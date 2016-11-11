<?php

use Target\User\UserPermission;

$app->get('/register(/:school_id)', $guest(), function($school_id = 0) use($app) {
	if ($school_id == 0){
		$app->render('auth/findschool.php');
	} else {
		$school = $app->school->where('id',$school_id)->first();
		if ($school){
			$app->render('auth/register.php', [
				'school_id' => $school->id,
				'name' => $school->name,
				'errors' => [],
				]);
		} else {
			$app->render('auth/findschool.php');
		};
		die();
	}
})->name('register');


$app->post('/findschool', $guest(), function() use($app) {
	
	$request = $app->request;
	$postcode = $request->post('postcode');

	$school = $app->school
		->where('postcode', $postcode)
		->first();
	
	if ($school){
		$app->redirect($app->urlFor('register').'/'.$school->id);
		
	} else {
		$app->render('auth/findschool.php');
	};
	
	die();
	
})->name('findschool.post');


$app->post('/register', $guest(), function() use($app) {
	
	$request = $app->request;
	
	$school_id = $request->post('school_id');
	$email = $request->post('email');
	$username = $request->post('username');
	$password = $request->post('password');
	$passwordConfirm = $request->post('password_confirm');
	
	$v = $app->validation;
	
	$v->validate([
		'email' => [$email, 'required|email|uniqueEmail'],
		'username' => [$username, 'required|alnumDash|max(20)|uniqueUsername'],
		'password' => [$password, 'required|min(6)'],
		'password_confirm' => [$passwordConfirm, 'required|matches(password)'],
	]);

	if ($v->passes()){
		
		$identifier = $app->randomLib->generateString(128);
		
		$user = $app->user->create([
			'school_id' => $school_id,
			'email' => $email,
			'username' => $username,
			'password' => $app->hash->password($password),
			'active' => false,
			'active_hash' => $app->hash->hash($identifier),
		]);
		
		$user->permissions()->create(UserPermission::$defaults);

		$result = $app->mail->send(
			$user->email,
			'Thanks for registering.',
			'email/auth/registered.php', ['user' => $user, 'identifier' => $identifier]
			);
			
		$app->flash('global', 'You have been sent a registration email.');
		
		$app->response->redirect($app->urlFor('home'));
	}
	echo 'register.php: validation failed';
	$app->render('auth/register.php', [
		'errors' => $v->errors(),
		'request' => $request,
	]);
	
	
})->name('register.post');