<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    /** @use HasFactory<\Database\Factories\ImportFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function importDetails(): HasMany
    {
        return $this->hasMany(ImportDetail::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
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
        return $query;
    }
}
