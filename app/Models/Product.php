<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'name',
    'price',
    'description',
    'image',
    'stock_quantity',
    'low_stock_alert',
];
    public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
}
