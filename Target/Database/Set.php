<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Set extends Eloquent{
	
	protected $primarykey = 'class_id';
	public $incrementing = false;

	protected $table = 'sets';

	protected $fillable = [
		'class_id',
		'year_group',
		'code2',
		'block',
		'course',
		'subject',
		'number',
		];

}
