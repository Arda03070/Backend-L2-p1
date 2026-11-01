<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = [
        'Product',
        'Allergeen',
        'Leverancier',
        'Magazijn',
        'ProductPerAllergeen',
        'ProductPerLeverancier',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            $this->addSysteemVeldenIfMissing($table);
        }

        $this->ensureQualityStreetExists();
        $this->deactivateWinegums();
        $this->createStoredProcedures();
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeverancierById');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetGeleverdeProducten');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetProductVoorLevering');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_AddProductLevering');
        }

        DB::table('Leverancier')->where('Id', 6)->delete();
        DB::table('Product')->where('Id', 10)->update(['IsActief' => true]);
    }

    private function addSysteemVeldenIfMissing(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $missing = collect(['IsActief', 'Opmerking', 'DatumAangemaakt', 'DatumGewijzigd'])
            ->reject(fn (string $column): bool => Schema::hasColumn($table, $column));

        if ($missing->isEmpty()) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($table): void {
            if (! Schema::hasColumn($table, 'IsActief')) {
                $blueprint->boolean('IsActief')->default(true);
            }

            if (! Schema::hasColumn($table, 'Opmerking')) {
                $blueprint->string('Opmerking', 255)->nullable();
            }

            if (! Schema::hasColumn($table, 'DatumAangemaakt')) {
                $blueprint->dateTime('DatumAangemaakt')->useCurrent();
            }

            if (! Schema::hasColumn($table, 'DatumGewijzigd')) {
                $blueprint->dateTime('DatumGewijzigd')->useCurrent();
            }
        });
    }

    private function ensureQualityStreetExists(): void
    {
        $exists = DB::table('Leverancier')->where('Id', 6)->exists();

        if ($exists) {
            return;
        }

        DB::table('Leverancier')->insert([
            'Id' => 6,
            'Naam' => 'Quality Street',
            'ContactPersoon' => 'Johan Nooij',
            'LeverancierNummer' => 'L1029234586',
            'Mobiel' => '06-23458456',
            'IsActief' => true,
            'Opmerking' => null,
        ]);
    }

    private function deactivateWinegums(): void
    {
        DB::table('Product')
            ->where('Id', 10)
            ->update(['IsActief' => false]);
    }

    private function createStoredProcedures(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeverancierById');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetGeleverdeProducten');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetProductVoorLevering');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_AddProductLevering');

        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetLeveranciersOverzicht()
BEGIN
    SELECT
         l.Id
        ,l.Naam
        ,l.ContactPersoon
        ,l.LeverancierNummer
        ,l.Mobiel
        ,COUNT(DISTINCT ppl.ProductId) AS AantalProducten
    FROM Leverancier AS l
    LEFT JOIN ProductPerLeverancier AS ppl
        ON ppl.LeverancierId = l.Id
    GROUP BY
         l.Id
        ,l.Naam
        ,l.ContactPersoon
        ,l.LeverancierNummer
        ,l.Mobiel
    ORDER BY
         AantalProducten DESC
        ,l.Naam ASC;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetLeverancierById(IN pLeverancierId INT UNSIGNED)
BEGIN
    SELECT
         Id
        ,Naam
        ,ContactPersoon
        ,LeverancierNummer
        ,Mobiel
    FROM Leverancier
    WHERE Id = pLeverancierId;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetGeleverdeProducten(IN pLeverancierId INT UNSIGNED)
BEGIN
    SELECT
         p.Id AS ProductId
        ,p.Naam AS ProductNaam
        ,m.AantalAanwezig
        ,MAX(ppl.DatumLevering) AS DatumLaatsteLevering
    FROM ProductPerLeverancier AS ppl
    INNER JOIN Product AS p
        ON p.Id = ppl.ProductId
    LEFT JOIN Magazijn AS m
        ON m.ProductId = p.Id
    WHERE ppl.LeverancierId = pLeverancierId
    GROUP BY
         p.Id
        ,p.Naam
        ,m.AantalAanwezig
    ORDER BY m.AantalAanwezig DESC;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetProductVoorLevering(
    IN pLeverancierId INT UNSIGNED,
    IN pProductId INT UNSIGNED
)
BEGIN
    SELECT
         l.Id AS LeverancierId
        ,l.Naam AS LeverancierNaam
        ,l.ContactPersoon
        ,l.LeverancierNummer
        ,l.Mobiel
        ,p.Id AS ProductId
        ,p.Naam AS ProductNaam
        ,CAST(p.IsActief AS UNSIGNED) AS ProductIsActief
    FROM Leverancier AS l
    INNER JOIN Product AS p
        ON p.Id = pProductId
    WHERE l.Id = pLeverancierId;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_AddProductLevering(
    IN pLeverancierId INT UNSIGNED,
    IN pProductId INT UNSIGNED,
    IN pAantal INT UNSIGNED,
    IN pDatumEerstVolgendeLevering DATE
)
BEGIN
    DECLARE vIsActief TINYINT(1) DEFAULT 1;
    DECLARE vProductNaam VARCHAR(255);
    DECLARE vLeverancierNaam VARCHAR(255);

    SELECT
         CAST(p.IsActief AS UNSIGNED)
        ,p.Naam
        ,l.Naam
    INTO
         vIsActief
        ,vProductNaam
        ,vLeverancierNaam
    FROM Product AS p
    INNER JOIN Leverancier AS l
        ON l.Id = pLeverancierId
    WHERE p.Id = pProductId;

    IF vIsActief = 0 THEN
        SELECT
             'inactive' AS Status
            ,CONCAT(
                'Het product ',
                vProductNaam,
                ' van de leverancier ',
                vLeverancierNaam,
                ' wordt niet meer geproduceerd'
             ) AS Message;
    ELSE
        INSERT INTO ProductPerLeverancier
        (
             LeverancierId
            ,ProductId
            ,DatumLevering
            ,Aantal
            ,DatumEerstVolgendeLevering
            ,IsActief
            ,Opmerking
            ,DatumAangemaakt
            ,DatumGewijzigd
        )
        VALUES
        (
             pLeverancierId
            ,pProductId
            ,CURDATE()
            ,pAantal
            ,pDatumEerstVolgendeLevering
            ,1
            ,NULL
            ,NOW(6)
            ,NOW(6)
        );

        UPDATE Magazijn
        SET
             AantalAanwezig = IFNULL(AantalAanwezig, 0) + pAantal
            ,DatumGewijzigd = NOW(6)
        WHERE ProductId = pProductId;

        SELECT
             'ok' AS Status
            ,NULL AS Message;
    END IF;
END
SQL);
    }
};
