<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country',
        'city',
        'date',
        'api_destination_id',
        'api_geo_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'date'
    ];

    protected $appends = ['full_name'];

    // Full name (city, country)
    public function getFullNameAttribute()
    {
        return $this->city . ', ' . $this->country;
    }

    /**
     * Get the comments for the blog post.
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function weathers()
    {
        return $this->hasMany(Weather::class);
    }
}
