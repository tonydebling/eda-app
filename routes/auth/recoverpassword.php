<?php

use Target\User\UserPermission;

$app->get('/recoverpassword', $guest(), function() use($app) {
	$app->render('auth/recoverpassword.php');
})->name('recoverpassword');

$app->post('/recoverpassword', $guest(), function() use($app) {
	
	$request = $app->request;
	
	$email = $request->post('email');

	$v = $app->validation;
	
	$v->validate([
		'email' => [$email, 'required|email|existingEmail'],
	]);
	
	if ($v->passes()){
		
		$user = $app->user->where('email',$email)->first();
		
		$identifier = $app->randomLib->generateString(128);		

		$user->update([
			'recover_hash' => $app->hash->hash($identifier)
		]);

		$result = $app->mail->send(
			$user->email,
			'Recover your password.',
			'email/auth/recoverpassword.php', ['user' => $user, 'identifier' => $identifier]
			);
		
		$app->flash('global', 'You have been sent reset instructions.');
		
		$app->response->redirect($app->urlFor('home'));
	}

	$app->render('auth/recoverpassword.php', [
		'errors' => $v->errors(),
		'request' => $request,
	]);
	
	
})->name('recoverpassword.post');