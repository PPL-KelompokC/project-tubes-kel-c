<?php

namespace App\Notifications;

use App\Models\RewardTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RedemptionRejected extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public RewardTransaction $transaction,
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
            'title'      => 'Redemption Rejected',
            'emoji'      => '🔄',
            'message'    => "Your redemption of {$this->transaction->reward->name} was rejected. {$this->transaction->points_used} points have been refunded.",
            'category'   => 'challenge',
            'action_url' => '/rewards',
            'transaction_id' => $this->transaction->id,
            'reward_id'      => $this->transaction->reward_id,
            'points_refunded' => $this->transaction->points_used,
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
        return 'notification.redemption_rejected';
    }
}
