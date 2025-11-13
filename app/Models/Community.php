<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Community extends Model
{
    use HasFactory;

    protected $primaryKey = 'geonames_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'geonames_id', 'name', 'ascii_name', 'parish_code', 'latitude',
        'longitude', 'population', 'country_code'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }

    public function parish()
    {
        return $this->belongsTo(Parish::class, 'parish_code', 'parish_code');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'community_geonames_id', 'geonames_id');
    }
}