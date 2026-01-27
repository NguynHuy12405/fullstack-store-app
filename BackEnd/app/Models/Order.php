<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'phone', 'address', 'order_status_id', 'ship_status_id', 'admin_id', 'shipper_id', 'message'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function shipper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function shipStatus(): BelongsTo
    {
        return $this->belongsTo(ShipStatus::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('phone', $search)
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', $search);
                    });
            });
        });

        $query->when($filters['from_date'] ?? null, function ($query, $fromDate) {
            return $query->whereDate('created_at', '>=', $fromDate);
        });

        $query->when($filters['to_date'] ?? null, function ($query, $toDate) {
            return $query->whereDate('created_at', '<=', $toDate);
        });

        // Lọc theo khoảng giá (Total_price)
        $query->when($filters['min_price'] ?? null, function ($query, $minPrice) {
            return $query->where('total_price', '>=', $minPrice);
        });

        $query->when($filters['max_price'] ?? null, function ($query, $maxPrice) {
            return $query->where('total_price', '<=', $maxPrice);
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            return $query->where('order_status_id', $status);
        });

        $query->when($filters['ship_status'] ?? null, function ($query, $status) {
            return $query->where('ship_status_id', $status);
        });
        return $query;
    }
}
