<?php

namespace App\Http\Controllers;

use App\Models\ChallengeSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── 1. Overview Cards ──────────────────────────────────────────
        $totalPoints = $user->points;
        $pointsThisMonth = ChallengeSubmission::where('user_id', $user->id)
            ->where('status', 'verified')
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->sum('points_awarded');

        $carbonSaved = $user->carbon_saved;
        $co2ThisMonth = ChallengeSubmission::where('challenge_submissions.user_id', $user->id)
            ->where('challenge_submissions.status', 'verified')
            ->whereMonth('challenge_submissions.verified_at', now()->month)
            ->whereYear('challenge_submissions.verified_at', now()->year)
            ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
            ->sum('challenges.co2_saved');

        $challengesCompleted = $user->challenges_completed;
        $challengesThisMonth = ChallengeSubmission::where('user_id', $user->id)
            ->where('status', 'verified')
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->count();

        $streak = $user->streak;
        $longestStreak = $user->longest_streak ?: max($streak, 31); // Fallback to 31 if not set

        $currentUserStats = [
            'totalPoints' => $totalPoints,
            'pointsThisMonth' => $pointsThisMonth,
            'carbonSaved' => round($carbonSaved, 1),
            'co2ThisMonth' => round($co2ThisMonth, 1),
            'challengesCompleted' => $challengesCompleted,
            'challengesThisMonth' => $challengesThisMonth,
            'streak' => $streak,
            'longestStreak' => $longestStreak,
        ];

        // ── 2. Weekly Data (Mon - Sun) ──────────────────────────────────
        $weeklyData = [];
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $startOfWeek = now()->startOfWeek();

        foreach ($days as $i => $dayName) {
            $date = $startOfWeek->copy()->addDays($i);

            $points = ChallengeSubmission::where('user_id', $user->id)
                ->where('status', 'verified')
                ->whereDate('verified_at', $date)
                ->sum('points_awarded');

            $weeklyData[] = [
                'day' => $dayName,
                'points' => (int) $points,
            ];
        }

        // ── 3. Category Balance (Radar Chart) ───────────────────────────
        $categoryCounts = ChallengeSubmission::where('challenge_submissions.user_id', $user->id)
            ->where('challenge_submissions.status', 'verified')
            ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
            ->selectRaw('LOWER(challenges.category) as cat, count(*) as count')
            ->groupBy('challenges.category')
            ->pluck('count', 'cat')
            ->toArray();

        $subjects = ['Nature', 'Food', 'Waste', 'Energy', 'Water', 'Transport'];
        $radarData = [];
        $totalCompleted = array_sum($categoryCounts);

        foreach ($subjects as $subject) {
            $lower = strtolower($subject);
            $count = $categoryCounts[$lower] ?? 0;
            // Balance is the percentage of total completed challenges that are in this category.
            // If they haven't completed any challenges, we show 0%.
            $radarData[] = [
                'subject' => $subject,
                'value' => $totalCompleted > 0 ? round(($count / $totalCompleted) * 100) : 0,
                'count' => $count,
            ];
        }

        // ── 4. CO2 Savings Monthly Trend (Last 6 Months) ───────────────
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthName = $monthDate->format('M');

            $co2 = ChallengeSubmission::where('challenge_submissions.user_id', $user->id)
                ->where('challenge_submissions.status', 'verified')
                ->whereMonth('challenge_submissions.verified_at', $monthDate->month)
                ->whereYear('challenge_submissions.verified_at', $monthDate->year)
                ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
                ->sum('challenges.co2_saved');

            $monthlyData[] = [
                'month' => $monthName,
                'co2' => round((float) $co2, 1),
            ];
        }

        // ── 5. 35-Day Streak Calendar Grid ──────────────────────────────
        // A 35-day grid starting 4 weeks before the start of the current week (to align perfectly to Mon-Sun rows)
        $startDate = now()->startOfWeek()->subWeeks(4);
        $streakCalendar = [];
        
        $verifiedDates = ChallengeSubmission::where('user_id', $user->id)
            ->where('status', 'verified')
            ->whereDate('verified_at', '>=', $startDate)
            ->pluck('verified_at')
            ->map(fn($date) => $date->toDateString())
            ->unique()
            ->toArray();

        for ($i = 0; $i < 35; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->toDateString();

            if (in_array($dateString, $verifiedDates)) {
                $status = 'completed';
            } elseif ($date->isToday()) {
                $status = 'today';
            } elseif ($date->isFuture()) {
                $status = 'future';
            } else {
                $status = 'uncompleted';
            }

            $streakCalendar[] = [
                'day' => $date->day,
                'date' => $dateString,
                'status' => $status,
            ];
        }

        // ── 6. Challenge History Timeline ───────────────────────────────
        $challengeHistory = ChallengeSubmission::with('challenge')
            ->where('user_id', $user->id)
            ->where('status', 'verified')
            ->latest('verified_at')
            ->take(5)
            ->get()
            ->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'title' => $sub->challenge->title,
                    'date' => $sub->verified_at ? $sub->verified_at->format('M d') : $sub->created_at->format('M d'),
                    'points' => $sub->points_awarded,
                    'co2' => $sub->challenge->co2_saved,
                    'category' => strtolower($sub->challenge->category),
                ];
            });

        return view('stats', compact(
            'currentUserStats',
            'weeklyData',
            'radarData',
            'monthlyData',
            'streakCalendar',
            'challengeHistory'
        ));
    }
}
