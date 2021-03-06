<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Checkpoint extends Eloquent{

	protected $fillable = [
		'name',
		'tricode',
		'school_id',
		'date',
		'year_group',
		'week_number',
		];

    public $timestamps = false;
		
	public function testpoints()
	{
			return $this->hasMany('Target\Database\Testpoint');
	}

	public function school()
	{
			return $this->belongsTo('Target\Database\School');
	}
}
