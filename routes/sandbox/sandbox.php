<?php

use Mailgun\Mailgun;

$app->get('/sandbox', function() use($app) {

	# Instantiate the client.
	$mgClient = new Mailgun('key-58e14f14956e8761e6b320f0c063fb16');
	$domain = "https://api.mailgun.net/v3/msg.edanalytics.co.uk";
	# Make the call to the client.
	$result = $mgClient->sendMessage("$domain",
					  array('from'    => 'noreply@msg.edanalytics.co.uk',
							'to'      => 'gladysdebling@gmail.com',
							'subject' => 'Hello Tony Debling',
							'text'    => 'Here is a different text to send with no reply sender.'));
	if ($result){
		$app->flash('global','Email sent!');
	} else {
		$app->flash('global','Email failed :( !');
	}
	$app->response->redirect($app->urlFor('home'));

	})->name('sandbox');
