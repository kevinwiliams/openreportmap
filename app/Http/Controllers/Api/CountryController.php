<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        return Country::query()
            ->select(['country_code', 'name', 'flag_emoji', 'map_center_lat', 'map_center_lng', 'map_zoom_level', 'timezone'])
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    public function parishes($code)
    {
        $country = Country::where('country_code', $code)->firstOrFail();

        return $country->parishes()
            ->select(['id', 'geonames_id', 'name', 'ascii_name', 'parish_code', 'latitude', 'longitude', 'population', 'community_count'])
            ->orderBy('name')
            ->get();
    }
}
