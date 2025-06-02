<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
	protected $fillable = [
		'member_id',
		'nama_barang',
		'tanggal',
		'status'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}
}
