<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        // ── All-time leaderboard ──────────────────────────────────
        $allTimeUsers = User::where('role', '!=', 'admin')
            ->orderByDesc('points')
            ->take(50)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank'                => $index + 1,
                    'name'                => $user->name,
                    'avatar'              => $user->avatar,
                    'points'              => $user->points,
                    'carbonSaved'         => $user->carbon_saved,
                    'challengesCompleted' => $user->challenges_completed,
                    'streak'              => $user->streak,
                    'location'            => $user->location ?? 'Unknown',
                    'isCurrentUser'       => $user->id === auth()->id(),
                    'id'                  => $user->id,
                ];
            });

        // ── Weekly leaderboard (points from verified submissions in last 7 days) ──
        $weekStart = now()->subDays(7);
        $weeklyRaw = ChallengeSubmission::select('user_id', DB::raw('SUM(points_awarded) as week_points'))
            ->where('status', 'verified')
            ->where('verified_at', '>=', $weekStart)
            ->groupBy('user_id')
            ->orderByDesc('week_points')
            ->with('user')
            ->take(50)
            ->get();

        $weeklyUsers = $weeklyRaw->map(function ($row, $index) {
            $user = $row->user;
            return [
                'rank'          => $index + 1,
                'name'          => $user->name,
                'avatar'        => $user->avatar,
                'points'        => $row->week_points,
                'carbonSaved'   => $user->carbon_saved,
                'streak'        => $user->streak,
                'location'      => $user->location ?? 'Unknown',
                'isCurrentUser' => $user->id === auth()->id(),
                'id'            => $user->id,
            ];
        });

        // ── Current user rank ─────────────────────────────────────
        $currentUser     = auth()->user();
        $myRankAllTime   = User::where('role', '!=', 'admin')->where('points', '>', $currentUser->points)->count() + 1;
        $myWeekPoints    = ChallengeSubmission::where('user_id', $currentUser->id)
            ->where('status', 'verified')
            ->where('verified_at', '>=', $weekStart)
            ->sum('points_awarded');

        return view('leaderboard', compact(
            'allTimeUsers',
            'weeklyUsers',
            'myRankAllTime',
            'myWeekPoints',
            'currentUser',
        ));
    }
}
