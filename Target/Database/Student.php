<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Student extends Eloquent{

	protected $table = 'students';
		
	protected $fillable = [
		'first_name',
		'last_name',
		'preferred_name',
		'gender',
		'date_of_birth',
		'year_group',
		'email',
		'school_id',
		'candidate_number',
		'upn',
		'registered',
		];
		
	public function classes()
	{
			return $this->belongsToMany('Target\Database\Classe', 'student_classe')
				-> withPivot('checklist_id', 'number');
	}

	public function getFullName()
	{
		if (!$this->first_name || !$this->last_name){
			return null;
		}
		
		return "{$this->first_name} {$this->last_name}";
	}
	
	public function setAsRegistered()
	{
		$this->update([
			'registered' => true
		]);
	}

}
