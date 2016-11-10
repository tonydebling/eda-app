<?php

use Target\User\UserPermission;

$app->get('/uploadfile', function() use($app) {
	$app->render('admin/uploadfile.php');
})->name('uploadfile');

$app->post('/uploadfile', function() use($app) {

	$request = $app->request;
	$fileType = $request->post('fileType');
	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	
	if ($fileType == "students") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);

		foreach ($table as $key => $csm){
			$table[$key]['school_id'] = $user->school_id;
		}

		$deletedrows = $app->student->truncate();
		$student = $app->student->insert($table);

		$heading = "Student table updated";
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);

	};

	if ($fileType == "sets") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		$columns = array_keys($table[0]);		
		$deletedrows = $app->set->truncate();
		$student = $app->set->insert($table);
		$heading = "Student table updated";
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};

	if ($fileType == "studentsets") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		$columns = array_keys($table[0]);

		$deletedrows = $app->studentset->truncate();
		$student = $app->studentset->insert($table);

		$heading = "Student classes updated";	
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};

})->name('uploadfile.post');

/*
 * @author Jay Williams <http://myd3.com/>
 * @copyright Copyright (c) 2010, Jay Williams
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
*/
function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	return $data;
}
