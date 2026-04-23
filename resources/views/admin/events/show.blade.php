@extends('admin.layouts.app')

@section('content')
@php
    $typeColors = [
        'cleanup'   => ['bg' => '#d1fae5', 'color' => '#059669'],
        'workshop'  => ['bg' => '#dbeafe', 'color' => '#2563eb'],
        'nature'    => ['bg' => '#dcfce7', 'color' => '#16a34a'],
        'awareness' => ['bg' => '#fef9c3', 'color' => '#ca8a04'],
        'transport' => ['bg' => '#ede9fe', 'color' => '#7c3aed'],
    ];
    $tc = $typeColors[$event->type] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280'];

    $initials = strtoupper(substr($event->user->name ?? 'U', 0, 1));
    $photoUrl  = $event->user->profile_photo_url ?? null;

    $dateObj       = \Carbon\Carbon::parse($event->date);
    $formattedDate = $dateObj->format('d F Y');
    $formattedTime = $dateObj->format('H.i') . ' ' . ($dateObj->format('A') === 'AM' ? 'A.M' : 'P.M');
    $createdAt     = $event->created_at
                        ? \Carbon\Carbon::parse($event->created_at)->format('d F H.i A')
                        : '—';
@endphp

<style>
    /* ── Back link ── */
    .evd-back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .875rem;
        color: #374151;
        text-decoration: none;
        margin-bottom: 1.5rem;
        font-weight: 500;
        transition: color .15s;
    }
    .evd-back:hover { color: #111827; }

    /* ── Two-column grid ── */
    .evd-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* ── Left panel ── */
    .evd-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 .6rem;
    }

    .evd-meta-row {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .82rem;
        color: #374151;
        margin-bottom: .25rem;
    }
    .evd-meta-row .evd-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        object-fit: cover;
        background: #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 700;
        color: #6b7280;
        flex-shrink: 0;
    }

    /* Type badge */
    .evd-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .3rem .85rem;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 700;
        margin-top: .85rem;
        margin-bottom: 1.5rem;
    }
    .evd-badge svg { width: 14px; height: 14px; }

    /* Section headings */
    .evd-section-title {
        font-size: .95rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 .45rem;
    }
    .evd-section-value {
        font-size: .85rem;
        color: #374151;
        margin: 0 0 .2rem;
        line-height: 1.6;
    }
    .evd-section-gap { margin-bottom: 1.4rem; }

    /* ── Right panel ── */
    .evd-right { display: flex; flex-direction: column; gap: 1rem; }

    .evd-location-section { }
    .evd-location-values {
        font-size: .85rem;
        color: #374151;
        line-height: 1.8;
    }

    .evd-map-wrapper {
        width: 100%;
        aspect-ratio: 4/3;
        border-radius: .75rem;
        overflow: hidden;
        border: 1.5px solid #e5e7eb;
    }
    .evd-map-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }

    /* ── Bottom action bar ── */
    .evd-action-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2.5rem;
    }
    .btn-evd-approve {
        padding: .6rem 2rem;
        background: #4ade80;
        color: #fff;
        font-size: .9rem; font-weight: 700;
        border: none; border-radius: .65rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-evd-approve:hover { background: #22c55e; transform: translateY(-1px); }
    .btn-evd-reject {
        padding: .6rem 2rem;
        background: #fb923c;
        color: #fff;
        font-size: .9rem; font-weight: 700;
        border: none; border-radius: .65rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-evd-reject:hover { background: #f97316; transform: translateY(-1px); }
    .btn-evd-edit {
        padding: .6rem 2rem;
        background: #f59e0b;
        color: #fff;
        font-size: .9rem; font-weight: 700;
        border: none; border-radius: .65rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-evd-edit:hover { background: #d97706; transform: translateY(-1px); }
</style>

<div>
    {{-- Back button --}}
    <a href="{{ route('admin.events.index') }}" class="evd-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Back
    </a>

    {{-- Flash --}}
    @if(session('success'))
        <div style="padding:.65rem 1rem;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;border-radius:.65rem;font-size:.875rem;font-weight:600;margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Two-column layout --}}
    <div class="evd-grid">

        {{-- ── LEFT: Event detail ── --}}
        <div>
            {{-- Title --}}
            <h1 class="evd-title">{{ $event->name }}</h1>

            {{-- Created by --}}
            <div class="evd-meta-row">
                <span style="font-weight:600;">Created By</span>
                <span>&#64;{{ $event->user->name ?? 'Unknown' }}</span>
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" class="evd-avatar" alt="{{ $event->user->name }}">
                @else
                    <span class="evd-avatar">{{ $initials }}</span>
                @endif
            </div>

            {{-- Created at --}}
            <div class="evd-meta-row">
                <span style="font-weight:600;">Created at:</span>
                <span>{{ $createdAt }}</span>
            </div>

            {{-- Type badge --}}
            <div class="evd-badge" style="background:{{ $tc['bg'] }};color:{{ $tc['color'] }};">
                @switch($event->type)
                    @case('cleanup')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 4 13c0-5 7-11 7-11s7 6 7 11a7 7 0 0 1-7 7z"/>
                            <path d="M11 20v-9"/>
                        </svg>
                        @break
                    @case('workshop')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/>
                            <polyline points="23 20 23 14 17 14"/>
                            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
                        </svg>
                        @break
                    @case('nature')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71
                                     3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        @break
                    @case('awareness')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        @break
                    @case('transport')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="5.5" cy="17.5" r="3.5"/>
                            <circle cx="18.5" cy="17.5" r="3.5"/>
                            <path d="M15 6a1 1 0 0 0-1-1H9"/>
                            <path d="M12 6l1 7h5.5"/>
                            <path d="M5.5 17.5 9 10l3 3"/>
                        </svg>
                        @break
                    @default
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                @endswitch
                {{ ucfirst($event->type) }}
            </div>

            {{-- Event Date --}}
            <div class="evd-section-gap">
                <div class="evd-section-title">Event Date</div>
                <div class="evd-section-value">Date: {{ $formattedDate }}</div>
                <div class="evd-section-value">Time: {{ $formattedTime }}</div>
            </div>

            {{-- Event Description --}}
            <div class="evd-section-gap">
                <div class="evd-section-title">Event Description</div>
                <div class="evd-section-value" style="white-space:pre-line;">
                    {{ $event->description ?? '—' }}
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Location + Map ── --}}
        <div class="evd-right">

            {{-- Location info --}}
            <div class="evd-location-section">
                <div class="evd-section-title">Event Location</div>
                <div class="evd-location-values">
                    <div>{{ $event->place ?? '—' }}</div>
                    <div>{{ $event->city  ?? '—' }}</div>
                    <div>{{ $event->state ?? '—' }}</div>
                    <div>{{ $event->x }}, {{ $event->y }}</div>
                </div>
            </div>

            {{-- OpenStreetMap embed centered on event coordinates --}}
            <div class="evd-map-wrapper">
                @php
                    $lat    = $event->x;
                    $lon    = $event->y;
                    $zoom   = 14;
                    $bbox_d = 0.05;
                    $left   = $lon - $bbox_d;
                    $bottom = $lat - $bbox_d;
                    $right  = $lon + $bbox_d;
                    $top    = $lat + $bbox_d;
                    $mapUrl = "https://www.openstreetmap.org/export/embed.html"
                            . "?bbox={$left},{$bottom},{$right},{$top}"
                            . "&layer=mapnik"
                            . "&marker={$lat},{$lon}";
                @endphp
                <iframe
                    src="{{ $mapUrl }}"
                    title="Event location map"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    </div>

    {{-- ── Bottom action bar ── --}}
    <div class="evd-action-bar">
        @if($event->status === 'pending')
            <form action="{{ route('admin.events.update', $event) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="accepted">
                <button type="submit" class="btn-evd-approve">Approve</button>
            </form>
            <form action="{{ route('admin.events.update', $event) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn-evd-reject">Reject</button>
            </form>
        @else
            <button class="btn-evd-edit" onclick="openEditModal({{ $event->id }}, '{{ $event->status }}')">
                Edit status
            </button>
        @endif
    </div>
</div>

{{-- Edit Status Modal (reused from index style) --}}
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
    function openEditModal(eventId, currentStatus) {
        const form   = document.getElementById('edit-modal-form');
        const select = document.getElementById('edit-modal-status');
        form.action  = `/admin/events/${eventId}`;
        select.value = currentStatus;
        document.getElementById('edit-modal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }
    document.getElementById('edit-modal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endsection
