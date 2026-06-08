@extends('admin.layouts.app')

@section('title', 'Leaderboard Control - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Leaderboard Control</h1>
            <p class="text-sm text-slate-500 mt-1">Monitor and manage user rankings, points, and achievements across the platform.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.leaderboard.export', ['tab' => $tab]) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                Export
            </a>
            <form action="{{ route('admin.leaderboard.reset') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset all leaderboard points to 0?');">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-rose-500 text-white rounded-lg text-sm font-semibold hover:bg-rose-600 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset Leaderboard
                </button>
            </form>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <span class="text-lg">🏆</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Total Participants</span>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($totalParticipants) }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Points</span>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($totalPoints) }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider">Average Points</span>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($averagePoints) }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider">Total CO₂ Saved</span>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($totalCO2, 1) }} kg</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <h2 class="text-base font-bold text-slate-800">Rankings</h2>
            
            <div class="flex items-center bg-slate-50 rounded-lg p-1 border border-slate-100">
                <a href="{{ route('admin.leaderboard.index', ['tab' => 'daily']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $tab == 'daily' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Daily</a>
                <a href="{{ route('admin.leaderboard.index', ['tab' => 'weekly']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $tab == 'weekly' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Weekly</a>
                <a href="{{ route('admin.leaderboard.index', ['tab' => 'monthly']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $tab == 'monthly' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Monthly</a>
                <a href="{{ route('admin.leaderboard.index', ['tab' => 'alltime']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $tab == 'alltime' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">All time</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/50 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Rank</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4 text-center">Points</th>
                        <th class="px-6 py-4 text-center">CO₂ Saved</th>
                        <th class="px-6 py-4 text-center">Challenges</th>
                        <th class="px-6 py-4 text-center">Badges</th>
                        <th class="px-6 py-4 text-center">Trend</th>
                        <th class="px-6 py-4 text-center">Last Active</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $index => $user)
                        @php 
                            $rank = $index + 1; 
                            $username = strtolower(str_replace(' ', '', $user->name));
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 font-bold text-slate-800">
                                    @if($rank === 1)
                                        <span class="text-xl">👑</span> #1
                                    @elseif($rank === 2)
                                        <span class="text-xl">🥈</span> #2
                                    @elseif($rank === 3)
                                        <span class="text-xl">🥉</span> #3
                                    @else
                                        <span class="text-slate-400 ml-7">#{{ $rank }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-500 uppercase">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                        <p class="text-[10px] text-slate-400">@ {{ $username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-800">{{ number_format($user->points) }}</td>
                            <td class="px-6 py-4 text-center">{{ number_format($user->carbon_saved, 1) }} kg</td>
                            <td class="px-6 py-4 text-center">{{ number_format($user->challenges_completed) }}</td>
                            <td class="px-6 py-4 text-center">0</td> <!-- Badge functionality not yet implemented in relations -->
                            <td class="px-6 py-4 text-center">
                                <span class="text-slate-300">&mdash;</span>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-slate-400">
                                {{ $user->last_active_date ? \Carbon\Carbon::parse($user->last_active_date)->diffForHumans() : 'Never' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openAdjustModal({{ $user->id }}, '{{ $user->name }}', {{ $user->points }})" class="text-emerald-600 font-bold hover:text-emerald-700 transition-colors text-xs">
                                    Adjust Points
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @if($users->isEmpty())
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">No users found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Adjust Points Modal -->
<div id="adjustModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdjustModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <form id="adjustForm" method="POST" action="">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-1">Adjust Points</h3>
                <p class="text-xs text-slate-500 mb-6" id="adjustModalDesc">Set the total points for user.</p>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total Points</label>
                    <input type="number" name="points" id="adjustPointsInput" min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 font-medium">
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="closeAdjustModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition-colors shadow-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAdjustModal(userId, userName, currentPoints) {
        const modal = document.getElementById('adjustModal');
        const form = document.getElementById('adjustForm');
        const desc = document.getElementById('adjustModalDesc');
        const input = document.getElementById('adjustPointsInput');
        
        form.action = `/admin/leaderboard/${userId}/adjust`;
        desc.textContent = `Set the total points for ${userName}.`;
        input.value = currentPoints;
        
        modal.classList.remove('hidden');
    }

    function closeAdjustModal() {
        document.getElementById('adjustModal').classList.add('hidden');
    }
</script>
@endsection
