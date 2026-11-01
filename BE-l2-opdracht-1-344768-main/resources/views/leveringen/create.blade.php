<x-magazijn-layout title="Levering product">
    <h1>Levering product</h1>

    <h2>Leverancier</h2>
    <p><strong>Naam:</strong> {{ $gegevens->LeverancierNaam }}</p>
    <p><strong>Contactpersoon:</strong> {{ $gegevens->ContactPersoon }}</p>
    <p><strong>Leveranciernummer:</strong> {{ $gegevens->LeverancierNummer }}</p>
    <p><strong>Mobiel:</strong> {{ $gegevens->Mobiel }}</p>

    <h2>Product</h2>
    <p><strong>Naam product:</strong> {{ $gegevens->ProductNaam }}</p>

    @if (! empty($melding))
        <p class="melding">{{ $melding }}</p>
        <p>Je wordt over 4 seconden teruggestuurd naar geleverde producten.</p>

        <script>
            setTimeout(function () {
                window.location.href = "{{ route('leveranciers.producten', $gegevens->LeverancierId) }}";
            }, 4000);
        </script>
    @else
        <form method="POST" action="{{ route('leveringen.store', [$gegevens->LeverancierId, $gegevens->ProductId]) }}">
            @csrf

            <p>
                <label for="aantal_producteenheden">Aantal producteenheden</label><br>
                <input
                    id="aantal_producteenheden"
                    name="aantal_producteenheden"
                    type="number"
                    min="1"
                    value="{{ old('aantal_producteenheden') }}"
                    required
                >
            </p>
            <x-input-error :messages="$errors->get('aantal_producteenheden')" />

            <p>
                <label for="datum_eerstvolgende_levering">Datum eerstvolgende levering</label><br>
                <input
                    id="datum_eerstvolgende_levering"
                    name="datum_eerstvolgende_levering"
                    type="date"
                    value="{{ old('datum_eerstvolgende_levering') }}"
                    required
                >
            </p>
            <x-input-error :messages="$errors->get('datum_eerstvolgende_levering')" />

            <p>
                <button type="submit">Sla op</button>
            </p>
        </form>
    @endif

    <a class="terug" href="{{ route('leveranciers.producten', $gegevens->LeverancierId) }}">Terug naar geleverde producten</a>
</x-magazijn-layout>
