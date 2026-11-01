<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeveringRequest;
use App\Models\Leverancier;
use App\Models\Product;
use App\Services\LeverancierPdoRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeveringController extends Controller
{
    public function __construct(private LeverancierPdoRepository $leveranciers) {}

    public function create(Leverancier $leverancier, Product $product): View
    {
        return view('leveringen.create', [
            'gegevens' => $this->gegevensVoorLevering($leverancier, $product),
        ]);
    }

    public function store(
        StoreLeveringRequest $request,
        Leverancier $leverancier,
        Product $product,
    ): View|RedirectResponse {
        $gegevens = $this->gegevensVoorLevering($leverancier, $product);
        $validated = $request->validated();

        $result = $this->leveranciers->addLevering(
            $leverancier->Id,
            $product->Id,
            (int) $validated['aantal_producteenheden'],
            $validated['datum_eerstvolgende_levering'],
        );

        if ($result['status'] === 'inactive') {
            return view('leveringen.create', [
                'gegevens' => $gegevens,
                'melding' => $result['message'],
            ]);
        }

        return redirect()->route('leveranciers.producten', $leverancier);
    }

    private function gegevensVoorLevering(Leverancier $leverancier, Product $product): object
    {
        $gegevens = $this->leveranciers->productVoorLevering($leverancier->Id, $product->Id);

        if ($gegevens === null) {
            throw new NotFoundHttpException;
        }

        return $gegevens;
    }
}
