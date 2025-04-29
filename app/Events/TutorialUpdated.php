<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TutorialUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    use Dispatchable;

    public $notifications;
    /**
     * Create a new event instance.
     */
    public function __construct(array $notifications)
    {
        $this->notifications = $notifications ?? [
            'added' => [999],
            'updated' => [],
            'deleted' => [],
            'test_connection' => true  // Nouveau champ pour identifier le test
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('tutorials-updates'),
        ];
    }

    public function broadcastWith(): array{
        return $this->notifications;
    }
}
