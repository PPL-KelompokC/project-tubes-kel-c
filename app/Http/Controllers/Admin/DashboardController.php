<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_challenges' => Challenge::count(),
            'total_carbon_saved' => User::sum('carbon_saved'),
            'recent_submissions' => ChallengeSubmission::with(['user', 'challenge'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
