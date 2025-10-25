<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Show do Milhão') }}</title>
    @viteReactRefresh
    @vite(['resources/js/App.css','resources/js/main.tsx'])
</head>
<body>
<div id="app"></div>
</body>
</html>
