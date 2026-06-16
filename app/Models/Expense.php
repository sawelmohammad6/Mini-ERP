<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'amount',
        'category',
        'note',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function getNameForLog(): string
    {
        return $this->note ?? 'Expense #' . $this->id;
    }
}
