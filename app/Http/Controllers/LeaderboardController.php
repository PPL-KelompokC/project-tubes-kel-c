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
        $rank = 1;
        $actualRank = 1;
        $previousPoints = null;

        $allTimeUsers = User::where('role', '!=', 'admin')
            ->orderByDesc('points')
            ->orderBy('id')
            ->take(50)
            ->get()
            ->map(function ($user) use (&$rank, &$actualRank, &$previousPoints) {
                if ($previousPoints !== null && $user->points < $previousPoints) {
                    $rank = $actualRank;
                }
                $currentRank = $rank;
                $previousPoints = $user->points;
                $actualRank++;

                return [
                    'rank'                => $currentRank,
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

        $wRank = 1;
        $wActualRank = 1;
        $wPreviousPoints = null;

        $weeklyUsers = $weeklyRaw->map(function ($row) use (&$wRank, &$wActualRank, &$wPreviousPoints) {
            $user = $row->user;
            if ($wPreviousPoints !== null && $row->week_points < $wPreviousPoints) {
                $wRank = $wActualRank;
            }
            $currentRank = $wRank;
            $wPreviousPoints = $row->week_points;
            $wActualRank++;

            return [
                'rank'          => $currentRank,
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
