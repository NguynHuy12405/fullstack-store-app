<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use App\Models\CartDetail;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'status',
        'review',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cartDetails(): HasMany
    {
        return $this->hasMany(CartDetail::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function role(): BelongsTo{
        return $this->belongsTo(Role::class);
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

    public function processedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'admin_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Order::class, 'shipper_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Review::class, 'admin_id');
    }

    public function isAdmin(): bool
    {
        return $this->role()->where('name', 'like', 'admin%')->exists();
    }

    public function reviews(): HasMany{
        return $this->hasMany(Review::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', $search)
                    ->orWhere('phone', $search)
                    ->orWhere('id', $search);
            });
        });

        // Filter
        $query->when($filters['role'] ?? null, function ($query, $role) {
            $query->where('category_id', $role);
        });

        $query->when(isset($filters['gender']), function ($query) use ($filters) {
            $query->where('status', $filters['gender']);
        });

        $query->when(isset($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });

        return $query;
    }
}
