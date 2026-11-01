<?php

namespace Tests\Feature;

use App\Models\Leverancier;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductLeveringTest extends TestCase
{
    use RefreshDatabase;

    public function test_adding_a_venco_mintnopjes_delivery_updates_stock_and_last_delivery_date(): void
    {
        $this->seed();
        Carbon::setTestNow(Carbon::parse('2026-11-01'));

        $medewerker = User::where('email', 'magazijn@example.com')->firstOrFail();
        $venco = Leverancier::where('Naam', 'Venco')->firstOrFail();
        $mintnopjes = Product::where('Naam', 'Mintnopjes')->firstOrFail();

        $this->actingAs($medewerker)
            ->from(route('leveringen.create', [$venco, $mintnopjes]))
            ->post(route('leveringen.store', [$venco, $mintnopjes]), [
                'aantal_producteenheden' => 25,
                'datum_eerstvolgende_levering' => '2024-05-29',
            ])
            ->assertRedirect(route('leveranciers.producten', $venco));

        $this->actingAs($medewerker)
            ->get(route('leveranciers.producten', $venco))
            ->assertOk()
            ->assertSeeInOrder([
                'Mintnopjes',
                '478',
                '01-11-2026',
            ]);

        $this->assertDatabaseHas('Magazijn', [
            'ProductId' => 1,
            'AantalAanwezig' => 478,
        ]);
    }

    public function test_inactive_winegums_cannot_be_added_and_redirects_after_four_seconds(): void
    {
        $this->seed();
        $medewerker = User::where('email', 'magazijn@example.com')->firstOrFail();
        $basset = Leverancier::where('Naam', 'Basset')->firstOrFail();
        $winegums = Product::where('Naam', 'Winegums')->firstOrFail();

        $response = $this->actingAs($medewerker)
            ->from(route('leveringen.create', [$basset, $winegums]))
            ->post(route('leveringen.store', [$basset, $winegums]), [
                'aantal_producteenheden' => 30,
                'datum_eerstvolgende_levering' => now()->toDateString(),
            ]);

        $response->assertOk();
        $response->assertSee('Het product Winegums van de leverancier Basset wordt niet meer geproduceerd');
        $response->assertSee('4000', false);

        $this->assertSame(1, DB::table('ProductPerLeverancier')->where('ProductId', 10)->count());
        $this->assertDatabaseHas('Magazijn', [
            'ProductId' => 10,
            'AantalAanwezig' => null,
        ]);
    }
}
