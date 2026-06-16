<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $query = ActivityLog::with('user');

        $query->filter(request()->only(['module', 'action', 'from_date', 'to_date']));

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('description', 'like', "%{$search}%");
        }

        $activities = $query->latest()->paginate(config('erp.pagination_size'));

        $allTypes = ActivityLog::select('model_type')
            ->distinct()
            ->whereNotNull('model_type')
            ->pluck('model_type');

        $modules = $allTypes->map(fn($t) => class_basename($t))->sort()->values();
        $actions = ActivityLog::select('action')->distinct()->pluck('action')->sort();

        return view('activity_logs.index', compact('activities', 'modules', 'actions'));
    }
}
