<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-black/50">
        <div class="bg-gray-50 text-black/50 min-h-screen">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <svg class="h-12 w-auto text-[#FF2D20] lg:h-16" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M61.8548 14.6253C61.8778 14.7102 61.8894 14.7979 61.8894 14.8868V28.5713C61.8894 28.818 61.759 29.0467 61.5455 29.1712L31.7548 46.6554C31.5413 46.7799 31.2806 46.7799 31.0671 46.6554L1.27643 29.1712C1.06293 29.0467 0.932617 28.818 0.932617 28.5713V14.8868C0.932617 14.7979 0.944617 14.7102 0.967617 14.6253C0.982617 14.569 1.00562 14.515 1.03562 14.4652C1.06912 14.4077 1.11012 14.3552 1.15712 14.3102L12.7666 3.24672C13.0016 3.02372 13.3716 3.02372 13.6066 3.24672L24.1336 13.2467C24.3686 13.4697 24.7386 13.4697 24.9736 13.2467L35.5006 3.24672C35.7356 3.02372 36.1056 3.02372 36.3406 3.24672L47.9501 14.3102C47.9971 14.3552 48.0381 14.4077 48.0716 14.4652C48.1016 14.515 48.1246 14.569 48.1396 14.6253H61.8548Z" fill="currentColor"/>
                            </svg>
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a href="{{ route('products.index') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                        Magazijn
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <a href="https://laravel.com/docs" target="_blank" class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10">
                                <div class="relative flex items-center gap-6 lg:items-end">
                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                        <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path fill="#FF2D20" d="M23 4a1 1 0 0 0-1.447-.894L12.224 7.77a.5.5 0 0 1-.448 0L2.447 3.106A1 1 0 0 0 1 4v13.382a1.5 1.5 0 0 0 .777 1.316l9.5 5.5a1.5 1.5 0 0 0 1.446 0l9.5-5.5A1.5 1.5 0 0 0 23 17.382V4Z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-semibold text-black">Documentation</h2>
                                </div>
                                <p class="text-sm leading-relaxed">Laravel has wonderful documentation covering every aspect of the framework. Whether you are a newcomer or have prior experience with Laravel, we recommend reading our documentation from beginning to end.</p>
                            </a>

                            <a href="https://laracasts.com" target="_blank" class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                    <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path fill="#FF2D20" d="M8.75 4.5a.75.75 0 0 0-1.5 0v15a.75.75 0 0 0 1.5 0v-15Zm8 0a.75.75 0 0 0-1.5 0v15a.75.75 0 0 0 1.5 0v-15Z"/>
                                        <path fill="#FF2D20" d="M4 7.75A.75.75 0 0 1 4.75 7h14.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 7.75Zm0 8.5a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H4.75a.75.75 0 0 1-.75-.75Z"/>
                                    </svg>
                                </div>
                                <div class="pt-3 sm:pt-5">
                                    <h2 class="text-xl font-semibold text-black">Laracasts</h2>
                                    <p class="mt-4 text-sm leading-relaxed">Laracasts offers thousands of video tutorials on Laravel, PHP, and JavaScript development.</p>
                                </div>
                            </a>

                            <a href="https://laravel-news.com" target="_blank" class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                    <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path fill="#FF2D20" fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.75 4.5a.75.75 0 0 0-1.5 0v5.25c0 .414.336.75.75.75h3.75a.75.75 0 0 0 0-1.5h-3V6.75Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="pt-3 sm:pt-5">
                                    <h2 class="text-xl font-semibold text-black">Laravel News</h2>
                                    <p class="mt-4 text-sm leading-relaxed">Laravel News is a community driven portal and newsletter aggregating all of the latest and most important news in the Laravel ecosystem.</p>
                                </div>
                            </a>
                        </div>
                    </main>

                    <footer class="py-16 text-center text-sm text-black">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
