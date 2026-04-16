<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $challenges = Challenge::all()->map(function($c) use ($user) {
            $userChallenge = $user->challenges()->where('challenge_id', $c->id)->first();
            // Also check for pending_admin/verified in submissions (if user_challenges isn't fully synced)
            $sub = \App\Models\ChallengeSubmission::where('user_id', $user->id)
                ->where('challenge_id', $c->id)->latest()->first();
            
            $status = 'pending';
            if ($userChallenge && $userChallenge->pivot->status === 'completed') {
                $status = 'completed';
            } elseif ($sub && in_array($sub->status, ['verified', 'pending_admin'])) {
                $status = 'completed';
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
                'imageUrl' => $c->image_url,
                'participants' => rand(100, 2000), // Mock data
                'impact' => 'Reduced carbon footprint'
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
}
