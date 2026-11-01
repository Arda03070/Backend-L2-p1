<?php

namespace Tests\Feature;

use App\Models\Leverancier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeverancierOverzichtTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_leveranciers_index_to_login(): void
    {
        $response = $this->get(route('leveranciers.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_homepage_shows_links_to_product_and_supplier_overviews(): void
    {
        $this->seed();
        $medewerker = User::where('email', 'magazijn@example.com')->firstOrFail();

        $response = $this->actingAs($medewerker)->get(route('home'));

        $response->assertOk();
        $response->assertSee('Overzicht producten Jamin');
        $response->assertSee('Overzicht Leveranciers');
    }

    public function test_leveranciers_are_shown_with_product_counts_sorted_descending(): void
    {
        $this->seed();
        $medewerker = User::where('email', 'magazijn@example.com')->firstOrFail();

        $response = $this->actingAs($medewerker)->get(route('leveranciers.index'));

        $response->assertOk();
        $response->assertSee('Overzicht leverancier');
        $response->assertSeeInOrder([
            'Astra Sweets',
            'De Bron',
            'Haribo',
            'Venco',
            'Basset',
            'Quality Street',
        ]);
        $response->assertSee('Johan Nooij');
        $response->assertSee('L1029234586');
    }

    public function test_venco_shows_delivered_products_sorted_by_stock_descending(): void
    {
        $this->seed();
        $medewerker = User::where('email', 'magazijn@example.com')->firstOrFail();
        $venco = Leverancier::where('Naam', 'Venco')->firstOrFail();

        $response = $this->actingAs($medewerker)
            ->get(route('leveranciers.producten', $venco));

        $response->assertOk();
        $response->assertSee('Geleverde producten');
        $response->assertSee('Venco');
        $response->assertSeeInOrder([
            'Mintnopjes',
            '453',
            '18-11-2024',
            'Schoolkrijt',
            '400',
            'Honingdrop',
            '1',
        ]);
    }

    public function test_quality_street_shows_no_products_message_and_redirects_after_three_seconds(): void
    {
        $this->seed();
        $medewerker = User::where('email', 'magazijn@example.com')->firstOrFail();
        $qualityStreet = Leverancier::where('Naam', 'Quality Street')->firstOrFail();

        $response = $this->actingAs($medewerker)
            ->get(route('leveranciers.producten', $qualityStreet));

        $response->assertOk();
        $response->assertSee('Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin');
        $response->assertSee('3000', false);
        $response->assertDontSee('Naam product');
    }
}
