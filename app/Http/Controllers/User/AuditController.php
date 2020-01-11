<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Http\Resources\ActivityResource;

class AuditController extends Controller
{
    public function activity(Request $request)
    {
        $activities = Activity::whereCauserId($request->user()->id)->whereNull('subject_type')
        ->latest()->paginate(10);

        return ActivityResource::collection($activities);
    }
}
