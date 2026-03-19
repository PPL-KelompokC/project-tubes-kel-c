<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EcoChallenge - SDG 13 Climate Action')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#030213',
                        secondary: '#030213',
                        muted: '#ececf0',
                        accent: '#e9ebef',
                        destructive: '#d4183d',
                        sidebar: '#ffffff',
                    },
                    spacing: {
                        '4.5': '1.125rem',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="flex h-screen bg-gray-50 overflow-hidden">
        <!-- Sidebar -->
        @auth
            @if(Route::currentRouteName() !== 'landing')
                @include('layouts.partials.sidebar')
            @endif
        @endauth

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @if(Route::currentRouteName() !== 'landing')
                @include('layouts.partials.header')
            @endif

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto pb-20 lg:pb-0">
                @yield('content')
            </main>
        </div>

        <!-- Mobile bottom navigation -->
        @auth
            @if(Route::currentRouteName() !== 'landing')
                @include('layouts.partials.mobile-nav')
            @endif
        @endauth
    </div>

    <!-- Confetti Canvas -->
    <canvas id="confetti-canvas"></canvas>

    @stack('scripts')
</body>
</html>
