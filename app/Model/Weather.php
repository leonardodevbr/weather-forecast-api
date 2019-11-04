<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Weather extends Model
{
    protected $table = null;

    protected $fillable = [
        'id',
        'city_id',
        'dt',
        'dt_formatted',
        'temp',
        'pressure',
        'humidity',
        'weather',
        'speed',
        'deg',
        'clouds',
        'uvi',
    ];

    protected $casts = [
        'id' => 'integer',
        'city_id' => 'integer',
        'dt' => 'timestamp',
        'temp' => 'array',
        'pressure' => 'float',
        'humidity' => 'integer',
        'weather' => 'array',
        'speed' => 'float',
        'deg' => 'integer',
        'clouds' => 'integer',
        'uvi' => 'float',
    ];

    public function cities(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
