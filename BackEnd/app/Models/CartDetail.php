<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CartDetail extends Model
{
    /** @use HasFactory<\Database\Factories\CartDetailFactory> */
    use HasFactory;

    protected $fillable = [
        "user_id",
        "variant_id",
        "quantity",
        "status"
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
