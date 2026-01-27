<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    /** @use HasFactory<\Database\Factories\VariantFactory> */
    use HasFactory;

    protected $fillable = ['product_id', 'size_id', 'color_id', 'price', 'sale_price', 'quantity', 'status'];

    protected static function booted()
    {
        static::saved(function ($variant) {
            $minPrice = $variant->product->variants()->min('price');
            $variant->product->update(['price' => $minPrice]);
        });
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class);
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('slug', 'like', "%{$search}%");
                    });
            });
        });

        $query->when($filters['color'] ?? null, function ($query, $color) {
            $query->whereHas('color', function ($q) use ($color) {
                $q->where('id', $color);
            });
        });

        $query->when($filters['size'] ?? null, function ($query, $size) {
            $query->whereHas('size', function ($q) use ($size) {
                $q->where('name', 'like', "%{$size}%");
            });
        });

        return $query;
    }
}
