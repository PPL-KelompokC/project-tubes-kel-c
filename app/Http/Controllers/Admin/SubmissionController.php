<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChallengeSubmission;
use App\Models\User;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * List submissions in manual_review status.
     */
    public function index()
    {
        $submissions = ChallengeSubmission::with(['user', 'challenge', 'verifications'])
            ->where('status', 'manual_review')
            ->latest()
            ->paginate(20);

        return view('admin.submissions', compact('submissions'));
    }

    /**
     * Admin approves a submission → verified + award points.
     */
    public function approve(ChallengeSubmission $submission)
    {
        if ($submission->status !== 'manual_review') {
            return back()->with('error', 'Submission is not in manual review.');
        }

        $submitter  = $submission->user;
        $challenge  = $submission->challenge;
        $points     = $challenge->points;

        $submission->update([
            'status'         => 'verified',
            'points_awarded' => $points,
            'verified_at'    => now(),
        ]);

        $submitter->increment('points', $points);
        $submitter->decrement('pending_points', min($points, $submitter->pending_points));
        $submitter->increment('carbon_saved', $challenge->co2_saved);
        $submitter->increment('challenges_completed');
        $submitter->updateStreak();

        return back()->with('success', "Submission approved! {$points} points awarded to {$submitter->name}.");
    }

    /**
     * Admin rejects a submission.
     */
    public function reject(ChallengeSubmission $submission)
    {
        if ($submission->status !== 'manual_review') {
            return back()->with('error', 'Submission is not in manual review.');
        }

        $submission->update(['status' => 'rejected']);

        return back()->with('success', 'Submission rejected.');
    }
}
