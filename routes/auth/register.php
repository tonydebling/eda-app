<?php

use Target\Database\Student;
use Target\Database\Staffmember;
use Target\User\UserPermission;

$app->get('/register', $guest(), function() use($app) {

	$school_id = $app->request()->params('id');
	if ($school_id == NULL){
		$base = $app->config->get('app.url');
		$returnUrl = $base.$app->urlFor('register');
		$app->render('auth/findschool.php', [
			'returnUrl' => $returnUrl,
		]);
	} else {
		// School id provided as URL argument
		echo('School id parameter:'); echo($school_id);
		$school = $app->school->where('id',$school_id)->first();
		if ($school){
			$app->render('auth/register.php', [
				'school_id' => $school->id,
				'school_domain' => $school->domain,
				'name' => $school->name,
				'errors' => [],
				]);
		} else {
		    // It should never happen that the id passed in the URL does not exist
			$app->flash('global', 'School not found with this id');
			$base = $app->config->get('app.url');
			$returnUrl = $base.$app->urlFor('register');
			$app->render('auth/findschool.php', [
                'returnUrl' => $returnUrl,
                ]);
		};
		die();
	}

})->name('register');


$app->post('/register', $guest(), function() use($app) {
	
	$request = $app->request;
	
	$school_id = $request->post('school_id');
	$name = $request->post('name');
	$school_domain = $request->post('school_domain');
	$username = $request->post('username');
	/*
	The next section allows the user to enter an email address into the username slot
	which will be passed through as the email address, without adding the @schooldomain to it
	*/
	if (strpos($username,'@')){
		$email = $username;
		$username = substr($username, 0, strpos($username,'@'));
	} else {
		$email = $username.'@'.$school_domain;
	}
	$password = $request->post('password');
	$passwordConfirm = $request->post('password_confirm');
	
	$v = $app->validation;
	
	$v->validate([
		'email' => [$email, 'required|email|uniqueEmail'],
		'username' => [$username, 'required|alnumDash|max(20)'],
		'password' => [$password, 'required|min(6)'],
		'password_confirm' => [$passwordConfirm, 'required|matches(password)'],
	]);

	if ($v->passes()){
	    $is_student = False;
	    $student_id = null;
	    $is_staffmember = False;
	    $staffmember_id = null;
	    $first_name = null;
	    $last_name = null;
	    $student = new Student;
	    $thisStudent = $student
            ->where('email', $email)
            ->first();
	    if ($thisStudent <> null){
	        $is_student = True;
	        $student_id = $thisStudent->id;
	        $first_name = $thisStudent->first_name;
	        $last_name = $thisStudent->last_name;
        } else {
	        $staffmember = new Staffmember;
            $thisStaffmember = $staffmember
                ->where('email', $email)
                ->first();
            if ($thisStaffmember <> null) {
                $is_staffmember = True;
                $staffmember_id = $thisStaffmember->id;
                $first_name = $thisStaffmember->first_name;
                $last_name = $thisStaffmember->last_name;
            }
        }
		$identifier = $app->randomLib->generateString(128);
		$user = $app->user->create([
			'school_id' => $school_id,
			'email' => $email,
			'username' => $username,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'is_student' => $is_student,
			'student_id' => $student_id,
			'is_staffmember' => $is_staffmember,
			'staffmember_id' => $staffmember_id,
			'password' => $app->hash->password($password),
			'active' => false,
			'active_hash' => $app->hash->hash($identifier),
		]);
		
		$user->permissions()->create(UserPermission::$defaults);
		
		$base = $app->config->get('app.url');
		if ($base == 'http://localhost'){
			// On development platform, simply mark the account as activated
			$user->activateAccount();
			$app->flash('global', 'Your account has been activated and you can sign in.');
			$url = $app->urlFor('login').'?id='.$school_id;
			echo($url);
			$app->redirect($url);
			die();
		} else {
			// On live site send a registration email to confirm identity of user
			$result = $app->mail->send(
				$user->email,
				'Thanks for registering.',
				'email/auth/registered.php', ['user' => $user, 'identifier' => $identifier]
				);
			if ($result){
                $app->flash('global', 'You have been sent a registration email.');
            } else {
                $app->flash('global', 'Registration email failed to be sent.');
            }
			$app->redirect($app->urlFor('home'));
			echo('Redirected to home');
			die();
			}
	}
	$app->flash('global', 'register.php: validation failed');
	$app->render('auth/register.php', [
		'school_id' => $school_id,
		'school_domain' => $school_domain,
		'name' => $name,
		'errors' => $v->errors(),
		'request' => $request,
	]);
	
	
})->name('register.post');