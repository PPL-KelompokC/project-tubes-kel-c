<?php
use App\Models\User;
use App\Models\ChallengeSubmission;

foreach(ChallengeSubmission::all() as $s) {
    if ($s->points_awarded == 0 && $s->status == 'verified') {
        $s->points_awarded = $s->challenge->points;
        $s->save();
    }
}

foreach(User::all() as $u) {
    $pts = ChallengeSubmission::where('user_id', $u->id)->where('status', 'verified')->sum('points_awarded');
    $co2 = ChallengeSubmission::where('user_id', $u->id)->where('status', 'verified')
        ->join('challenges', 'challenge_id', '=', 'challenges.id')->sum('co2_saved');
    $cnt = ChallengeSubmission::where('user_id', $u->id)->where('status', 'verified')->count();
    
    // Simulate streak if they have at least one submission
    $streak = $cnt > 0 ? 1 : 0;
    
    // If the user's role is not admin, push the stats so their local testing state is perfectly aligned.
    if($u->role !== 'admin') {
        $u->update([
            'points' => max($u->points, $pts), // In case they had seeded points
            'carbon_saved' => max($u->carbon_saved, $co2),
            'challenges_completed' => max($u->challenges_completed, $cnt),
            'streak' => max($u->streak, $streak)
        ]);
        
        // Also force a streak update to catch the case where they logged in but it wasn't counting correctly yesterday
        $u->updateStreak();
    }
}
echo "Synchronized successfully.\n";
