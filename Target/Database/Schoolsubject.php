<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Schoolsubject extends Eloquent{

	protected $fillable = [
		'name',
		'tricode',
        'Qan',
        'checklist_id',
        'school_id',
		];

    public $timestamps = false;

	public function classes()
	{
			return $this->hasMany('Target\Database\Classe');
	}
	
	public function testpoints()
	{
			return $this->hasMany('Target\Database\Testpoint');
	}

	public function school()
	{
			return $this->belongsTo('Target\Database\School');
	}
}
