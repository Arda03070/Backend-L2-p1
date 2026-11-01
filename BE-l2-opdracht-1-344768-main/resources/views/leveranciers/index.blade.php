<x-magazijn-layout title="Overzicht leverancier">
    <h1>Overzicht leverancier</h1>
    <p>Alle verschillende leveranciers met het aantal producten dat zij aan Jamin leveren.</p>

    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Contactpersoon</th>
                <th>Leveranciernummer</th>
                <th>Mobiel</th>
                <th>Aantal verschillende producten</th>
                <th>Toon producten</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leveranciers as $leverancier)
                <tr>
                    <td>{{ $leverancier->Naam }}</td>
                    <td>{{ $leverancier->ContactPersoon }}</td>
                    <td>{{ $leverancier->LeverancierNummer }}</td>
                    <td>{{ $leverancier->Mobiel }}</td>
                    <td>{{ $leverancier->AantalProducten }}</td>
                    <td>
                        <a
                            class="box-icoon"
                            href="{{ route('leveranciers.producten', $leverancier->Id) }}"
                            title="Toon producten van {{ $leverancier->Naam }}"
                            aria-label="Toon producten van {{ $leverancier->Naam }}"
                        >▣</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-magazijn-layout>
