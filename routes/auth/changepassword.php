<?php

use Target\User\UserPermission;

$app->get('/changepassword', $authenticated(), function() use($app) {
	$app->render('auth/changepassword.php');
})->name('changepassword');

$app->post('/changepassword', $authenticated(), function() use($app) {
	
	$request = $app->request;
	
	$password = $request->post('password');
	$password = $request->post('password');
	$passwordConfirm = $request->post('password_confirm');

	$v = $app->validation;
	
	$v->validate([
		'password_old' => [$password, 'required|matchesCurrentPassword'],
		'password' => [$password, 'required|min(6)'],
		'password_confirm' => [$passwordConfirm, 'required|matches(password)'],
	]);

	if ($v->passes()){
		
		$app->auth->update([
			'password' => $app->hash->password($password),
		]);

		$result = $app->mail->send(
			$app->auth->email,
			'Your password has been changed.',
			'email/auth/changedpassword.php', ['user' => $app->auth]
			);
		$app->flash('global', 'Your password has been updated.');
		
		$app->response->redirect($app->urlFor('home'));
	}
	$app->render('auth/changepassword.php', [
		'errors' => $v->errors()
	]);
	
	
})->name('changepassword.post');