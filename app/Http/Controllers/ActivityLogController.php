<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Failed to load activity logs: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $activities = collect();
            $modules = collect();
            $actions = collect();

            return view('activity_logs.index', compact('activities', 'modules', 'actions'))
                ->with('warning', 'Unable to load activity logs at this time.');
        }

        return view('activity_logs.index', compact('activities', 'modules', 'actions'));
    }
}
