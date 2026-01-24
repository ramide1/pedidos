@php
$useSidebar = auth()->check() && (auth()->user()->admin || auth()->user()->restaurantes()->exists());
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @if (session('success'))
    <meta name="session-success" content="{{ session('success') }}">
    @elseif (session('error'))
    <meta name="session-error" content="{{ session('error') }}">
    @endif
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 animate__animated animate__fadeIn">
    @if($useSidebar)
    <x-layouts::app.sidebar :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts::app.sidebar>
    @else
    <x-layouts::app.header :title="$title ?? null">
        {{ $slot }}
    </x-layouts::app.header>
    @endif

    @fluxScripts
</body>

</html>