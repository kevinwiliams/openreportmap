<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parish;
use Illuminate\Http\Request;

class ParishController extends Controller
{
    public function communities($code)
    {
        $parish = Parish::where('parish_code', $code)->firstOrFail();
        return $parish->communities;
    }
}
