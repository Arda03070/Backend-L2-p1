<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Services\LeverancierPdoRepository;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeverancierController extends Controller
{
    public function __construct(private LeverancierPdoRepository $leveranciers) {}

    public function index(): View
    {
        return view('leveranciers.index', [
            'leveranciers' => $this->leveranciers->overzicht(),
        ]);
    }

    public function producten(Leverancier $leverancier): View
    {
        $gegevens = $this->leveranciers->find($leverancier->Id);

        if ($gegevens === null) {
            throw new NotFoundHttpException;
        }

        $producten = $this->leveranciers->geleverdeProducten($leverancier->Id);

        return view('leveranciers.producten', [
            'leverancier' => $gegevens,
            'producten' => $producten,
        ]);
    }
}
