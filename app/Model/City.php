<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $table = null;

    protected $fillable = [
        'id',
        'coord',
        'country',
        'geoname',
        'name',
        'stat',
        'stations',
        'zoom',
    ];

    protected $casts = [
        'id' => 'integer',
        'coord' => 'array',
        'country' => 'string',
        'geoname' => 'array',
        'name' => 'string',
        'stat' => 'array',
        'stations' => 'array',
        'zoom' => 'integer',
    ];

    public function weathers(): HasMany
    {
        return $this->hasMany(Weather::class, 'city_id', 'id');
    }
}
