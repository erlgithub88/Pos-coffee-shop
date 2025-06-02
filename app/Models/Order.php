<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'cashier_id',
        'discount',
        'discount_type',
        'date',
        'status',
        'payment_method_id',
    ];

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
