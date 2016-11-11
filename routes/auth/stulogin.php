<?php

$app->get('/stulogin(/:school_id)', $guest(), function($school_id = 0) use($app) {
	if ($school_id == 0){
		$app->render('auth/stufindschool.php');
	} else {
		$school = $app->school->where('id',$school_id)->first();
		if ($school){
			$app->render('auth/stulogin.php', [
				'school_id' => $school->id,
				'name' => $school->name,
				'errors' => [],
				]);
		} else {
			$app->render('auth/stufindschool.php');
		};
		die();
	}
})->name('stulogin');

$app->post('/stufindschool', $guest(), function() use($app) {
	
	$request = $app->request;
	$postcode = $request->post('postcode');

	$school = $app->school
		->where('postcode', $postcode)
		->first();
	
	if ($school){
		$app->redirect($app->urlFor('stulogin').'/'.$school->id);
		
	} else {
		$app->render('auth/stufindschool.php');
	};
	
	die();
	
})->name('stufindschool.post');

$app->post('/stulogin', $guest(), function() use($app) {
	
	$request = $app->request;
	$school_id = $request->post('school_id');
	$name = $request->post('name');
	$first_name = $request->post('first_name');
	$last_name = $request->post('last_name');	
	$date_of_birth = $request->post('date_of_birth');

	$v = $app->validation;
	
	$v->validate([
		'school_id' => [$school_id, 'required'],
		'first_name' => [$first_name, 'required'],
		'last_name' => [$last_name, 'required'],
		'date_of_birth' => [$date_of_birth, 'required'],
	]);
	
	if ($v->passes()){
		/* Need to include DOB check */
		$student = $app->student
			->where('first_name', $first_name)
			->where('last_name', $last_name)
			->where('school_id', $school_id)
			->first();

		if ($student) {
			/* Set up a user record if there is not one already */
			$user = $app->user->firstOrNew(['student_id' => $student->id]);
			$user->student_id = $student->id;
			$user->school_id = $school_id;
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->email = $student->email;
			$user->is_student = true;
			$user->active = true;
			$user->save();

			$_SESSION[$app->config->get('auth.session')] = $user->id;

			$app->flash('global','You are now signed in');
			$app->redirect($app->urlFor('user.home', ['user_id' =>$user->id]));
		};	
	}
	
	$app->render('auth/stulogin.php', [
		'school_id' => $school_id,
		'name' => $name,
		'errors' => $v->errors(),
		'request' => $request,
	]);	

})->name('stulogin.post');
