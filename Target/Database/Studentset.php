<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Studentset extends Eloquent{
	
	protected $primarykey = null;
	public $incrementing = false;

	protected $table = 'studentsets';

	protected $fillable = [
		'school_id',
		'class',
		'number',
		];
	
}
