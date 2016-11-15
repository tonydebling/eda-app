<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Student_Classe extends Eloquent{
	
	protected $primarykey = null;
	public $incrementing = false;
	protected $table = 'student_classe';

	protected $fillable = [
		'student_id',
		'school_student_id',
		'classe_id',
		'school_classe_id',
		'checklist_id',
		'number',
		];
	
}
