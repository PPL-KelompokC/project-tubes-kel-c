@extends('layouts.app')

@section('title', 'Personal Stats - TerraVerde')

@section('content')
<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-6">
    <!-- Overview stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Points -->
        <div class="bg-[#fffdf0] border border-[#fef08a]/80 rounded-2xl p-5 shadow-sm transition-all hover:shadow-md hover:translate-y-[-2px] duration-300">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ number_format($currentUserStats['totalPoints']) }}</p>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-0.5">Total Points</p>
            <p class="text-[11px] mt-2 font-bold text-green-600 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                +{{ number_format($currentUserStats['pointsThisMonth']) }} this month
            </p>
        </div>

        <!-- Card 2: CO2 Saved -->
        <div class="bg-[#f7fee7] border border-[#d9f99d]/80 rounded-2xl p-5 shadow-sm transition-all hover:shadow-md hover:translate-y-[-2px] duration-300">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-lime-100 flex items-center justify-center text-lime-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-leaf"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $currentUserStats['carbonSaved'] }}kg</p>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-0.5">CO₂ Saved</p>
            <p class="text-[11px] mt-2 font-bold text-green-600 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                +{{ $currentUserStats['co2ThisMonth'] }}kg this month
            </p>
        </div>

        <!-- Card 3: Challenges -->
        <div class="bg-[#f0fdf4] border border-[#bbf7d0]/80 rounded-2xl p-5 shadow-sm transition-all hover:shadow-md hover:translate-y-[-2px] duration-300">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks"><path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/><path d="m3 12 2 2 4-4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $currentUserStats['challengesCompleted'] }}</p>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-0.5">Challenges</p>
            <p class="text-[11px] mt-2 font-bold text-green-600 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                +{{ $currentUserStats['challengesThisMonth'] }} this month
            </p>
        </div>

        <!-- Card 4: Current Streak -->
        <div class="bg-[#fff7ed] border border-[#ffedd5]/80 rounded-2xl p-5 shadow-sm transition-all hover:shadow-md hover:translate-y-[-2px] duration-300">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $currentUserStats['streak'] }}d</p>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-0.5">Current Streak</p>
            <p class="text-[11px] mt-2 font-bold text-gray-500 flex items-center gap-1">
                Personal best: {{ $currentUserStats['longestStreak'] }}d
            </p>
        </div>
    </div>

    <!-- Charts row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Points over time -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col">
            <h3 class="text-sm font-extrabold text-gray-900 mb-4">Points Earned — Weekly</h3>
            <div class="h-60 relative flex-1">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Category balance -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col">
            <h3 class="text-sm font-extrabold text-gray-900 mb-4">Challenge Category Balance</h3>
            <div class="h-60 relative flex-1">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- CO2 trend + Streak calendar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- CO2 monthly trend -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col">
            <h3 class="text-sm font-extrabold text-gray-900 mb-4">CO₂ Savings — Monthly Trend</h3>
            <div class="h-60 relative flex-1">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Streak calendar -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="text-orange-500"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                <h3 class="text-sm font-extrabold text-gray-900">{{ $currentUserStats['streak'] }}-Day Streak</h3>
            </div>
            
            <div class="grid grid-cols-7 gap-2 flex-1 items-center">
                @foreach(['M','T','W','T','F','S','S'] as $d)
                    <div class="text-[10px] text-center text-gray-400 font-bold uppercase tracking-wider">{{ $d }}</div>
                @endforeach
                @foreach($streakCalendar as $day)
                    @php
                        $dayClass = 'bg-gray-50 text-gray-300 border border-gray-100';
                        if ($day['status'] === 'completed') {
                            $dayClass = 'bg-green-500 text-white font-bold border-green-500 shadow-sm shadow-green-100';
                        } elseif ($day['status'] === 'today') {
                            $dayClass = 'bg-orange-500 text-white font-bold border-orange-500 shadow-sm ring-4 ring-orange-100';
                        } elseif ($day['status'] === 'uncompleted') {
                            $dayClass = 'bg-gray-100 text-gray-400 border-gray-200';
                        }
                    @endphp
                    <div
                        class="aspect-square rounded-xl flex items-center justify-center text-xs transition-all {{ $dayClass }}"
                        title="{{ $day['date'] }}"
                    >
                        {{ $day['day'] }}
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-4 text-[10px] font-bold text-gray-500">
                <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded bg-green-500 shadow-sm shadow-green-100"></div><span>Done</span></div>
                <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded bg-orange-500 shadow-sm"></div><span>Today</span></div>
                <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded bg-gray-100 border border-gray-200"></div><span>Future</span></div>
            </div>
        </div>
    </div>

    <!-- Challenge History -->
    @if($challengeHistory->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-extrabold text-gray-900 mb-5">Challenge History</h3>
            <div class="space-y-4 relative">
                <div class="absolute left-5 top-2 bottom-2 w-0.5 bg-gray-100"></div>
                @foreach($challengeHistory as $ch)
                    @php
                        $catColors = [
                            'transport' => 'bg-blue-500 border-blue-500 text-blue-500',
                            'food' => 'bg-green-500 border-green-500 text-green-500',
                            'waste' => 'bg-orange-500 border-orange-500 text-orange-500',
                            'energy' => 'bg-yellow-500 border-yellow-500 text-yellow-500',
                            'water' => 'bg-cyan-500 border-cyan-500 text-cyan-500',
                            'nature' => 'bg-emerald-500 border-emerald-500 text-emerald-500',
                        ];
                        $colorClass = $catColors[$ch['category']] ?? 'bg-green-500 border-green-500 text-green-500';
                    @endphp
                    <div class="flex items-start gap-4 pl-12 relative">
                        <div class="absolute left-[17px] top-3.5 w-2 h-2 rounded-full ring-4 ring-white {{ explode(' ', $colorClass)[0] }}"></div>
                        <div class="flex-1 bg-gray-50 rounded-2xl p-4 flex items-center justify-between gap-4 transition-all hover:bg-gray-100/70 duration-200">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $ch['title'] }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ $ch['category'] }}</span>
                                    <span class="text-[10px] text-gray-300">·</span>
                                    <span class="text-[10px] font-medium text-gray-400">{{ $ch['date'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 flex-shrink-0 text-right">
                                <div>
                                    <p class="text-sm font-black text-green-600">+{{ $ch['points'] }} pts</p>
                                    <p class="text-[10px] font-bold text-gray-400">{{ $ch['co2'] }}kg CO₂</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. Weekly Points Bar Chart ---
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyData = {!! json_encode($weeklyData) !!};
        
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyData.map(item => item.day),
                datasets: [{
                    label: 'Points Earned',
                    data: weeklyData.map(item => item.points),
                    backgroundColor: '#22c55e',
                    hoverBackgroundColor: '#16a34a',
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { family: 'Inter', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.raw} points`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 10, weight: '600' }, color: '#9ca3af' }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#f3f4f6' },
                        ticks: { 
                            font: { family: 'Inter', size: 10, weight: '600' }, 
                            color: '#9ca3af',
                            stepSize: 35
                        },
                        suggestedMax: 140
                    }
                }
            }
        });

        // --- 2. Challenge Category Balance Radar Chart ---
        const radarCtx = document.getElementById('radarChart').getContext('2d');
        const radarData = {!! json_encode($radarData) !!};

        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: radarData.map(item => item.subject),
                datasets: [{
                    data: radarData.map(item => item.value),
                    backgroundColor: 'rgba(34, 197, 94, 0.12)',
                    borderColor: '#22c55e',
                    borderWidth: 2,
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#22c55e',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { family: 'Inter', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const count = radarData[index].count;
                                return ` ${context.raw}% (${count} completed)`;
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        angleLines: { color: '#f3f4f6' },
                        grid: { color: '#f3f4f6' },
                        pointLabels: { 
                            font: { family: 'Inter', size: 10, weight: 'bold' }, 
                            color: '#4b5563' 
                        },
                        ticks: { display: false },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        });

        // --- 3. CO2 Savings Monthly Trend Line Chart ---
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = {!! json_encode($monthlyData) !!};

        const gradient = monthlyCtx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.22)');
        gradient.addColorStop(1, 'rgba(34, 197, 94, 0.01)');

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    data: monthlyData.map(item => item.co2),
                    borderColor: '#22c55e',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#22c55e',
                    pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { family: 'Inter', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.raw} kg CO₂ saved`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 10, weight: '600' }, color: '#9ca3af' }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { family: 'Inter', size: 10, weight: '600' }, color: '#9ca3af' }
                    }
                }
            }
        });
    });
</script>
@endpush
