<?php

use Target\Database\Checkpoint;
use Target\Database\Testpoint;
use Target\Database\Testresult;
use Target\Database\Template;

$app->get('/uploadfile', function() use($app) {
	$app->render('admin/uploadfile.php');
})->name('uploadfile');

$app->post('/uploadfile', function() use($app) {

	$request = $app->request;
	$fileType = $request->post('fileType');
	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();

	// STUDENTS
	if ($fileType == "students") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		// Add a school_id column
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
	// SUBJECTS
	if ($fileType == "subjects") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		// Add a school_id column
		foreach ($table as $key => $csm){
			$table[$key]['school_id'] = $user->school_id;
		}

		$deletedrows = $app->subject
			->where('school_id', $user->school_id)
			->delete();
		
		$student = $app->subject->insert($table);

		$heading = "Subject table updated";
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};
	// CLASSES
	if ($fileType == "classes") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);

		// Build table to convert subject to id
		$subjects = $app->subject
			->where('school_id', $user->school_id)
			->get();
		$subjectTranslate = [];
		foreach ($subjects as $subject){
			$subjectTranslate += [$subject->name => $subject->id];
		};

		foreach ($table as $key => $csm){
			$table[$key]['school_id'] = $user->school_id;
			$table[$key]['schoolsubject_id'] = $subjectTranslate[$table[$key]['subject']];
			unset($table[$key]['subject']);
		}

		$deletedrows = $app->classe
			->where('school_id', $user->school_id)
			->delete();
		
		$student = $app->classe->insert($table);

		$heading = "Classes";		
		$columns = array_keys($table[0]);	
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
		
	};
	// STUDENT CLASSES
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
		} else {
			var_dump($nomatch);
			die();
		};
		
		foreach ($table as $key => $csm){
			$table[$key]['classe_id'] = $classeIdTranslate[$table[$key]['school_classe_id']];
			$table[$key]['student_id'] = $studentIdTranslate[$table[$key]['school_student_id']];
			$table[$key]['school_id'] = $user->school_id;
		};

		$deletedrows = $app->student_classe
			->where('school_id', $user->school_id)
			->delete();
/*		
		var_dump($table);
		die();
*/		
		$student = $app->student_classe->insert($table);

		$columns = array_keys($table[0]);
		$heading = "Student classes updated";	
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);

	};
	
	// CHECKPOINTS
	if ($fileType == "checkpoints") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		// Add a school_id column
		foreach ($table as $key => $csm){
			$table[$key]['school_id'] = $user->school_id;
		}

		$checkpoint = new Checkpoint;
		$checkpoints = $checkpoint
			->where('school_id', $user->school_id)
			->delete();
		
		$checkpoint->insert($table);

		$heading = "Checkpoints";
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};
	
	// TESTPOINTS
	if ($fileType == "testpoints") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		
		// Build table to convert checkpoint tricode to checkpoint id
		$checkpoint = new Checkpoint;
		$checkpoints = $checkpoint
			->where('school_id', $user->school_id)
			->get();
		$checkpointTricodeTranslate = [];
		foreach ($checkpoints as $checkpoint){
			$checkpointTricodeTranslate += [$checkpoint->tricode => $checkpoint->id];
		};

		// Build table to convert subject to id
		$subjects = $app->subject
			->where('school_id', $user->school_id)
			->get();
		$subjectTranslate = [];
		foreach ($subjects as $subject){
			$subjectTranslate += [$subject->name => $subject->id];
		};

		// Fix up the input table
		foreach ($table as $key => $csm){
			$table[$key]['school_id'] = $user->school_id;
			$table[$key]['checkpoint_id'] = $checkpointTricodeTranslate[$table[$key]['cp_tricode']];
			unset($table[$key]['cp_tricode']);
			$table[$key]['schoolsubject_id'] = $subjectTranslate[$table[$key]['subject']];
			unset($table[$key]['subject']);
		};
		// Remove existing testpoints
		$testpoint = new Testpoint;
		$testpoints = $testpoint
			->where('school_id', $user->school_id)
			->delete();

		// Insert new ones
		$testpoint->insert($table);
		
		$testpoints = $testpoint
			->where('school_id', $user->school_id)
			->get();
		$table = $testpoints->toArray();
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => "Testpoints",
			'columns' => $columns,
			'table' => $table
		]);
	};
	
	// TESTRESULTS
	if ($fileType == "testresults") {
		
		$testpoint_id = $request->post('testpoint_id');
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		
		$testpoint = new Testpoint;
		$here = $testpoint->find($testpoint_id);
		if (!$here) {
			$app->notFound();
			die(); // Need a better exit than this...
		}
		
		if ($here->template_id != 0){
			// Results have associated Template file
			$template = new Template;
			$template = $template->where('id', $here->template_id)->first();
			if (!$template) {
				$app->notFound();
				die();
			}
			$xml = simplexml_load_file($template->filename);
			
			$markpoints = $xml->testbody->mp;
			
			// Set up a string which shows what no marks entered looks like
			$nomarks = '';
			$count = $markpoints->count();
			$count = $count - 1;
			for ($i = 1; $i <= $count; $i++){
				$nomarks = $nomarks.',';
			}
			
			$testresult = new Testresult;
			$results_table = [];
			$k = 0;
			foreach($table as $key => $csm){
				$result = [];
				$result['testpoint_id'] = $testpoint_id;
				$result['student_id'] = $table[$key]['id'];
				$total = 0;
				$marks = [];
				foreach($markpoints as $mp){
					$i = array((string) $mp->q)[0];
					$i = trim($i);
					$marks[] =  $table[$key][$i];
					$total += $table[$key][$i];
				};
				$result['total'] = $total;
				$result['marks'] = implode(',',$marks);
				
				// Only include results with a student_id and at least one non-empty mark
				if (($result['student_id'] != null)&& ($result['marks'] != $nomarks)){
					$tr = $testresult
						->updateOrCreate($result);
					$results_table[$k] = $result;
					$k += 1;
				};
			};
			
		} else {
			// Results do not have associated Template file
			$results_table = [];
			$k = 0;
			foreach($table as $key => $csm){
				$result = [];
				$result['testpoint_id'] = $testpoint_id;
				$result['student_id'] = $table[$key]['id'];
				$result['ums'] = $ums;
				$result['total'] = $total;
				$result['marks'] = '';
				$results_table[$k] = $result;
				$k += 1;
			};

		};
		$columns = array_keys($results_table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => "Testresults",
			'columns' => $columns,
			'table' => $results_table,
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
