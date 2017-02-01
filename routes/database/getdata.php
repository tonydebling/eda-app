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
	
	$school = new School;
	$schools = $school
//		->where('postcode', 'LIKE', 'TN2%')
		->get();
	$table = $schools->toArray();
	$jtable = json_encode($table);
	echo $jtable;
	exit();

})->name('getdata.schools');
