<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Testmark extends Eloquent{

	protected $fillable = [
		'testresult_id',
		'markpoint_id',
		'mark',
		];
	
	public function testresult()
	{
			return $this->belongsTo('Target\Database\Testresult');
	}

}
