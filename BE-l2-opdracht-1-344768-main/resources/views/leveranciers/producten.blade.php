<x-magazijn-layout title="Geleverde producten">
    <h1>Geleverde producten</h1>

    <h2>Leverancier</h2>
    <p><strong>Naam:</strong> {{ $leverancier->Naam }}</p>
    <p><strong>Contactpersoon:</strong> {{ $leverancier->ContactPersoon }}</p>
    <p><strong>Leveranciernummer:</strong> {{ $leverancier->LeverancierNummer }}</p>
    <p><strong>Mobiel:</strong> {{ $leverancier->Mobiel }}</p>

    @if (count($producten) === 0)
        <p>Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin</p>
        <p>Je wordt over 3 seconden teruggestuurd naar het overzicht leveranciers.</p>

        <script>
            setTimeout(function () {
                window.location.href = "{{ route('leveranciers.index') }}";
            }, 3000);
        </script>
    @else
        <table>
            <thead>
                <tr>
                    <th>Naam product</th>
                    <th>Aantal in magazijn</th>
                    <th>Laatste levering</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($producten as $product)
                    <tr>
                        <td>{{ $product->ProductNaam }}</td>
                        <td>{{ $product->AantalAanwezig ?? 0 }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($product->DatumLaatsteLevering)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a class="terug" href="{{ route('leveranciers.index') }}">Terug naar overzicht leveranciers</a>
</x-magazijn-layout>
