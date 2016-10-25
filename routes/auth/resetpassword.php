<?php

$app->get('/resetpassword', $guest(), function() use($app) {

	$request = $app->request;
	$email = $request->get('email');
	$identifier = $request->get('identifier');
	
	$hashedIdentifier = $app->hash->hash($identifier);

	$user = $app->user->where('email', $email)->first();
		
	if (!$user){
		$app->response->redirect($app->urlFor('home'));
	};

	if(!$user->recover_hash){
		$app->response->redirect($app->urlFor('home'));
	};

	if(!$app->hash->hashCheck($user->recover_hash, $hashedIdentifier)){
		$app->flash('global', 'There was a system problem.');
		$app->response->redirect($app->urlFor('home'));
	}	

	$app->render('auth/resetpassword.php', [
		'email' => $user->email,
		'identifier' => $identifier
	]);

})->name('resetpassword');

$app->post('/resetpassword', $guest(), function() use($app) {

	$request = $app->request;
	$email = $request->get('email');
	$identifier = $request->get('identifier');
	
	$password = $request->post('password');
	$password_confirm = $request->post('password_confirm');
	
	$hashedIdentifier = $app->hash->hash($identifier);

	$user = $app->user->where('email', $email)->first();
		
	if (!$user){
		$app->response->redirect($app->urlFor('home'));
	};

	if(!$user->recover_hash){
		$app->response->redirect($app->urlFor('home'));
	};

	if(!$app->hash->hashCheck($user->recover_hash, $hashedIdentifier)){
		$app->flash('global', 'There was a system problem.');
		$app->response->redirect($app->urlFor('home'));
	}	
	$v = $app->validation;
	
	$v->validate([
		'password' => [$password, 'required|min(6)'],
		'password_confirm' => [$password_confirm, 'required|matches(password)'],
	]);

	if ($v->passes()){
		
		$user->update([
			'password' => $app->hash->password($password),
			'recover_hash' => null,			
		]);
		$app->flash('global', 'Your password has been reset.');
		$app->response->redirect($app->urlFor('home'));		
	}
	
	$app->render('auth/resetpassword.php',[
		'erors' => $v->errors()
	]);

})->name('resetpassword.post');
