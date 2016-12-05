<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Template extends Eloquent{

	protected $fillable = [
		'filename',
		'code',
		'subject',
		'board',
		'paper',
		'year',
		'month',
		'mpcount',
		];

    public $timestamps = false;
}
