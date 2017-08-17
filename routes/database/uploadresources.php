<?php

use Target\Database\Resource;

$app->post('/uploadresources', function() use($app) {

    $user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
    $fullFileName = $_FILES["fileToUpload"]["tmp_name"];
    $table = csv_to_array($fullFileName);
    $columns = array_keys($table[0]);
    foreach ($table as $key => $csm) {
        $table[$key]['uploader_id'] = $user->id;
    }
	$resource = new Resource;
    $resources = $resource->insert($table);

})->name('uploadresources');

