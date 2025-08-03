<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'isbn', 'author', 'publisher', 'publication_year',
        'pages', 'description', 'cover_image', 'stock', 'available_stock',
        'location', 'category_id', 'is_featured', 'is_active'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            $book->slug = Str::slug($book->title);
        });

        static::updating(function ($book) {
            $book->slug = Str::slug($book->title);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings()
    {
        return $this->borrowings()->where('status', 'borrowed');
    }

    public function isAvailable()
    {
        return $this->available_stock > 0;
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/books/' . $this->cover_image);
        }
        return asset('images/no-cover.jpg');
    }
}
