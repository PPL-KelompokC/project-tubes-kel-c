<?php

namespace App\Notifications;

use App\Models\ChallengeSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SubmissionRejected extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ChallengeSubmission $submission,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification (database storage).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'      => 'Submission Rejected',
            'emoji'      => '❌',
            'message'    => "Your {$this->submission->challenge->title} submission was rejected. Reason: {$this->submission->rejection_reason}",
            'category'   => 'challenge',
            'action_url' => '/challenges',
            'submission_id'    => $this->submission->id,
            'challenge_id'     => $this->submission->challenge_id,
            'rejection_reason' => $this->submission->rejection_reason,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return 'notification.submission_rejected';
    }
}
