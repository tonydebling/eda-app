<?php

$app->get('/h/:user_id', function($user_id) use($app) {
	
	$user = $app->user->where('id', $user_id)->first();
	
	if (!$user) {
		$app->notFound();
	}

	$app->render('/user/home.php', [
		'user' => $user,
	]);
	
})->name('user.home');
