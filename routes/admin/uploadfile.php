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

		$deletedrows = $app->student
			->where('school_id', $user->school_id)
			->delete();
		
		$student = $app->student->insert($table);

		$heading = "Student table updated";
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);

	};

	if ($fileType == "classes") {
		echo 'hey ho';
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		$columns = array_keys($table[0]);
		echo 'got here';
	
		foreach ($table as $key => $csm){
			$table[$key]['school_id'] = $user->school_id;
		}

		$deletedrows = $app->classe
			->where('school_id', $user->school_id)
			->delete();
		
		$student = $app->classe->insert($table);
	
		$heading = "Set lists updated";
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};

	if ($fileType == "student_classes") {
		
		$students = $app->student
			->where('school_id', $user->school_id)
			->get();
		$studentIdTranslate = [];
		foreach ($students as $student){
			$studentIdTranslate += [$student->school_student_id => $student->id];
		};

		$classes = $app->classe
			->where('school_id', $user->school_id)
			->get();
		$classeIdTranslate = [];
		foreach ($classes as $classe){
			$classeIdTranslate += [$classe->school_classe_id => $classe->id];
		};
		
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		
		$nomatch =[];
		foreach ($table as $key => $csm){
			if (isset($studentIdTranslate[$table[$key]['school_student_id']])==false){
				$nomatch += $table[$key];
				unset($table[$key]);
			}
		};
		if($nomatch == []){
			echo 'Yay it all matches';
		};
		
		foreach ($table as $key => $csm){
			$table[$key]['classe_id'] = $classeIdTranslate[$table[$key]['school_classe_id']];
			$table[$key]['student_id'] = $studentIdTranslate[$table[$key]['school_student_id']];
			$table[$key]['school_id'] = $user->school_id;
		};

		$deletedrows = $app->student_classe
			->where('school_id', $user->school_id)
			->delete();
		
		$student = $app->student_classe->insert($table);

		$columns = array_keys($table[0]);
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
