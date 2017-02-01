<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Testpoint extends Eloquent{

	protected $fillable = [
		'school_id',
		'checkpoint_id',
		'year_group',
		'schoolsubject_id',
		'course_id',
		'test_type',
		'weighting',
		'template_id',
		];

    public $timestamps = false;
		
	public function checkpoint()
	{
			return $this->belongsTo('Target\Database\Checkpoint');
	}
	
	public function schoolsubject()
	{
			return $this->belongsTo('Target\Database\Schoolsubject');
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
