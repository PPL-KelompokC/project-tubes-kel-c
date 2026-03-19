@extends('admin.layouts.app')

@section('title', 'Overview')
@section('page_title', 'Platform Overview')
@section('page_subtitle', 'Monitor real-time statistics and user engagement across Siklim.')

@section('content')
<div class="space-y-10">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow group hover:border-emerald-200 transition-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+12%</span>
            </div>
            <p class="text-sm font-medium text-slate-500">Total Active Users</p>
            <h4 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_users']) }}</h4>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow group hover:border-blue-200 transition-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">New</span>
            </div>
            <p class="text-sm font-medium text-slate-500">Active Challenges</p>
            <h4 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_challenges']) }}</h4>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow group hover:border-emerald-200 transition-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Global</span>
            </div>
            <p class="text-sm font-medium text-slate-500">CO₂ Saved (kg)</p>
            <h4 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats['total_carbon_saved'], 1) }}</h4>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow group hover:border-orange-200 transition-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Top 5%</span>
            </div>
            <p class="text-sm font-medium text-slate-500">Daily Engagement</p>
            <h4 class="text-2xl font-black text-slate-900 mt-1">84.2%</h4>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-slate-200 card-shadow">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Carbon Impact Trend</h3>
                    <p class="text-xs text-slate-500">Total CO₂ reduction over the last 30 days.</p>
                </div>
                <select class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-none">
                    <option>Last 30 days</option>
                    <option>Last 7 days</option>
                </select>
            </div>
            <div class="h-[300px] w-full">
                <canvas id="carbonChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white rounded-3xl border border-slate-200 card-shadow flex flex-col">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Live Activity</h3>
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
            </div>
            <div class="flex-1 overflow-y-auto p-2">
                <div class="space-y-1">
                    @forelse($stats['recent_activities'] as $activity)
                        <div class="p-4 rounded-2xl hover:bg-slate-50 transition-200 group">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 group-hover:bg-white transition-200">
                                    {{ substr($activity->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ $activity->user->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Completed a challenge
                                    </p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">-{{ $activity->co2_saved }}kg CO₂</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-sm text-slate-400 italic">Waiting for new activity...</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-6 bg-slate-50/50 rounded-b-3xl border-t border-slate-100">
                <button class="w-full py-2.5 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-200 shadow-sm">
                    View Activity Log
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('carbonChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Feb 20', 'Feb 25', 'Mar 1', 'Mar 5', 'Mar 10', 'Mar 15', 'Mar 18'],
            datasets: [{
                label: 'CO2 Saved (kg)',
                data: [450, 520, 480, 610, 590, 720, 840],
                borderColor: '#10b981',
                borderWidth: 3,
                backgroundColor: (context) => {
                    const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#10b981',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 12 },
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: { font: { size: 11 }, color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endsection
