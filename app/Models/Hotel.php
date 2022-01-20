<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'caption',
        'lat',
        'long',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function scopeHaveHotel($query, $name)
    {
        return $query->where('name', $name);
    }
}
