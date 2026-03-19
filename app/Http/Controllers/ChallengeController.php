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
            return [
                'id' => $c->id,
                'title' => $c->title,
                'description' => $c->description,
                'category' => $c->category,
                'difficulty' => $c->difficulty,
                'points' => $c->points,
                'co2Saved' => $c->co2_saved,
                'status' => $userChallenge ? $userChallenge->pivot->status : 'pending',
                'imageUrl' => $c->image_url,
                'participants' => rand(100, 2000), // Mock data
                'impact' => 'Reduced carbon footprint'
            ];
        });

        return view('challenges', compact('challenges'));
    }
}
