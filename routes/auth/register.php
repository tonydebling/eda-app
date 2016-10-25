<?php

use Target\User\UserPermission;

$app->get('/register', $guest(), function() use($app) {
	$app->render('auth/register.php');
})->name('register');

$app->post('/register', $guest(), function() use($app) {
	
	$request = $app->request;
	
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