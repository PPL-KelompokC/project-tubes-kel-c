@php
    $mobileNavItems = [
        ['path' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Home'],
        ['path' => 'challenges', 'icon' => 'list-checks', 'label' => 'Challenges'],
        ['path' => 'carbon', 'icon' => 'leaf', 'label' => 'Carbon'],
        ['path' => 'leaderboard', 'icon' => 'trophy', 'label' => 'Board'],
        ['path' => 'feed', 'icon' => 'activity', 'label' => 'Feed'],
        ['path' => 'profile', 'icon' => 'star', 'label' => 'Profile'],
    ];
@endphp

<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-30 px-2 py-2 safe-area-pb">
    <div class="flex items-center justify-around">
        @foreach($mobileNavItems as $item)
            @php $isActive = Request::routeIs($item['path']); @endphp
            <a href="{{ route($item['path']) }}" class="mobile-nav-item flex flex-col items-center gap-0.5 px-2 py-1 rounded-xl">
                <div class="p-1.5 rounded-xl transition-all duration-150 {{ $isActive ? 'bg-green-100' : '' }}">
                    @include('layouts.partials.icons.' . $item['icon'], ['class' => 'w-5 h-5 transition-colors ' . ($isActive ? 'text-green-600' : 'text-gray-400')])
                </div>
                <span class="text-[10px] font-medium {{ $isActive ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach
    </div>
</nav>
