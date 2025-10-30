<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image', 'summary', 'content', 'date'
    ];

    protected $appends = ['image_url'];

    // Accessor for full image URL
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('uploads/blogs/' . $this->image);
        }
        return asset('uploads/blogs/default.jpg'); // fallback image
    }
}
