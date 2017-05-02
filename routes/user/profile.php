<?php

$app->get('/user', function() use($app) {
	
	$user_id = $app->request()->params('id');
	$user = $app->user->where('id', $user_id)->first();
	
	if (!$user) {
		$app->notFound();
	}

	$app->render('/user/profile.php', [
		'user' => $user,
	]);
})->name('user.profile');
