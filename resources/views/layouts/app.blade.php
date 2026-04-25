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
    <body class="font-sans antialiased bg-slate-100 text-slate-800">
        <div class="min-h-screen lg:flex">
            @include('layouts.navigation')
            <div class="flex-1 min-w-0">
                <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
                    <div class="px-4 py-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Material Tracking System</p>
                            @isset($header)
                                <div class="mt-1">{{ $header }}</div>
                            @else
                                <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                            @endisset
                        </div>
                        <div class="text-right text-sm text-slate-500">
                            <div>{{ now()->format('d M Y') }}</div>
                            <div>{{ auth()->user()->name ?? '' }}</div>
                        </div>
                    </div>
                </header>

                <main class="p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
