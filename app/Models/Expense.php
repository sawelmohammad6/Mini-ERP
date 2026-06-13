<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use LogsActivity;

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
}
