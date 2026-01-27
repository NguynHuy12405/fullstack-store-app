<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    /** @use HasFactory<\Database\Factories\SlideFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'image',
        'title',
        'description',
        'url',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
