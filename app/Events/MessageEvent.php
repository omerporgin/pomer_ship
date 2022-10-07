<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notifications;
    public $totalNewNotifications;
    public $messages;
    public $userID;
    public $channelName;
    public $session;

    public function __construct(int $userID)
    {
        $this->notifications = \App\NotificationService\NotificationService::getNotifications($userID);
        $this->totalNewNotifications = \App\NotificationService\NotificationService::getTotalNewNotifications($userID);
        $this->messages = [];
        $this->userID = $userID;
        $this->channelName = 'client-messages.' . $this->userID;
        $this->session = session('_token');
    }

    public function broadcastOn()
    {
        return [$this->channelName];
    }

    public function broadcastAs()
    {
        return $this->channelName;
    }

}
