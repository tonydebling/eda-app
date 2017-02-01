<?php

$app->get('/browse', function() use($app) {
	$base = $app->config->get('app.url');
	$url = $base.$app->urlFor('getdata');
	var_dump($url);
	$app->render('database/browse.php',[
		'url' => $url,
	]);
})->name('browse');
