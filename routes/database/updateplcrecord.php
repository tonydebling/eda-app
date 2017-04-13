<?php

use Target\Database\Student;
use Target\Database\Plc;
use Target\Database\Checklist;


	
$app->get('/updateplcrecord', function() use($app) {
	
	$plc_id = $app->request()->params('plc_id');
	$ratings = $app->request()->params('ratings');
	$plc = new Plc;
	$plc = $plc->find($plc_id);
	$plc = $plc->update(['ratings' => $ratings]);

})->name('updateplcrecord');

