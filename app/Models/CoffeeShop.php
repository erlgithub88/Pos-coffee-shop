<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoffeeShop extends Model
{
    protected $fillable = [
        'shop_name',
        'image_path',
        'phone_number',
        'address',
        'footer_message',
    ];
}
