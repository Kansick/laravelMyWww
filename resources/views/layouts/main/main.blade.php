<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="d-flex flex-column min-vh-100 m-0 p-0">
    @include('partials.menu', ['menuItems' => config('menu.navbar')])

    <main class="flex-grow-1" style="padding-top: 56px;">
        <section class="bg-dark-custom text-white d-flex align-items-center justify-content-center" style="min-height: 300px;">
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-3">@yield('header-page-main')</h1>
                <p class="lead text-white-50">@yield('sub-header-page-main')</p>
            </div>
        </section>
        @yield('content')
    </main>

    <footer class="bg-dark-custom w-100 mt-auto" style="min-height: 100px;">
        <div class="container-fluid px-0">
            <div class="d-flex align-items-center justify-content-end h-100 pe-4" style="min-height: 100px;">
                <span class="text-white-50 small">
                    {{ date('Y') }} @ Все права защищены
                </span>
            </div>
        </div>
    </footer>
</body>
</html>