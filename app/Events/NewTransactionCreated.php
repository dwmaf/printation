<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTransactionCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $outletId;

    public function __construct($outletId)
    {
        $this->outletId = $outletId;
    }

    public function broadcastOn(): array
    {
        // Broadcast ke channel outlet agar owner bisa terima
        return [new Channel('outlet-channel.' . $this->outletId)];
    }

    public function broadcastAs()
    {
        return 'transaction.created';
    }
}