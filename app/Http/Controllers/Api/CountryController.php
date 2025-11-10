<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return Country::where('is_active', 1)->get();
    }

    public function parishes($code)
    {
        $country = Country::where('country_code', $code)->firstOrFail();
        return $country->parishes;
    }
}
