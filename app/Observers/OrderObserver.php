<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('order_status_id') && $order->order_status_id == 4) {
            DB::transaction(function () use ($order) {
                foreach ($order->orderDetails as $detail) {
                    $detail->variant->increment('quantity', $detail->quantity);
                }
            });
        }

        if ($order->wasChanged('ship_status_id') && $order->ship_status_id == 1) {
            $order->shipper_id = null;
            $order->saveQuietly();
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
