<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'address',
        'monthly_wage',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
