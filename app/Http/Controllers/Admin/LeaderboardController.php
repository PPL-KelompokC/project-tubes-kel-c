<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'alltime');
        
        // Global Stats
        $totalParticipants = User::where('role', '!=', 'admin')->count();
        $totalPoints = User::where('role', '!=', 'admin')->sum('points');
        $averagePoints = $totalParticipants > 0 ? round($totalPoints / $totalParticipants) : 0;
        $totalCO2 = User::where('role', '!=', 'admin')->sum('carbon_saved');

        // Fetch users ordered by points
        // If we want actual date-based sorting, we'd sum points_awarded from ChallengeSubmission
        // For simplicity and matching the "All time" view perfectly, we use User points.
        $users = User::where('role', '!=', 'admin')
            ->orderByDesc('points')
            ->get();

        return view('admin.leaderboard.index', compact(
            'users', 'tab', 'totalParticipants', 'totalPoints', 'averagePoints', 'totalCO2'
        ));
    }

    public function adjust(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|integer|min:0'
        ]);

        $user->points = $request->points;
        $user->save();

        return back()->with('success', 'User points updated successfully.');
    }

    public function reset(Request $request)
    {
        User::where('role', '!=', 'admin')->update([
            'points' => 0, 
            'streak' => 0,
        ]);

        return back()->with('success', 'Leaderboard points have been reset.');
    }

    public function export(Request $request)
    {
        $tab = $request->query('tab', 'alltime');

        // Same query logic as index
        $query = User::where('role', '!=', 'admin');

        if ($tab === 'weekly') {
            $query->whereHas('submissions', function($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            });
        } elseif ($tab === 'monthly') {
            $query->whereHas('submissions', function($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            });
        } elseif ($tab === 'daily') {
            $query->whereHas('submissions', function($q) {
                $q->where('created_at', '>=', now()->startOfDay());
            });
        }

        $users = $query->orderByDesc('points')->get();
        $tabLabel = ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'alltime' => 'All Time'][$tab] ?? 'All Time';
        $filename = 'leaderboard_' . $tab . '_' . now()->format('Y-m-d') . '.csv';

        // Write to a temporary file, then download it
        $tmpPath = storage_path('app/tmp_' . uniqid() . '.csv');
        $file = fopen($tmpPath, 'w');

        // Metadata rows
        fputcsv($file, ['EcoChallenge Leaderboard Export']);
        fputcsv($file, ['Period', $tabLabel]);
        fputcsv($file, ['Exported At', now()->format('Y-m-d H:i:s')]);
        fputcsv($file, []); // blank separator

        // Column headers — same as the table
        fputcsv($file, ['Rank', 'Name', 'Username', 'Points', 'CO2 Saved (kg)', 'Challenges', 'Badges', 'Trend', 'Last Active']);

        foreach ($users as $i => $user) {
            $rank = $i + 1;
            $username = '@' . strtolower(str_replace(' ', '', $user->name));

            $trend = match(true) {
                $rank === 1 => '#1',
                $rank === 2 => '#2',
                $rank === 3 => '#3',
                default     => '-',
            };

            fputcsv($file, [
                '#' . $rank,
                $user->name,
                $username,
                $user->points,
                number_format((float) $user->carbon_saved, 2),
                $user->challenges_completed,
                0,
                $trend,
                $user->last_active_date
                    ? \Carbon\Carbon::parse($user->last_active_date)->format('Y-m-d')
                    : 'Never',
            ]);
        }

        fclose($file);

        return response()->download($tmpPath, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }
}
