<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Checklist extends Eloquent{

	protected $fillable = [
		'filename',
		'subject',
		'board',
		'syllabus',
		'author',
		'organisation',
		];

    public $timestamps = false;
}
