<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Classe extends Eloquent{

	protected $fillable = [
		'school_id',
		'school_classe_id',
		'year_group',
		'code2',
		'block',
		'course',
		'subject',
		];

}
