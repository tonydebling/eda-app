<?php

use Target\Database\School;
use Target\Database\Schoolsubject;
use Illuminate\Database\Capsule\Manager as DB;

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


$app->get('/getdata/resources', function() use($app) {
    $sstr = $app->request()->params('sstr');
    $sql = "select * from resources where MATCH (title,keywords) AGAINST (".$sstr." IN BOOLEAN MODE)";
    $table = DB::select($sql);
    $jtable = json_encode($table);
    echo $jtable;
    exit();
})->name('getdata.resources');