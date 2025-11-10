<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = Provider::query();

        if ($request->has('utility_type_id')) {
            $query->where('utility_type_id', $request->utility_type_id);
        }

        if ($request->has('country_code')) {
            $query->whereHas('utilityType', function ($q) use ($request) {
                $q->where('country_code', $request->country_code);
            });
        }

        return $query->get();
    }
}
