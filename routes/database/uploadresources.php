<?php

use Target\Database\Resource;

$app->post('/uploadresources', function() use($app) {

    $user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
    $fullFileName = $_FILES["fileToUpload"]["tmp_name"];
    $table = csv_to_array($fullFileName);
    foreach ($table as $key => $csm) {
        $table[$key]['uploader_id'] = $user->id;
    }
	$resource = new Resource;
    try{
        $resources = $resource->insert($table);
    } catch(\Illuminate\Database\QueryException $ex) {
        die($ex->getMessage());
    }


})->name('uploadresources');

