<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Today's daily challenges ──────────────────────────────
        $todaysChallengesQ = Challenge::today()->get();

        // Fallback: if no daily challenges set, show up to 3 latest
        if ($todaysChallengesQ->isEmpty()) {
            $todaysChallengesQ = Challenge::latest()->take(3)->get();
        }

        $todaysChallenges = $todaysChallengesQ->map(function (Challenge $c) use ($user) {
            // Get this user's submission for today
            $submission = ChallengeSubmission::where('user_id', $user->id)
                ->where('challenge_id', $c->id)
                ->whereDate('created_at', today())
                ->first();

            return [
                'id'          => $c->id,
                'title'       => $c->title,
                'description' => $c->description,
                'category'    => $c->category,
                'difficulty'  => $c->difficulty,
                'points'      => $c->points,
                'co2Saved'    => $c->co2_saved,
                'imageUrl'    => $c->image_url,
                'participants'=> $c->participantCount(),
                // Submission state for this user
                'submission'  => $submission,
                'submitted'   => (bool) $submission,
                'status'      => $submission?->status ?? 'not_started',
            ];
        });

        $completedToday = $todaysChallenges->filter(
            fn($c) => in_array($c['status'], ['pending_community', 'verified'])
        )->count();

        $progressPct = count($todaysChallenges) > 0
            ? round(($completedToday / count($todaysChallenges)) * 100)
            : 0;

        // ── Sidebar stats ──────────────────────────────────────────
        $rank     = User::where('role', '!=', 'admin')->where('points', '>', $user->points)->count() + 1;
        $topUsers = User::where('role', '!=', 'admin')->orderByDesc('points')->take(5)->get();

        // ── Weekly chart data ──────────────────────────────────────
        $weeklyData  = [];
        $days        = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $startOfWeek = now()->startOfWeek();

        foreach ($days as $i => $dayName) {
            $date     = $startOfWeek->copy()->addDays($i);
            $activity = $user->activities()->whereDate('activity_date', $date)->first();

            $weeklyData[] = [
                'day'    => $dayName,
                'co2'    => $activity ? (float) $activity->co2_saved : 0,
                'points' => $activity ? (int) $activity->points_earned : 0,
            ];
        }

        // ── Recent verified submissions for bottom feed ────────────
        $recentSubmissions = ChallengeSubmission::with(['user', 'challenge'])
            ->where('status', 'verified')
            ->latest('verified_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'todaysChallenges',
            'completedToday',
            'progressPct',
            'topUsers',
            'weeklyData',
            'rank',
            'recentSubmissions',
        ));
    }
}
