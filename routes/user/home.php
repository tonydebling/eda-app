<?php

use Target\Database\Plc;

$app->get('/home', function() use($app) {
	
	$user_id = $app->request()->params('id');
	$user = $app->user->where('id', $user_id)->first();
	
	if (!$user) {
		$app->notFound();
	}
	if ($user->isStudent()) {
		$student_id = $user->student_id;


		$student = $app->student->where('id',$student_id)->first();
		$classes = $student->classes;

		$plc = new Plc;
		$k = 0;
		$dash = [];
		foreach ($classes as $classe){
			$dash[$k]['subject_id'] = $classe->schoolsubject_id;
			$dash[$k]['subject'] = $classe->schoolsubject->name;
			$subjectplc = $plc->where([
				['student_id', $student_id],
				['schoolsubject_id', $classe->schoolsubject_id],
			])->first();
			if ($subjectplc){
				$dash[$k]['plc_id'] = $subjectplc->id;
			}
			$k += 1;
		}
		
		$app->render('/user/stuhome.php', [
			'user' => $user,
			'dash' => $dash,
		]);
	
	} else {
		$app->render('/user/home.php', [
			'user' => $user,	
		]);
	}
	
})->name('user.home');
