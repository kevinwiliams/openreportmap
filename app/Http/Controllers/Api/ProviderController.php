<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = Provider::query()->orderBy('provider_name');

        if ($request->filled('utility_type_id')) {
            $query->where('utility_type_id', $request->input('utility_type_id'));
        }

        if ($request->filled('country_code')) {
            $country = Country::where('country_code', $request->input('country_code'))->first();

            if ($country) {
                $query->where('country_name', $country->name);
            }
        }

        return $query->get([
            'id',
            'provider_name',
            'provider_code',
            'utility_type_id',
            'utility_type_name',
            'country_id',
            'country_name',
            'website_url',
            'support_phone',
            'support_email',
        ]);
    }
}
