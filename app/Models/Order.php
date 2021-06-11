<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [

        'shipping_address', 'price', 'phone', 'order_expecting_date', 'delivery_condition',
        'quantity', 'order_status', 'plant_id', 'user_id',

    ];
}
