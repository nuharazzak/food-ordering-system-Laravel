<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Food extends Model
{
    // Explicitly define table name since the migration is 'foods' and singular is Food
    protected $table = 'foods';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    /**
     * Get the category that owns the food item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
