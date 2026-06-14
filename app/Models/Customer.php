<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
}
