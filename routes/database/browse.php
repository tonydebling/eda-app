<?php

$app->get('/browse', function() use($app) {
	$base = $app->config->get('app.url');
	$url = $base.$app->urlFor('uploadresources');
	$app->render('database/browse.php',[
		'uploadUrl' => $url,
	]);
})->name('browse');
