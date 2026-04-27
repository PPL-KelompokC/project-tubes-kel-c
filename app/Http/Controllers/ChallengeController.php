<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Challenge::query();

        // Search (title, description, category)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category Tab
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter: Difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter: Points Range
        if ($request->filled('points_min')) {
            $query->where('points', '>=', $request->points_min);
        }
        if ($request->filled('points_max')) {
            $query->where('points', '<=', $request->points_max);
        }

        // Filter: CO2 Range
        if ($request->filled('co2_min')) {
            $query->where('co2_saved', '>=', $request->co2_min);
        }
        if ($request->filled('co2_max')) {
            $query->where('co2_saved', '<=', $request->co2_max);
        }

        // Filter: Status (completed/uncompleted)
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'completed') {
                $query->whereHas('submissions', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereIn('status', ['verified', 'pending_admin'])
                      ->whereDate('created_at', today());
                });
            } elseif ($request->status === 'uncompleted') {
                $query->whereDoesntHave('submissions', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereIn('status', ['verified', 'pending_admin'])
                      ->whereDate('created_at', today());
                });
            }
        }

        // Sort
        switch ($request->sort) {
            case 'points_high':
                $query->orderBy('points', 'desc');
                break;
            case 'points_low':
                $query->orderBy('points', 'asc');
                break;
            case 'co2_high':
                $query->orderBy('co2_saved', 'desc');
                break;
            case 'co2_low':
                $query->orderBy('co2_saved', 'asc');
                break;
            case 'popular':
                $query->withCount('submissions')->orderBy('submissions_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $challenges = $query->get()->map(function ($c) use ($user) {
            // Check submission status for current user (Treat all as daily)
            $sub = ChallengeSubmission::where('user_id', $user->id)
                ->where('challenge_id', $c->id)
                ->whereIn('status', ['verified', 'pending_admin'])
                ->whereDate('created_at', today())
                ->latest()
                ->first();

            $status = $sub ? 'completed' : 'pending';

            return [
                'id' => $c->id,
                'title' => $c->title,
                'description' => $c->description,
                'category' => $c->category,
                'difficulty' => $c->difficulty,
                'points' => $c->points,
                'co2Saved' => $c->co2_saved,
                'status' => $status,
                'imageUrl' => $c->image_url,
                'participants' => $c->submissions()->distinct('user_id')->count(),
                'impact' => $c->impact ?? 'Reduced carbon footprint'
            ];
        });

        return view('challenges', compact('challenges'));
    }

    public function show(Challenge $challenge)
    {
        return response()->json($challenge->load('submissions'));
    }
}
