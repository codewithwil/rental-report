<?php

namespace App\Events;

use Illuminate\{
    Broadcasting\Channel,
    Contracts\Broadcasting\ShouldBroadcast,
    Queue\SerializesModels
};

class TestBroadcastEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

  
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('test-channel');
    }

    public function broadcastAs()
    {
        return 'TestEvent';
    }
}
