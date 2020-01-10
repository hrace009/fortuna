<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function activity(Request $request)
    {
        $activities = Activity::whereCauserId($request->user()->id)->whereNull('subject_type')
        ->latest()->paginate(10);

        return ActivityResource::collection($activities);
    }
}
