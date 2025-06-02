<?php

namespace App\Events;

use Illuminate\{
    Broadcasting\InteractsWithSockets,
    Broadcasting\PrivateChannel,
    Contracts\Broadcasting\ShouldBroadcast,
    Foundation\Events\Dispatchable,
    Queue\SerializesModels
};

class VehicleReminderNotification implements ShouldBroadcast

{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $title;
    public $message;
    public $link;

    public function __construct($userId, $title, $message, $link)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'vehicle.reminder';
    }
}
