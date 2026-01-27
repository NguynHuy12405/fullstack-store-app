<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'status'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => [
                'name' => $value,
                'slug' => str($value)->slug(),
            ],
        );
    }

    public function sizes(): HasMany{
        return $this->hasMany(Size::class);
    }

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

    public function brands()
    {
        return $this->belongsToMany(
            Brand::class,
            'products',
            'category_id',
            'brand_id'
        )->where('brands.status', 1)->distinct();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        });

        $query->when(isset($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });

        return $query;
    }
}
