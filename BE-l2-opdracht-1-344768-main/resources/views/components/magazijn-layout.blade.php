<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Magazijn Jamin' }}</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; background: #f7f7f7; color: #222; }
            header { background: #fff; border-bottom: 1px solid #ddd; }
            .balk { max-width: 960px; margin: 0 auto; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; }
            .balk a { color: #222; text-decoration: none; font-weight: bold; }
            main { max-width: 960px; margin: 0 auto; padding: 28px 20px 48px; }
            h1 { font-size: 24px; margin: 0 0 8px; }
            h2 { font-size: 18px; margin: 28px 0 10px; }
            table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 16px; }
            th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
            th { background: #1f4e79; color: #fff; }
            tbody tr:nth-child(even) { background: #f3f6f9; }
            button { padding: 6px 12px; cursor: pointer; }
            .kruis { color: #b00020; font-size: 22px; font-weight: bold; text-decoration: none; }
            .vraag { display: inline-block; width: 22px; height: 22px; line-height: 22px; text-align: center; background: #1f4e79; color: #fff; text-decoration: none; font-weight: bold; }
            .terug { display: inline-block; margin-top: 18px; }
        </style>
    </head>
    <body>
        <header>
            <div class="balk">
                <a href="{{ route('products.index') }}">Magazijn Jamin</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Uitloggen</button>
                </form>
            </div>
        </header>
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
