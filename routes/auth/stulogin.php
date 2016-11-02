<?php

$app->get('/stulogin(/:urn)', $guest(), function($urn = 0) use($app) {
	if ($urn == 0){
		$app->render('auth/stufindschool.php');
	} else {
		echo "Yay, we got to the next part";
		echo $urn;
		die();
	}
})->name('stulogin');

$app->post('/stufindschool', $guest(), function() use($app) {
	
	$request = $app->request;
	$postcode = $request->post('postcode');
	
/*	Convert the postcode to the URN
*/
	$urn = 100000;
	$name = "Wellsmart Academy";

	$app->render('auth/stulogin.php', [
		'urn' => $urn,
		'name' => $name,
		'errors' => [],
		'request' => $request,
		]);
	die();
	
})->name('stufindschool.post');

$app->post('/stulogin', $guest(), function() use($app) {
	
	$request = $app->request;
	$urn = $request->post('urn');	
	$first_name = $request->post('first_name');
	$last_name = $request->post('last_name');	
	$date_of_birth = $request->post('date_of_birth');


	$v = $app->validation;
	
	$v->validate([
		'urn' => [$urn, 'required'],
		'first_name' => [$first_name, 'required'],
		'last_name' => [$last_name, 'required'],
		'date_of_birth' => [$date_of_birth, 'required'],
	]);
	echo "Student login validation completed";
	die();
/*
	if ($v->passes()){
		$user = $app->user
			->where('username', $identifier)
			->orWhere('email', $identifier)
			->first();
			
		if ($user && $app->hash->passwordCheck($password, $user->password)){
			$_SESSION[$app->config->get('auth.session')] = $user->id;
			$app->flash('global','You are now signed in');
			$app->response->redirect($app->urlFor('home'));
		} else {
			$app->flash('global','Could not log you in!');
			$app->response->redirect($app->urlFor('login'));
		}	
	}
	
	$app->render('auth/login.php', [
		'errors' => $v->errors(),
		'request' => $request,
	]);	
*/
})->name('stulogin.post');
