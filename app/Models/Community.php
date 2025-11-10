<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'geonames_id',
        'name',
        'ascii_name',
        'parish_code',
        'latitude',
        'longitude',
        'population',
        'country_code',
    ];

    public function parish()
    {
        return $this->belongsTo(Parish::class, 'parish_code', 'parish_code');
    }
}
