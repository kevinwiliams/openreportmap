<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parish extends Model
{
    use HasFactory;

    protected $fillable = [
        'geonames_id', 'name', 'ascii_name', 'parish_code', 'latitude',
        'longitude', 'population', 'community_count', 'country_code'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }

    public function communities()
    {
        return $this->hasMany(Community::class, 'parish_code', 'parish_code');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'parish_code', 'parish_code');
    }
}