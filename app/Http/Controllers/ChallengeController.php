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
        if ($request->filled('points_min') && $request->filled('points_max')) {
            if ($request->points_min > $request->points_max) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['points_min' => 'Min points cannot be greater than max points.']);
            }
        }

        // Filter: CO2 Range
        if ($request->filled('co2_min') && $request->filled('co2_max')) {
            if ($request->co2_min > $request->co2_max) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['co2_min' => 'Min CO2 cannot be greater than max CO2.']);
            }
        }

        // Filter: Points Range Apply
        if ($request->filled('points_min')) {
            $query->where('points', '>=', $request->points_min);
        }
        if ($request->filled('points_max')) {
            $query->where('points', '<=', $request->points_max);
        }

        // Filter: CO2 Range Apply
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
                      ->where('status', 'verified')
                      ->whereDate('created_at', today());
                });
            } elseif ($request->status === 'uncompleted') {
                $query->whereDoesntHave('submissions', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('status', 'verified')
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
                ->whereDate('created_at', today())
                ->latest()
                ->first();

            $status = 'pending';
            if ($sub) {
                if ($sub->status === 'verified') {
                    $status = 'completed';
                } elseif ($sub->status === 'pending_admin') {
                    $status = 'on_verify';
                } elseif ($sub->status === 'rejected') {
                    $status = 'rejected';
                }
            }

            return [
                'id' => $c->id,
                'title' => $c->title,
                'description' => $c->description,
                'category' => $c->category,
                'difficulty' => $c->difficulty,
                'points' => $c->points,
                'co2Saved' => $c->co2_saved,
                'status' => $status,
                'submission' => $sub,
                'imageUrl' => $c->image_url,
                'participants' => $c->submissions()->distinct('user_id')->count(),
                'impact' => $c->impact ?? 'Reduced carbon footprint'
            ];
        });

        return view('challenges', compact('challenges'));
    }

    public function quickComplete(Challenge $challenge)
    {
        $user = Auth::user();
        
        $existing = \App\Models\ChallengeSubmission::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->whereDate('created_at', today())
            ->first();

        if (!$existing) {
            \App\Models\ChallengeSubmission::create([
                'user_id'        => $user->id,
                'challenge_id'   => $challenge->id,
                'photo_path'     => 'quick_complete',
                'status'         => 'verified',
                'points_awarded' => $challenge->points,
                'verified_at'    => now(),
            ]);

            $user->increment('points', $challenge->points);
            $user->increment('carbon_saved', $challenge->co2_saved);
            $user->increment('challenges_completed');
            $user->updateStreak();
        }

        return back();
    }

    public function show(Challenge $challenge)
    {
        return response()->json($challenge->load('submissions'));
    }
}
