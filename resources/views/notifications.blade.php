@extends('layouts.app')

@section('title', 'Notifications - TerraVerde')

@section('content')
@php
    // Get real notifications from database
    $filter = request('filter', 'all');
    $user = auth()->user();

    if ($filter === 'unread') {
        $notifications = $user->unreadNotifications()->latest()->get();
    } else {
        $notifications = $user->notifications()->latest()->get();
    }

    $unreadCount = $user->unreadNotifications()->count();

    $notifConfig = [
        'streak'    => ['icon' => 'flame',       'bg' => 'bg-orange-100', 'iconColor' => 'text-orange-500', 'label' => 'Streak',    'badgeBg' => 'bg-orange-100', 'badgeText' => 'text-orange-700'],
        'challenge' => ['icon' => 'calendar',    'bg' => 'bg-green-100',  'iconColor' => 'text-green-600',  'label' => 'Challenge', 'badgeBg' => 'bg-green-100',  'badgeText' => 'text-green-700'],
        'social'    => ['icon' => 'heart',       'bg' => 'bg-pink-100',   'iconColor' => 'text-pink-500',   'label' => 'Social',    'badgeBg' => 'bg-pink-100',   'badgeText' => 'text-pink-700'],
        'badge'     => ['icon' => 'trophy',      'bg' => 'bg-yellow-100', 'iconColor' => 'text-yellow-600', 'label' => 'Badge',     'badgeBg' => 'bg-yellow-100', 'badgeText' => 'text-yellow-700'],
        'ranking'   => ['icon' => 'trending-up', 'bg' => 'bg-blue-100',   'iconColor' => 'text-blue-500',   'label' => 'Ranking',   'badgeBg' => 'bg-blue-100',   'badgeText' => 'text-blue-700'],
        'referral'  => ['icon' => 'gift',        'bg' => 'bg-purple-100', 'iconColor' => 'text-purple-500', 'label' => 'Referral',  'badgeBg' => 'bg-purple-100', 'badgeText' => 'text-purple-700'],
    ];
@endphp

<div class="p-4 lg:p-6 max-w-3xl mx-auto space-y-5">
    <!-- Header controls -->
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'unread' => 'Unread'] as $f => $label)
                <a
                    href="{{ route('notifications', ['filter' => $f]) }}"
                    class="px-4 py-2 rounded-full text-sm font-semibold transition-all flex items-center gap-1.5 {{ $filter === $f ? 'bg-green-600 text-white shadow-sm shadow-green-200' : 'bg-white text-gray-600 border border-gray-200 hover:border-green-300' }}"
                >
                    @if($f === 'all')
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    @endif
                    {{ $label }}
                    @if($f === 'unread')
                        <span id="unread-tab-badge" class="ml-0.5 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none {{ $unreadCount > 0 ? '' : 'hidden' }}">{{ $unreadCount }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        <button
            id="mark-all-read-btn"
            class="text-sm text-green-600 hover:text-green-700 font-semibold flex items-center gap-1.5 transition-colors {{ $unreadCount > 0 ? '' : 'hidden' }}"
            onclick="markAllRead()"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17l-5-5"/></svg>
            Mark all read
        </button>
    </div>

    <!-- Notifications list -->
    <div id="notifications-container" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-bounce-in">
        @if($notifications->count() === 0)
            <div id="empty-state" class="py-16 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-3"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <p class="text-sm font-semibold text-gray-600">All caught up!</p>
                <p class="text-xs text-gray-400 mt-1">No {{ $filter === 'unread' ? 'unread ' : '' }}notifications</p>
            </div>
        @else
            <div id="notifications-list" class="divide-y divide-gray-50">
                @foreach($notifications as $i => $notif)
                    @php
                        $data = $notif->data;
                        $category = $data['category'] ?? 'challenge';
                        $config = $notifConfig[$category] ?? $notifConfig['challenge'];
                        $isUnread = is_null($notif->read_at);
                    @endphp
                    <div
                        class="notif-item flex items-start gap-3 px-4 py-4 hover:bg-gray-50/80 transition-colors animate-count-in {{ $isUnread ? 'bg-green-50/40' : '' }}"
                        style="animation-delay: {{ $i * 0.04 }}s"
                        data-id="{{ $notif->id }}"
                    >
                        <!-- Icon circle -->
                        <div class="w-10 h-10 rounded-full {{ $config['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                            @switch($config['icon'])
                                @case('flame')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['iconColor'] }}"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                                    @break
                                @case('calendar')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['iconColor'] }}"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    @break
                                @case('heart')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['iconColor'] }}"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                    @break
                                @case('trophy')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['iconColor'] }}"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                                    @break
                                @case('trending-up')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['iconColor'] }}"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                                    @break
                                @case('gift')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['iconColor'] }}"><polyline points="20 12 20 22 4 22 4 12"/><rect width="20" height="5" x="2" y="7"/><line x1="12" x2="12" y1="22" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                    @break
                            @endswitch
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold {{ $isUnread ? 'text-gray-900' : 'text-gray-700' }}">
                                    {{ $data['emoji'] ?? '🔔' }} {{ $data['title'] ?? 'Notification' }}
                                </p>
                                <div class="flex items-center gap-2 flex-shrink-0 mt-0.5">
                                    <span class="text-[11px] text-gray-400">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                                    @if($isUnread)
                                        <div class="unread-dot w-2.5 h-2.5 bg-green-500 rounded-full flex-shrink-0"></div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $data['message'] ?? '' }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $config['badgeBg'] }} {{ $config['badgeText'] }}">
                                    {{ $config['label'] }}
                                </span>
                                @if($isUnread)
                                    <button
                                        class="mark-read-btn text-[10px] text-green-600 hover:text-green-700 font-medium transition-colors"
                                        onclick="markAsRead('{{ $notif->id }}', this)"
                                    >Mark read</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Mark single notification as read
    function markAsRead(id, btn) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = btn.closest('.notif-item');
                item.classList.remove('bg-green-50/40');

                // Remove green dot
                const dot = item.querySelector('.unread-dot');
                if (dot) dot.remove();

                // Remove mark-read button
                btn.remove();

                // Update title text color
                const title = item.querySelector('.text-gray-900');
                if (title) {
                    title.classList.remove('text-gray-900');
                    title.classList.add('text-gray-700');
                }

                updateUnreadCountDisplay();
            }
        })
        .catch(err => console.error('Failed to mark as read:', err));
    }

    // Mark all notifications as read
    function markAllRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update all unread items visually
                document.querySelectorAll('.notif-item.bg-green-50\\/40').forEach(item => {
                    item.classList.remove('bg-green-50/40');
                });
                document.querySelectorAll('.unread-dot').forEach(dot => dot.remove());
                document.querySelectorAll('.mark-read-btn').forEach(btn => btn.remove());
                document.querySelectorAll('.notif-item .text-gray-900').forEach(el => {
                    el.classList.remove('text-gray-900');
                    el.classList.add('text-gray-700');
                });

                // Hide mark-all button
                const markAllBtn = document.getElementById('mark-all-read-btn');
                if (markAllBtn) markAllBtn.classList.add('hidden');

                updateUnreadCountDisplay();
            }
        })
        .catch(err => console.error('Failed to mark all as read:', err));
    }

    // Update unread count badges across the page
    function updateUnreadCountDisplay() {
        fetch('/notifications/unread-count', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(res => res.json())
        .then(data => {
            const count = data.count || 0;

            // Update tab badge
            const tabBadge = document.getElementById('unread-tab-badge');
            if (tabBadge) {
                tabBadge.textContent = count;
                tabBadge.classList.toggle('hidden', count === 0);
            }

            // Update header badge
            const headerBadge = document.getElementById('header-notif-badge');
            if (headerBadge) {
                headerBadge.textContent = count;
                headerBadge.classList.toggle('hidden', count === 0);
            }

            // Update sidebar badge
            const sidebarBadge = document.getElementById('sidebar-notif-badge');
            if (sidebarBadge) {
                sidebarBadge.textContent = count;
                sidebarBadge.classList.toggle('hidden', count === 0);
            }

            // Hide mark-all button if no unread
            const markAllBtn = document.getElementById('mark-all-read-btn');
            if (markAllBtn) markAllBtn.classList.toggle('hidden', count === 0);
        });
    }

    // ── Global Real-time notifications handled in app.blade.php ──────────

    // ── Polling fallback (every 30 seconds) ──────────────────────────
    // This ensures notifications update even without WebSocket
    let lastUnreadCount = {{ $unreadCount }};
    setInterval(() => {
        fetch('/notifications/unread-count', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        })
        .then(res => res.json())
        .then(data => {
            if (data.count !== lastUnreadCount) {
                const oldCount = lastUnreadCount;
                lastUnreadCount = data.count;

                // If count increased, a new notification arrived
                if (data.count > oldCount) {
                    window.location.reload();
                }

                // Update badge displays
                updateUnreadCountDisplay();
            }
        })
        .catch(() => {}); // Silently fail on network issues
    }, 30000);
</script>
@endpush
@endsection
