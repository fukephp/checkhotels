<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'api_weather_id',
        'main',
        'description',
        'icon',
        'temp_day',
        'temp_min',
        'temp_max',
        'temp_night',
        'temp_eve',
        'temp_morn',
        'date',
    ];

    protected $table = 'weathers';

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

    /**
     * Get the post that owns the comment.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Scope a query to find today weather forecast.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTodayWeather($query, $time)
    {
        return $query->whereDate('date', '=', \Carbon\Carbon::createFromTimestamp($time)->format('Y-m-d'));
    }
}
