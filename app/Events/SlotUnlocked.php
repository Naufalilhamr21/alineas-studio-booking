<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlotUnlocked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $packageId;
    public $date;
    public $time;
    public $userId;

    public function __construct($packageId, $date, $time, $userId)
    {
        $this->packageId = $packageId;
        $this->date = \Carbon\Carbon::parse($date)->format('Y-m-d');
        $this->time = $time;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        // Channel khusus per paket agar tidak broadcast ke semua orang di dunia
        return new Channel('alineas.calendar');
    }

    public function broadcastAs()
    {
        return 'SlotUnlocked';
    }
}