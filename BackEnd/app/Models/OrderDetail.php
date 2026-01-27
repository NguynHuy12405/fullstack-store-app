<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    /** @use HasFactory<\Database\Factories\OrderDetailFactory> */
    use HasFactory;
    protected $fillable =['order_id',
    'variant_id',
    'price',
    'quantity'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
    protected static function booted()
    {
        static::created(function ($detail) {
            $detail->variant->decrement('quantity', $detail->quantity);
        });
        static::created(function ($detail) {
            $detail->order->increment('total_price', $detail->price * $detail->quantity);
        });
    }
}
