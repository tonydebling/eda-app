
<?php

$app->get('/login', $guest(), function() use($app) {
	
	$school_id = $app->request()->params('id');
	if ($school_id == NULL){
		$base = $app->config->get('app.url');
		$returnUrl = $base.$app->urlFor('login');
		$app->render('database/findschool.php', [
			'returnUrl' => $returnUrl,
		]);
	} else {
		$school = $app->school->where('id',$school_id)->first();
		if ($school){
			$app->render('auth/login.php', [
				'school_id' => $school->id,
				'name' => $school->name,
				'school_domain' => $school->domain,
				'errors' => [],
				]);
		} else {
			$app->flash('global', 'School not found with this id');
			$base = $app->config->get('app.url');
			$returnUrl = $base.$app->urlFor('login');
			$app->render('database/findschool.php',[
                'returnUrl' => $returnUrl,
		        ]);
		};
		die();
	}	

})->name('login');

$app->post('/login', $guest(), function() use($app) {
	
	$request = $app->request;

	$identifier = $request->post('identifier');
	$password = $request->post('password');
	$school_id = $request->post('school_id');
	$name = $request->post('name');
	$school_domain = $request->post('school_domain');

	$v = $app->validation;
	
	$v->validate([
		'identifier' => [$identifier, 'required'],
		'password' => [$password, 'required'],
	]);
	
	if ($v->passes()){
		$user = $app->user
			->where('username', $identifier)
			->orWhere('email', $identifier)
			->first();
	
		if ($user && $app->hash->passwordCheck($password, $user->password)){
			$_SESSION[$app->config->get('auth.session')] = $user->id;
			$app->flash('global','You are now signed in');
			$app->redirect($app->urlFor('user.home')."?id=".$user->id);
		} else {
			$app->flash('global','Could not log you in!');
			$app->response->redirect($app->urlFor('login'));
		}	
	}
	
	$app->render('auth/login.php', [
		'school_id' => $school_id,
		'name' => $name,
		'school_domain' => $school_domain,
		'errors' => $v->errors(),
		'request' => $request,
	]);	
	
})->name('login.post');