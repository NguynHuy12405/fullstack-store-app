<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'thumbnail', 'status', 'category_id', 'brand_id'];

    // Tự động thêm Slug khi thêm name
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => [
                'name' => $value,
                'slug' => str($value)->slug(),
            ],
        );
    }

    // Cho phép Laravel tìm kiếm Model qua cột slug thay vì ID
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function reviewsRaw(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function latestReview()
    {
        return $this->hasOne(Review::class)->latestOfMany();
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->variants->min('price');
            }
        );
    }
    protected function salePrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->variants->where('sale_price', '>', '0')->sum('sale_price') ?? 0;
            }
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('id', $search);

                $q->orWhereHas('brand', function ($qBrand) use ($search) {
                    $qBrand->where('name', 'like', "%{$search}%");
                });

                $q->orWhereHas('tags', function ($qTag) use ($search) {
                    $qTag->where('name', 'like', "%{$search}%");
                });
            });
        });

        // Filter
        $query->when($filters['category'] ?? null, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        });

        $query->when($filters['brand'] ?? null, function ($query, $brandId) {
            $query->where('brand_id', $brandId);
        });

        $query->when($filters['rating'] ?? null, function ($query, $rating) {
            $query->where('products.rating', '>=', (int) $rating);
        });

        $query->when(
            $filters['price_from'] ?? $filters['price_to'] ?? null,
            function ($query) use ($filters) {

                $query->whereHas('variants', function ($q) use ($filters) {

                    if (!empty($filters['price_from'])) {
                        $q->where('price', '>=', (int) $filters['price_from']);
                    }

                    if (!empty($filters['price_to'])) {
                        $q->where('price', '<=', (int) $filters['price_to']);
                    }
                });
            }
        );

        $query->when(isset($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });

        $query->when($filters['tags'] ?? null, function ($query, $tagList) {
            $count = count($tagList);
            if ($count > 0) {
                $query->whereHas('tags', function ($qTag) use ($tagList) {
                    $qTag->whereIn('id', $tagList);
                }, $count);
            }
        });

        return $query;
    }
}
