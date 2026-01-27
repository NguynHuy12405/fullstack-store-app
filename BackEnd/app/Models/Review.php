<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = ['product_id','user_id','rating','comment','status','reply','replier','reply_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replier()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeReplied($query)
    {
        return $query
            ->whereNotNull('reply')
            ->where('reply', '!=', '')
            ->whereNotNull('reply_at');
    }

    public function getIsRepliedAttribute(): bool
    {
        return !empty($this->reply) && !is_null($this->reply_at);
    }

    public function replierUser()
    {
        return $this->belongsTo(User::class, 'replier');
    }
}
