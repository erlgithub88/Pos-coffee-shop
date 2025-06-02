<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
  protected $fillable = [
    'manager_id',
    'title',
    'desc',
    'nominal',
    'type',
    'date',
  ];

  public function user()
  {
    return $this->belongsTo(Manager::class);
  }
}
