<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disaster;
use Illuminate\Http\Request;

class DisasterController extends Controller
{
    public function active()
    {
        return Disaster::query()
            ->select(['id', 'disaster_type', 'name', 'severity', 'start_time', 'end_time', 'alert_message', 'alert_message_es', 'alert_color', 'affected_countries'])
            ->where(function ($query) {
                $query->whereNull('end_time')
                    ->orWhere('end_time', '>', now());
            })
            ->orderByDesc('start_time')
            ->get();
    }
}
