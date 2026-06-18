<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            if ($model->wasChanged('is_active') && $model->is_active) {
                static::logActivity($model, 'activated');
            } elseif ($model->wasChanged('is_active') && !$model->is_active) {
                static::logActivity($model, 'deactivated');
            } else {
                static::logActivity($model, 'updated');
            }
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity($model, string $action)
    {
        if (!Auth::check()) {
            return;
        }

        try {
            $description = static::getActivityDescription($model, $action);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'model_type'  => get_class($model),
                'model_id'    => $model->id ?? null,
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage(), [
                'model_type' => get_class($model),
                'model_id'   => $model->id ?? null,
                'action'     => $action,
                'trace'      => $e->getTraceAsString(),
            ]);
        }
    }

    protected static function getActivityDescription($model, string $action): string
    {
        $actor = Auth::user()->name ?? 'System';
        $module = class_basename($model);

        if ($model instanceof \App\Models\Order) {
            return "{$actor} {$action} Order #{$model->id}";
        }

        $name = method_exists($model, 'getNameForLog')
            ? $model->getNameForLog()
            : ($model->name ?? $model->id ?? 'Unknown');

        return "{$actor} {$action} {$module}: {$name}";
    }
}
