<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Event as CommunityEvent;

class UserJoinedEvent extends Notification
{
    use Queueable;

    public $user;
    public $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, CommunityEvent $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Participant!',
            'message' => "{$this->user->name} has joined your event: {$this->event->name}",
            'icon' => 'users',
            'url' => route('map')
        ];
    }
}
