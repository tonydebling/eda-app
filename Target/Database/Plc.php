<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Plc extends Eloquent{

	protected $fillable = [
		'student_id',
		'checklist_id',
		'schoolsubject_id',
		'ratings',
		'hot_topics',
		'lower_rank',
		'upper_rank',
		];
	
	public function checklist()
	{
			return $this->belongsTo('Target\Database\Checklist');
	}
	
	public function student()
	{
			return $this->belongsTo('Target\Database\Student');
	}
	
	public function schoolsubject()
	{
			return $this->belongsTo('Target\Database\Schoolsubject');
	}
}
