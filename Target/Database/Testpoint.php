<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Testpoint extends Eloquent{

	protected $fillable = [
		'checkpoint_id',
		'subject_id',
		'course_id',
		'test_type',
		'weighting',
		'template_id',
		];
	
	public function checkpoint()
	{
			return $this->belongsTo('Target\Database\Checkpoint');
	}
	
	public function testresults()
	{
			return $this->hasMany('Target\Database\Testresult');
	}
	
	public function template()
	{
			return $this->belongsTo('Target\Database\Template');

	}
}
