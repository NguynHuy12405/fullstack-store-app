<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    /** @use HasFactory<\Database\Factories\ImportDetailFactory> */
    use HasFactory;

    protected $fillable = ['import_id', 'variant_id', 'quantity', 'price'];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    protected static function booted()
    {
        static::created(function ($detail) {
            $detail->variant->increment('quantity', $detail->quantity);
        });
        static::created(function ($detail) {
            $detail->import->increment('total_price', $detail->price * $detail->quantity);
        });
    }
}
