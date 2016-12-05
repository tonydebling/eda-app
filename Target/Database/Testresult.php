<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Testresult extends Eloquent{

	protected $fillable = [
		'testpoint_id',
		'student_id',
		'grade',
		];
	
	public function testpoint()
	{
			return $this->belongsTo('Target\Database\Testpoint');
	}
	
	public function student()
	{
			return $this->belongsTo('Target\Database\Student');
	}
		
	public function testmarks()
	{
			return $this->hasMany('Target\Database\Testmark');
	}

}
