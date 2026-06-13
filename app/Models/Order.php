<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use LogsActivity;

    protected $fillable = [
        'customer_id',
        'total',
        'discount',
        'final_price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
