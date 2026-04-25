<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Material Tracking System') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-sky-500 text-2xl font-bold text-white shadow-lg shadow-sky-500/30">MT</div>
                    <h1 class="text-2xl font-bold">Material Tracking System</h1>
                    <p class="mt-2 text-sm text-slate-500">Sign in to manage requisitions, stock, and reports.</p>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
