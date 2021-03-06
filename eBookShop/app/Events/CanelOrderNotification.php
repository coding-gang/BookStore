<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CanelOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order;
    public $userName;
    public $userId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order,$userName,$userId)
    {
       $this->order =$order;
       $this->userName = $userName;
       $this->userId =$userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('my-channel');
    }
    public function broadcastAs(){
        return 'cancelOrder-detail';
    }
}
