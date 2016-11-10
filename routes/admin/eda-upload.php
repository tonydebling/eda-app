<?php

use Target\User\UserPermission;

$app->get('/eda-upload', function() use($app) {
	$app->render('admin/eda-upload.php');
})->name('eda-upload');

$app->post('/eda-upload', function() use($app) {

	$request = $app->request;
	$fileType = $request->post('fileType');

	if ($fileType == "schools") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		$columns = array_keys($table[0]);
		$deletedrows = $app->school->truncate();
		$student = $app->school->insert($table);
		$heading = "School table updated";
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};

})->name('eda-upload.post');

/*
 * @author Jay Williams <http://myd3.com/>
 * @copyright Copyright (c) 2010, Jay Williams
 * @license http://www.opensource.org/licenses/mit-license.php MIT License

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
*/
