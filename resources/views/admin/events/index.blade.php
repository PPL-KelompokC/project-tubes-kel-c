@extends('admin.layouts.app')

@section('content')
<style>
    /* ── Stat Cards ── */
    .ev-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .ev-stat-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-radius: 1rem;
        border: 2px solid transparent;
    }
    .ev-stat-card.pending  { background: #dde8f8; border-color: #a8c0ef; }
    .ev-stat-card.approved { background: #d6f0e2; border-color: #8fd4ae; }
    .ev-stat-card.rejected { background: #fce3d5; border-color: #f4a97b; }
    .ev-stat-label { font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: .25rem; }
    .ev-stat-count { font-size: 2rem; font-weight: 800; color: #111827; line-height: 1; }
    .ev-stat-icon  { width: 72px; height: 72px; opacity: .40; flex-shrink: 0; }

    /* ── Toolbar ── */
    .ev-toolbar {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .ev-search {
        flex: 1;
        display: flex;
        align-items: center;
        gap: .5rem;
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: .625rem;
        padding: .55rem 1rem;
    }
    .ev-search input {
        border: none;
        outline: none;
        background: transparent;
        font-size: .875rem;
        color: #374151;
        width: 100%;
    }
    .ev-search input::placeholder { color: #9ca3af; }
    .ev-sort-btn {
        display: flex;
        align-items: center;
        gap: .4rem;
        padding: .55rem 1.1rem;
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: .625rem;
        font-size: .875rem;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        transition: background .15s;
    }
    .ev-sort-btn:hover { background: #f9fafb; }

    /* ── Event List ── */
    .ev-list { display: flex; flex-direction: column; gap: 0; }
    .ev-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
        cursor: pointer;
        transition: background .15s, box-shadow .2s, transform .2s;
        position: relative;
    }
    .ev-row:last-child { border-bottom: none; }
    .ev-row:hover {
        background: #f5f7f5;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transform: translateY(-1px);
    }
    .ev-row.approved-row { background: #d6f0e2 !important; }
    .ev-row.rejected-row  { background: #fce3d5 !important; }
    .ev-row.approved-row:hover { background: #c8ebd9 !important; }
    .ev-row.rejected-row:hover  { background: #f8d5c0 !important; }

    /* Hover arrow indicator */
    .ev-row-arrow {
        display: flex; align-items: center; justify-content: center;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #fef9c3;
        opacity: 0;
        transform: translateX(-6px);
        transition: opacity .2s, transform .2s;
        flex-shrink: 0;
    }
    .ev-row-arrow svg { width: 14px; height: 14px; color: #a16207; }
    .ev-row:hover .ev-row-arrow { opacity: 1; transform: translateX(0); }

    /* Arrow colors by status */
    .ev-row.approved-row .ev-row-arrow { background: #dcfce7; }
    .ev-row.approved-row .ev-row-arrow svg { color: #15803d; }
    .ev-row.rejected-row .ev-row-arrow { background: #fff1e6; }
    .ev-row.rejected-row .ev-row-arrow svg { color: #c2410c; }

    /* Underline name on hover */
    .ev-row:hover .ev-name { text-decoration: underline; text-underline-offset: 2px; }

    /* Avatar */
    .ev-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        background: #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #6b7280; font-size: .9rem;
    }

    /* Info */
    .ev-info { flex: 1; min-width: 0; }
    .ev-info .ev-name { font-size: .9rem; font-weight: 700; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ev-info .ev-creator { font-size: .75rem; color: #6b7280; }

    /* Date */
    .ev-date { font-size: .8rem; color: #374151; white-space: nowrap; min-width: 120px; }

    .ev-type-icon {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
        line-height: 1;
        background: #fef9c3;
    }

    /* Type icon color by status */
    .ev-row.approved-row .ev-type-icon { background: #dcfce7; }
    .ev-row.rejected-row .ev-type-icon { background: #fff1e6; }

    /* Status label */
    .ev-status-label {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .8rem;
        font-weight: 700;
        min-width: 100px;
        padding: .3rem .7rem;
        border-radius: 999px;
    }
    .ev-status-label svg { width: 14px; height: 14px; flex-shrink: 0; }
    .ev-status-label.approved { color: #15803d; background: #dcfce7; }
    .ev-status-label.rejected { color: #c2410c; background: #fff1e6; }
    .ev-status-label.pending  { color: #a16207; background: #fef9c3; }

    /* Action buttons */
    .ev-actions { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
    .btn-approve {
        padding: .45rem 1.1rem;
        background: #4ade80;
        color: #fff;
        font-size: .8rem; font-weight: 700;
        border: none; border-radius: .6rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-approve:hover { background: #22c55e; transform: translateY(-1px); }
    .btn-reject {
        padding: .45rem 1.1rem;
        background: #fb923c;
        color: #fff;
        font-size: .8rem; font-weight: 700;
        border: none; border-radius: .6rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-reject:hover { background: #f97316; transform: translateY(-1px); }
    .btn-edit-status {
        padding: .45rem 1.2rem;
        background: #f59e0b;
        color: #fff;
        font-size: .8rem; font-weight: 700;
        border: none; border-radius: .6rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-edit-status:hover { background: #d97706; transform: translateY(-1px); }

    /* Card wrapper */
    .ev-card {
        background: #fff;
        border-radius: 1rem;
        border: 1.5px solid #e5e7eb;
        overflow: hidden;
    }

    /* Flash message */
    .ev-flash {
        padding: .75rem 1rem;
        border-radius: .75rem;
        margin-bottom: 1rem;
        font-size: .875rem;
        font-weight: 600;
    }
    .ev-flash.success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
</style>

<div>
    {{-- Page Title --}}
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:1.25rem;">Events</h1>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="ev-flash success">{{ session('success') }}</div>
    @endif

    {{-- Stat Cards --}}
    <div class="ev-stat-grid">
        {{-- Pending --}}
        <div class="ev-stat-card pending">
            <div>
                <div class="ev-stat-label">Pending Event</div>
                <div class="ev-stat-count">{{ $events->where('status', 'pending')->count() }}</div>
            </div>
            {{-- Hourglass icon --}}
            <svg class="ev-stat-icon" viewBox="0 0 64 80" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#374151" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <rect x="8" y="4" width="48" height="10" rx="2"/>
                <rect x="8" y="66" width="48" height="10" rx="2"/>
                <path d="M14 14 C14 14 14 38 32 46 C50 38 50 14 50 14"/>
                <path d="M14 66 C14 66 14 52 32 46 C50 52 50 66 50 66"/>
                <path d="M24 30 L40 30"/>
            </svg>
        </div>

        {{-- Approved --}}
        <div class="ev-stat-card approved">
            <div>
                <div class="ev-stat-label">Approved Event</div>
                <div class="ev-stat-count">{{ $events->where('status', 'accepted')->count() }}</div>
            </div>
            {{-- Rosette / verified-badge with checkmark --}}
            <svg class="ev-stat-icon" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#374151" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M40 6 L47 16 L59 13 L62 25 L74 28 L71 40 L74 52 L62 55 L59 67 L47 64 L40 74 L33 64 L21 67 L18 55 L6 52 L9 40 L6 28 L18 25 L21 13 L33 16 Z"/>
                <polyline points="27,40 36,49 53,32"/>
            </svg>
        </div>

        {{-- Rejected --}}
        <div class="ev-stat-card rejected">
            <div>
                <div class="ev-stat-label">Rejected Event</div>
                <div class="ev-stat-count">{{ $events->where('status', 'rejected')->count() }}</div>
            </div>
            {{-- Bold X mark --}}
            <svg class="ev-stat-icon" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#374151" stroke-width="8" stroke-linecap="round">
                <line x1="16" y1="16" x2="64" y2="64"/>
                <line x1="64" y1="16" x2="16" y2="64"/>
            </svg>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="ev-toolbar">
        <div class="ev-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" id="ev-search-input" placeholder="Search Specific Event" oninput="filterEvents(this.value)">
        </div>
        <button class="ev-sort-btn" onclick="sortEvents()">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M4 6h16M7 12h10M10 18h4"/>
            </svg>
            Sort
        </button>
    </div>

    {{-- Event List --}}
    <div class="ev-card">
        <div class="ev-list" id="ev-list">
            @forelse($events as $event)
                @php
                    $statusClass = match($event->status) {
                        'accepted' => 'approved-row',
                        'rejected' => 'rejected-row',
                        default => '',
                    };

                    $typeIconClass = match($event->type) {
                        'cleanup'   => 'cleanup',
                        'workshop'  => 'workshop',
                        'nature'    => 'nature',
                        'awareness' => 'awareness',
                        'transport' => 'transport',
                        default     => 'default',
                    };

                    // Avatar: use profile photo or initials fallback
                    $initials = strtoupper(substr($event->user->name ?? 'U', 0, 1));
                    $photoUrl = $event->user->profile_photo_url ?? null;

                    $formattedDate = \Carbon\Carbon::parse($event->date)->format('M d, Y');
                @endphp

                <div class="ev-row {{ $statusClass }}"
                     data-name="{{ strtolower($event->name) }} {{ strtolower($event->user->name ?? '') }}"
                     data-href="{{ route('admin.events.show', $event) }}"
                     onclick="if(!event.target.closest('form,button')){window.location=this.dataset.href;}">
                    {{-- Avatar --}}
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" class="ev-avatar" alt="{{ $event->user->name ?? 'User' }}">
                    @else
                        <div class="ev-avatar">{{ $initials }}</div>
                    @endif

                    {{-- Info --}}
                    <div class="ev-info">
                        <div class="ev-name">{{ $event->name }}</div>
                        <div class="ev-creator">Created by &#64;{{ $event->user->name ?? 'Unknown' }}</div>
                    </div>

                    {{-- Date --}}
                    <div class="ev-date">{{ $formattedDate }}</div>

                    {{-- Type Icon (emoji) --}}
                    @php
                        $typeEmoji = match($event->type) {
                            'cleanup'   => '🧹',
                            'workshop'  => '🔧',
                            'nature'    => '🌿',
                            'awareness' => '📢',
                            'transport' => '🚲',
                            default     => '📍',
                        };
                    @endphp
                    <div class="ev-type-icon {{ $typeIconClass }}" title="{{ ucfirst($event->type) }}">
                        {{ $typeEmoji }}
                    </div>

                    {{-- Status icon + label --}}
                    @if($event->status === 'accepted')
                        <div class="ev-status-label approved">
                            {{-- Check circle --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Approved
                        </div>
                    @elseif($event->status === 'rejected')
                        <div class="ev-status-label rejected">
                            {{-- X circle --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                            Rejected
                        </div>
                    @else
                        <div class="ev-status-label pending">
                            {{-- Clock --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Pending
                        </div>
                    @endif

                    {{-- Arrow indicator --}}
                    <div class="ev-row-arrow" title="View details">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>

                    {{-- Actions --}}
                    <div class="ev-actions">
                        @if($event->status === 'pending')
                            <form action="{{ route('admin.events.update', $event) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn-approve">Approve</button>
                            </form>
                            <form action="{{ route('admin.events.update', $event) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn-reject">Reject</button>
                            </form>
                        @else
                            {{-- Edit status modal trigger --}}
                            <button
                                class="btn-edit-status"
                                onclick="openEditModal({{ $event->id }}, '{{ $event->status }}')"
                            >Edit status</button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding:3rem;text-align:center;color:#6b7280;font-size:.9rem;">
                    No events found.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Status Modal --}}
<div id="edit-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;width:380px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <h2 style="font-size:1.1rem;font-weight:800;margin-bottom:1.25rem;color:#111827;">Edit Event Status</h2>
        <form id="edit-modal-form" method="POST">
            @csrf
            @method('PATCH')
            <div style="margin-bottom:1rem;">
                <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Status</label>
                <select name="status" id="edit-modal-status"
                    style="width:100%;padding:.6rem .9rem;border:1.5px solid #e5e7eb;border-radius:.6rem;font-size:.875rem;color:#374151;background:#fff;">

                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem;">
                <button type="button" onclick="closeEditModal()"
                    style="padding:.55rem 1.2rem;border:1.5px solid #e5e7eb;border-radius:.6rem;background:#fff;font-size:.85rem;font-weight:600;color:#374151;cursor:pointer;">
                    Cancel
                </button>
                <button type="submit"
                    style="padding:.55rem 1.2rem;border:none;border-radius:.6rem;background:#f59e0b;color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterEvents(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('#ev-list .ev-row').forEach(row => {
            const name = row.dataset.name || '';
            row.style.display = name.includes(q) ? '' : 'none';
        });
    }

    let sortAsc = true;
    function sortEvents() {
        const list = document.getElementById('ev-list');
        const rows = Array.from(list.querySelectorAll('.ev-row'));
        rows.sort((a, b) => {
            const na = a.querySelector('.ev-name')?.textContent.trim().toLowerCase() || '';
            const nb = b.querySelector('.ev-name')?.textContent.trim().toLowerCase() || '';
            return sortAsc ? na.localeCompare(nb) : nb.localeCompare(na);
        });
        sortAsc = !sortAsc;
        rows.forEach(r => list.appendChild(r));
    }

    function openEditModal(eventId, currentStatus) {
        const form   = document.getElementById('edit-modal-form');
        const select = document.getElementById('edit-modal-status');
        // Build the route URL
        form.action = `/admin/events/${eventId}`;
        select.value = currentStatus;
        document.getElementById('edit-modal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    // Close modal on backdrop click
    document.getElementById('edit-modal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endsection
