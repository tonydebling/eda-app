<?php

use Target\Database\Checkpoint;
use Target\Database\Testpoint;

$app->get('/sandbox2', function() use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	$testpoint = new Testpoint;
	$testpoints = $testpoint
		->where('school_id', $user->school_id)
		->where('year_group',11)
		->get();
	$table = $testpoints->toArray();

	$columns = array_keys($table[0]);
	$app->render('admin/displayfile.php', [
		'heading' => "Testpoints",
		'columns' => $columns,
		'table' => $table
	]);

	})->name('sandbox2');
