<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getModuleAttribute()
    {
        return $this->model_type ? class_basename($this->model_type) : null;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['module'] ?? null, function ($q, $module) {
            $q->where('model_type', 'like', '%' . $module);
        })->when($filters['action'] ?? null, function ($q, $action) {
            $q->where('action', $action);
        })->when($filters['from_date'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '>=', $date);
        })->when($filters['to_date'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '<=', $date);
        });
    }
}
