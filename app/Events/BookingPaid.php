<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingPaid implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $date;

    public function __construct($date)
    {
        // Hanya perlu mengirimkan tanggal saja untuk men-trigger refresh UI
        $this->date = \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function broadcastOn()
    {
        return new Channel('alineas.calendar');
    }

    public function broadcastAs()
    {
        return 'BookingPaid';
    }
}