<?php

use Target\Database\School;
use Target\Database\Schoolsubject;

$app->get('/getdata', function() use($app) {
	
	$subject = new Schoolsubject;
	$subjects = $subject
		->where('school_id', 100000)
		->get();

	$table = $subjects->toArray();
	$jtable = json_encode($table);
	echo $jtable;
	exit();

})->name('getdata');


$app->get('/getdata/schools', function() use($app) {
	$sstr = $app->request()->params('sstr');
	$school = new School;
	$table = [];
	$schools = $school
		->where('postcode', 'LIKE', $sstr.'%')
		->orwhere('name','LIKE','%'.$sstr.'%')
		->take(20)
		->get();
	$table = $schools->toArray();
	$jtable = json_encode($table);
	echo $jtable;
	exit();
})->name('getdata.schools');
