<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use App\Models\Feed;
use App\Models\Comment;
use App\Models\Reward;
use App\Models\RewardTransaction;
use App\Models\Event;

use App\Notifications\SubmissionApproved;
use App\Notifications\SubmissionRejected;
use App\Notifications\FeedLiked;
use App\Notifications\FeedCommented;
use App\Notifications\CommentReplied;
use App\Notifications\RedemptionCompleted;
use App\Notifications\RedemptionRejected;
use App\Notifications\StreakMilestone;
use App\Notifications\NewChallengeAvailable;
use App\Notifications\EventStatusChanged;

class NotificationTestSeeder extends Seeder
{
    public function run()
    {
        // Get or create a regular user
        $user = User::where('role', 'user')->first() ?? User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com', 'role' => 'user']);
        $otherUser = User::where('id', '!=', $user->id)->first() ?? User::factory()->create(['name' => 'Eco Friend']);

        // 1 & 2: Challenge Submissions
        $challenge = Challenge::firstOrCreate(
            ['title' => 'Plant a Tree'],
            ['description' => 'Plant one tree', 'category' => 'nature', 'difficulty' => 'easy', 'points' => 100, 'co2_saved' => 5.5]
        );
        $submission = ChallengeSubmission::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'status' => 'pending_admin',
            'photo_path' => 'dummy.jpg',
            'rejection_reason' => 'Photo too blurry',
        ]);
        $user->notify(new SubmissionApproved($submission));
        $user->notify(new SubmissionRejected($submission));

        // 3, 4 & 5: Social (Feed, Comments)
        $feed = Feed::create([
            'user_id' => $user->id,
            'caption' => 'Just completed my first beach cleanup!',
            'status' => 'active',
            'feed_type' => 'post',
        ]);
        $comment = Comment::create([
            'feed_id' => $feed->id,
            'user_id' => $otherUser->id,
            'content' => 'Wow, amazing job!',
        ]);
        $reply = Comment::create([
            'feed_id' => $feed->id,
            'user_id' => $otherUser->id,
            'parent_id' => $comment->id,
            'content' => 'I would love to join next time.',
        ]);
        
        $user->notify(new FeedLiked($feed, $otherUser));
        $user->notify(new FeedCommented($feed, $comment, $otherUser));
        $user->notify(new CommentReplied($reply, $otherUser));

        // 6 & 7: Rewards
        // 6 & 7: Rewards
        $reward = Reward::firstOrCreate(
            ['name' => 'Eco Tote Bag'],
            ['description' => 'A nice bag', 'points_required' => 500, 'stock' => 10, 'status' => 'active', 'category' => 'physical']
        );
        $transaction = RewardTransaction::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'points_used' => 500,
            'status' => 'pending'
        ]);
        $user->notify(new RedemptionCompleted($transaction));
        $user->notify(new RedemptionRejected($transaction));

        // 8: Streak
        $user->notify(new StreakMilestone(7));

        // 9: New Challenge
        $newChallenge = Challenge::firstOrCreate(
            ['title' => 'Zero Waste Week'],
            ['description' => 'Produce no trash', 'category' => 'lifestyle', 'difficulty' => 'hard', 'points' => 500, 'co2_saved' => 10.0]
        );
        $user->notify(new NewChallengeAvailable($newChallenge));

        // 10: Event
        $event = Event::create([
            'user_id' => $user->id,
            'name' => 'City Park Cleanup',
            'type' => 'cleanup',
            'date' => now()->addDays(5),
            'status' => 'accepted',
            'description' => 'Cleaning the park',
            'x' => 0,
            'y' => 0
        ]);
        $user->notify(new EventStatusChanged($event));
        
        $eventRejected = Event::create([
            'user_id' => $user->id,
            'name' => 'Random Event',
            'type' => 'workshop',
            'date' => now()->addDays(5),
            'status' => 'rejected',
            'description' => 'Random',
            'x' => 0,
            'y' => 0
        ]);
        $user->notify(new EventStatusChanged($eventRejected));

        $this->command->info('11 Test notifications have been seeded for user: ' . $user->email);
    }
}
