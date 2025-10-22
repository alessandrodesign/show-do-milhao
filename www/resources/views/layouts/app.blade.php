<!doctype html>
<html lang="pt-br" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- 🔐 CSRF Token para requisições Fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Show do Milhão' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        .prize-highlight {
            animation: prize-flash 1.2s ease-in-out;
        }
        @keyframes prize-flash {
            0% { background-color: rgba(255, 193, 7, 0.1); }
            50% { background-color: rgba(255, 193, 7, 0.6); }
            100% { background-color: transparent; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 to-black text-white min-h-screen">
<div id="sfx" x-data="sfxInit()"></div>
<main class="container mx-auto py-8">
    {{ $slot ?? '' }}
    @yield('content')
</main>

<x-toast />
<x-confirm />

<button
    onclick="toggleMusic()"
    class="fixed bottom-6 left-6 bg-amber-400 text-black font-bold px-4 py-2 rounded-full shadow-lg hover:brightness-110">
    🎧 Música
</button>

<audio id="bg-music" src="{{ asset('sounds/bg-music.mp3') }}" preload="auto" loop></audio>
<audio id="sound-click" src="{{ asset('sounds/click.mp3') }}" preload="auto"></audio>
<audio id="sound-suspense" src="{{ asset('sounds/suspense.mp3') }}" preload="auto"></audio>
<audio id="sound-correct" src="{{ asset('sounds/correct.mp3') }}" preload="auto"></audio>
<audio id="sound-wrong" src="{{ asset('sounds/wrong.mp3') }}" preload="auto"></audio>
<audio id="sound-victory" src="{{ asset('sounds/victory.mp3') }}" preload="auto"></audio>

@stack('scripts')
</body>
</html>
