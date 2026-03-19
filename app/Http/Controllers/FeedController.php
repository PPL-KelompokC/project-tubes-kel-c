<?php

namespace App\Http\Controllers;

use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Community feed — show recent VERIFIED submissions as a social proof wall.
     * (Community voting removed; verification is now admin-only)
     */
    public function index()
    {
        $submissions = ChallengeSubmission::with(['user', 'challenge'])
            ->where('status', 'verified')
            ->latest('verified_at')
            ->paginate(20);

        return view('feed', compact('submissions'));
    }
}
