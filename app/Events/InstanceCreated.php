// app/Events/InstanceCreated.php
<?php

namespace App\Events;

use App\Models\Instance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $instance;

    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('instance.' . $this->instance->id);
    }
}
