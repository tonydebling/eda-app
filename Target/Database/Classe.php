<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Classe extends Eloquent{

	protected $fillable = [
		'school_id',
		'school_classe_id',
        'teacher_tricode',
		'year_group',
		'schoolsubject_id',
		'course',
		];
		
	public function school()
	{
			return $this->belongsTo('Target\Database\School');
	}
	public function schoolsubject()
	{
			return $this->belongsTo('Target\Database\Schoolsubject');
	}	
	public function students()
	{
			return $this->belongsToMany('Target\Database\Student', 'student_classe');
			
//				-> withPivot('checklist_id', 'number');
	}

}
