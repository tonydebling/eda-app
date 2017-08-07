<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Staffmember extends Eloquent{

	protected $table = 'staffmembers';
		
	protected $fillable = [
	    'title',
		'first_name',
		'last_name',
		'email',
        'tricode',
		'school_id',
        'role',
        'subject1',
        'subject2',
        'subject3',
		'registered',
		];
		
	public function school()
	{
			return $this->belongsTo('Target\Database\School');
	}
	
	public function getFullName()
	{
		if (!$this->first_name || !$this->last_name){
			return null;
		}	
		return "{$this->title} {$this->first_name} {$this->last_name}";
	}
	
	public function setAsRegistered()
	{
		$this->update([
			'registered' => true
		]);
	}

}
