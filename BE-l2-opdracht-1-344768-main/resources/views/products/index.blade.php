<x-magazijn-layout title="Overzicht Magazijn Jamin">
    <h1>Overzicht Magazijn Jamin</h1>
    <p>Producten in het magazijn</p>

    <table>
        <thead>
            <tr>
                <th>Barcode</th>
                <th>Naam product</th>
                <th>Verpakkingseenheid</th>
                <th>Aantal aanwezig</th>
                <th>Allergenen Info</th>
                <th>Leverantie Info</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($producten as $product)
                <tr>
                    <td>{{ $product->Barcode }}</td>
                    <td>{{ $product->Naam }}</td>
                    <td>{{ $product->magazijn?->VerpakkingsEenheidInKilogram }} kg</td>
                    <td>{{ $product->magazijn?->AantalAanwezig ?? 'NULL' }}</td>
                    <td>
                        <a
                            class="kruis"
                            href="{{ route('products.allergenen', $product) }}"
                            title="Bekijk allergeneninformatie"
                            aria-label="Bekijk allergeneninformatie van {{ $product->Naam }}"
                        >×</a>
                    </td>
                    <td>
                        <a
                            class="vraag"
                            href="{{ route('products.show', $product) }}"
                            title="Bekijk leverantie-informatie"
                            aria-label="Bekijk leverantie-informatie van {{ $product->Naam }}"
                        >?</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-magazijn-layout>
