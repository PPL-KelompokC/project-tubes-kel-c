<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EcoChallenge - SDG 13 Climate Action')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js & SweetAlert2 -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- Pusher & Echo (for Reverb) -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env('REVERB_APP_KEY') }}',
            wsHost: '{{ env('REVERB_HOST', 'localhost') }}',
            wsPort: {{ env('REVERB_PORT', 8080) }},
            wssPort: {{ env('REVERB_PORT', 8080) }},
            forceTLS: (window.location.protocol === 'https:'),
            enabledTransports: ['ws', 'wss'],
        });

        @auth
            if (typeof Echo !== 'undefined') {
                const userId = {{ auth()->id() }};
                Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        console.log('🔔 New global notification:', notification);
                        
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                        
                        Toast.fire({
                            icon: 'info',
                            title: notification.title || 'New Notification',
                            text: notification.message || ''
                        });

                        fetch('/notifications/unread-count', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            }
                        }).then(res => res.json()).then(data => {
                            const count = data.count || 0;
                            ['header-notif-badge', 'sidebar-notif-badge', 'unread-tab-badge'].forEach(id => {
                                const badge = document.getElementById(id);
                                if (badge) {
                                    badge.textContent = count;
                                    badge.classList.toggle('hidden', count === 0);
                                }
                            });
                            
                            if (window.location.pathname.includes('/notifications')) {
                                window.location.reload();
                            }
                        }).catch(err => console.error(err));
                    });
            }
        @endauth
    </script>

    @stack('scripts')
</body>
</html>
