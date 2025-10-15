<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 relative">

    {{-- Navigation --}}
    @include('layouts.navigation')

    {{-- Header --}}
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Page content --}}
    <main>
        {{ $slot }}
    </main>

    <!-- ===== Nút Liên hệ ===== -->
    <a href="tel:0123456789"
       class="fixed bottom-24 right-5 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-full shadow-lg flex items-center gap-2 z-50">
        <i class="fa fa-headphones"></i>
        <span>Liên hệ</span>
    </a>

    <!-- ===== Nút Lên đầu ===== -->
    <button id="btnScrollTop"
        class="hidden fixed bottom-5 right-5 bg-gray-700 hover:bg-gray-800 text-white p-3 rounded-full shadow-lg z-50 transition-all duration-300">
        <i class="fa fa-arrow-up"></i>
    </button>

    <script>
        // Ẩn/hiện nút cuộn lên đầu
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('btnScrollTop');
            if (window.scrollY > 300) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        });

        // Cuộn mượt lên đầu
        document.getElementById('btnScrollTop').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
