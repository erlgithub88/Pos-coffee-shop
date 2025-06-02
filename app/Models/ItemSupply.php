<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSupply extends Model
{
  protected $fillable = [
    'manager_id',
    'item_id',
    'qty',
    'date',
  ];

  public function manager()
  {
    return $this->belongsTo(Manager::class);
  }

  public function item()
  {
    return $this->belongsTo(Item::class);
  }
}
