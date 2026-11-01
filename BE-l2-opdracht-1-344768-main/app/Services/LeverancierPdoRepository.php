<?php

namespace App\Services;

use Illuminate\Database\Connection;
use PDO;
use PDOStatement;
use RuntimeException;
use stdClass;

class LeverancierPdoRepository
{
    public function __construct(private Connection $connection) {}

    /**
     * @return list<stdClass>
     */
    public function overzicht(): array
    {
        if ($this->usesStoredProcedures()) {
            return $this->fetchAll('CALL sp_GetLeveranciersOverzicht()');
        }

        return $this->connection
            ->table('Leverancier as l')
            ->leftJoin('ProductPerLeverancier as ppl', 'ppl.LeverancierId', '=', 'l.Id')
            ->select([
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel',
            ])
            ->selectRaw('COUNT(DISTINCT ppl.ProductId) as AantalProducten')
            ->groupBy('l.Id', 'l.Naam', 'l.ContactPersoon', 'l.LeverancierNummer', 'l.Mobiel')
            ->orderByDesc('AantalProducten')
            ->orderBy('l.Naam')
            ->get()
            ->all();
    }

    public function find(int $leverancierId): ?stdClass
    {
        if ($this->usesStoredProcedures()) {
            return $this->fetchAll('CALL sp_GetLeverancierById(?)', [$leverancierId])[0] ?? null;
        }

        return $this->connection
            ->table('Leverancier')
            ->select(['Id', 'Naam', 'ContactPersoon', 'LeverancierNummer', 'Mobiel'])
            ->where('Id', $leverancierId)
            ->first();
    }

    /**
     * @return list<stdClass>
     */
    public function geleverdeProducten(int $leverancierId): array
    {
        if ($this->usesStoredProcedures()) {
            return $this->fetchAll('CALL sp_GetGeleverdeProducten(?)', [$leverancierId]);
        }

        return $this->connection
            ->table('ProductPerLeverancier as ppl')
            ->join('Product as p', 'p.Id', '=', 'ppl.ProductId')
            ->leftJoin('Magazijn as m', 'm.ProductId', '=', 'p.Id')
            ->select([
                'p.Id as ProductId',
                'p.Naam as ProductNaam',
                'm.AantalAanwezig',
            ])
            ->selectRaw('MAX(ppl.DatumLevering) as DatumLaatsteLevering')
            ->where('ppl.LeverancierId', $leverancierId)
            ->groupBy('p.Id', 'p.Naam', 'm.AantalAanwezig')
            ->orderByDesc('m.AantalAanwezig')
            ->get()
            ->all();
    }

    public function productVoorLevering(int $leverancierId, int $productId): ?stdClass
    {
        if ($this->usesStoredProcedures()) {
            return $this->fetchAll(
                'CALL sp_GetProductVoorLevering(?, ?)',
                [$leverancierId, $productId],
            )[0] ?? null;
        }

        $leverancier = $this->connection
            ->table('Leverancier')
            ->select(['Id', 'Naam', 'ContactPersoon', 'LeverancierNummer', 'Mobiel'])
            ->where('Id', $leverancierId)
            ->first();

        $product = $this->connection
            ->table('Product')
            ->select(['Id', 'Naam', 'IsActief'])
            ->where('Id', $productId)
            ->first();

        if ($leverancier === null || $product === null) {
            return null;
        }

        return (object) [
            'LeverancierId' => $leverancier->Id,
            'LeverancierNaam' => $leverancier->Naam,
            'ContactPersoon' => $leverancier->ContactPersoon,
            'LeverancierNummer' => $leverancier->LeverancierNummer,
            'Mobiel' => $leverancier->Mobiel,
            'ProductId' => $product->Id,
            'ProductNaam' => $product->Naam,
            'ProductIsActief' => $this->isActief($product->IsActief),
        ];
    }

    /**
     * @return array{status: string, message: ?string}
     */
    public function addLevering(
        int $leverancierId,
        int $productId,
        int $aantal,
        string $datumEerstVolgendeLevering,
    ): array {
        if ($this->usesStoredProcedures()) {
            $result = $this->fetchAll(
                'CALL sp_AddProductLevering(?, ?, ?, ?)',
                [$leverancierId, $productId, $aantal, $datumEerstVolgendeLevering],
            )[0] ?? null;

            return [
                'status' => (string) ($result->Status ?? 'error'),
                'message' => $result->Message ?? null,
            ];
        }

        return $this->addLeveringWithoutStoredProcedure(
            $leverancierId,
            $productId,
            $aantal,
            $datumEerstVolgendeLevering,
        );
    }

    /**
     * @return array{status: string, message: ?string}
     */
    private function addLeveringWithoutStoredProcedure(
        int $leverancierId,
        int $productId,
        int $aantal,
        string $datumEerstVolgendeLevering,
    ): array {
        $product = $this->productVoorLevering($leverancierId, $productId);

        if ($product === null) {
            throw new RuntimeException('Product of leverancier is niet gevonden.');
        }

        if (! $this->isActief($product->ProductIsActief)) {
            return [
                'status' => 'inactive',
                'message' => sprintf(
                    'Het product %s van de leverancier %s wordt niet meer geproduceerd',
                    $product->ProductNaam,
                    $product->LeverancierNaam,
                ),
            ];
        }

        $now = now();

        $this->connection->table('ProductPerLeverancier')->insert([
            'LeverancierId' => $leverancierId,
            'ProductId' => $productId,
            'DatumLevering' => $now->toDateString(),
            'Aantal' => $aantal,
            'DatumEerstVolgendeLevering' => $datumEerstVolgendeLevering,
            'IsActief' => true,
            'Opmerking' => null,
            'DatumAangemaakt' => $now,
            'DatumGewijzigd' => $now,
        ]);

        $huidigAantal = $this->connection
            ->table('Magazijn')
            ->where('ProductId', $productId)
            ->value('AantalAanwezig');

        $this->connection
            ->table('Magazijn')
            ->where('ProductId', $productId)
            ->update([
                'AantalAanwezig' => ((int) $huidigAantal) + $aantal,
                'DatumGewijzigd' => $now,
            ]);

        return [
            'status' => 'ok',
            'message' => null,
        ];
    }

    /**
     * @param  list<mixed>  $parameters
     * @return list<stdClass>
     */
    private function fetchAll(string $sql, array $parameters = []): array
    {
        $statement = $this->pdo()->prepare($sql);

        if (! $statement instanceof PDOStatement) {
            throw new RuntimeException('De stored procedure kon niet worden voorbereid.');
        }

        $statement->execute($parameters);
        $rows = $statement->fetchAll(PDO::FETCH_OBJ);
        $statement->closeCursor();

        return $rows;
    }

    private function isActief(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_numeric($value)) {
            return (int) $value === 1;
        }

        return $value !== "\0" && $value !== '' && $value !== '0' && $value !== null;
    }

    private function usesStoredProcedures(): bool
    {
        return $this->connection->getDriverName() === 'mysql';
    }

    private function pdo(): PDO
    {
        return $this->connection->getPdo();
    }
}
