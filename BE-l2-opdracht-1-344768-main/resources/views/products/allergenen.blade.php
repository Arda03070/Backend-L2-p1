<x-magazijn-layout title="Overzicht Allergenen">
    <h1>Overzicht Allergenen</h1>

    <p><strong>Naam Product:</strong> {{ $product->Naam }}</p>
    <p><strong>Barcode:</strong> {{ $product->Barcode }}</p>

    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Omschrijving</th>
            </tr>
        </thead>
        <tbody>
            @if ($product->allergenen->isEmpty())
                <tr>
                    <td colspan="2">
                        In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken
                    </td>
                </tr>
            @else
                @foreach ($product->allergenen as $allergeen)
                    <tr>
                        <td>{{ $allergeen->Naam }}</td>
                        <td>{{ $allergeen->Omschrijving }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    @if ($product->allergenen->isEmpty())
        <p>Je gaat over vier seconden terug naar Overzicht Magazijn Jamin.</p>

        <script>
            setTimeout(function () {
                window.location.href = "{{ route('products.index') }}";
            }, 4000);
        </script>
    @endif

    <a class="terug" href="{{ route('products.index') }}">Terug naar Overzicht Magazijn Jamin</a>
</x-magazijn-layout>
