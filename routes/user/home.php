<?php

$app->get('/h/:user_id', function($user_id) use($app) {
	
	$user = $app->user->where('id', $user_id)->first();
	
	if (!$user) {
		$app->notFound();
	}
	if ($user->isStudent()) {
		$student = $app->student->where('id',$user->student_id)->first();
		$classes = $student->classes;
		$app->render('/user/stuhome.php', [
			'user' => $user,
			'student' => $student,
			'classes' => $classes,
		]);
	} else {
		$app->render('/user/home.php', [
			'user' => $user,	
		]);
	}
	
})->name('user.home');
