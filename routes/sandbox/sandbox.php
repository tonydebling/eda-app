<?php

use Target\Database\Student;
use Target\Database\Schoolsubject;
use Target\Database\Classe;
use Target\Database\Student_Classe;
use Target\Database\Testpoint;
use Target\Database\Template;

$app->get('/sandbox', function() use($app) {
/*	This increnents the school year of every student! Fix for test data.
	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();

	$student = new Student;
	$students = $student
		->where('school_id', $user->school_id)
		->get();
	foreach($students as $stu){
		$ny = $stu->year_group + 1;
		$stu -> update(['year_group'=> $ny]);
	};
	$students = $student
		->where('school_id', $user->school_id)
		->get();	
	
	$table = $students->toArray();
	$columns = array_keys($table[0]);
	$app->render('admin/displayfile.php', [
		'heading' => 'Updated students',
		'columns' => $columns,
		'table' => $table
	]);
*/
	})->name('sandbox');
