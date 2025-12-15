<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'metadata',
        'price',
        'image',
        'category_id',
        'is_official',
    ];

    /**
     * Cast attributes to proper types.
     * Ensure metadata array is stored as JSON in the database.
     */
    protected $casts = [
        'metadata' => 'array',
        'price' => 'decimal:2',
    ];

    // Category relationship - each item belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Count items created within the last N minutes.
     *
     * @param int $minutes
     * @return int
     */
    public static function countNewWithinMinutes(int $minutes = 8): int
    {
        return static::where('is_official', true)->where('created_at', '>=', now()->subMinutes($minutes))->count();
    }
}
