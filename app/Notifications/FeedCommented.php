<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class FeedCommented extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Feed $feed,
        public Comment $comment,
        public User $commenter,
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
            'title'      => "{$this->commenter->name} commented on your post",
            'emoji'      => '💬',
            'message'    => '"' . mb_substr($this->comment->content, 0, 80) . '"',
            'category'   => 'social',
            'action_url' => '/feed',
            'feed_id'      => $this->feed->id,
            'comment_id'   => $this->comment->id,
            'commenter_id' => $this->commenter->id,
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
        return 'notification.feed_commented';
    }
}
