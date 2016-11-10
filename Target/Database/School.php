<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class School extends Eloquent{

	protected $table = 'schools';
		
	protected $fillable = [
		'id',
		'la_code',
		'estab_code',
		'name',
		'postcode',
		'domain',
		'registered',
		];
	
	public function setAsRegistered()
	{
		$this->update([
			'registered' => true
		]);
	}

}
