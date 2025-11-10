<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disaster;
use Illuminate\Http\Request;

class DisasterController extends Controller
{
    public function active()
    {
        return Disaster::where('end_time', '>', now())->orWhereNull('end_time')->get();
    }
}
