<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_product extends Model {
    use HasFactory;
    protected $table = 'order_products';

    protected $fillable = [

        'name', 'price', 'plant_id', 'order_id', 'quantity',

    ];
}
