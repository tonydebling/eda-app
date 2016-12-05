<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Checkpoint extends Eloquent{

	protected $fillable = [
		'name',
		'school_id',
		'date',
		'year_group',
		'week_number',
		'year_group',
		];
		
	public function testpoints()
	{
			return $this->hasMany('Target\Database\Testpoint');
	}

	public function school()
	{
			return $this->belongsTo('Target\Database\School');
	}
}
