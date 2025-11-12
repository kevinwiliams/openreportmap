<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UtilityType;

class UtilityTypeController extends Controller
{
    public function index()
    {
        return UtilityType::query()
            ->select(['id', 'type_name', 'display_name', 'icon_name', 'sort_order'])
            ->orderBy('sort_order')
            ->orderBy('display_name')
            ->get();
    }
}
