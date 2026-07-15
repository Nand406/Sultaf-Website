<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sultaf' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sultaf-pattern flex flex-col">

    {{--
        Mobile  (< lg)  : lebar dibatasi max-w-md, mirip tampilan app HP, nav di bawah.
        Desktop (>= lg) : lebar PENUH (max-w-none), nav pindah ke atas (header), nav bawah disembunyikan.
    --}}
    <div class="flex-1 w-full max-w-md lg:max-w-none mx-auto pb-24 lg:pb-10 px-0 lg:px-10 xl:px-16">
        {{ $slot }}
    </div>

    {{-- Bottom Navigation — HANYA tampil di mobile/tablet --}}
    <nav class="lg:hidden fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-sultaf-border
                flex items-stretch justify-around px-2 py-2 z-40">
        @include('components.nav-items')
    </nav>
</body>
</html>
