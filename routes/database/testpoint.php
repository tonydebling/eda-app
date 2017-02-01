<?php

use Target\Database\Student;
use Target\Database\Schoolsubject;
use Target\Database\Classe;
use Target\Database\Student_Classe;
use Target\Database\Testpoint;
use Target\Database\Testresult;
use Target\Database\Template;

$app->get('/testpoint(/:testpoint_id)', function($testpoint_id = 0) use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();	
	// First section displays list of testpoints if id is not given
	if ($testpoint_id == 0){
		
		$testpoint = new Testpoint;
		$testpoints = $testpoint
			->where('school_id', $user->school_id)
			->where('year_group',11)
			->get();
		$testpoints = $testpoints->sortBy('schoolsubject_id');
		$table = $testpoints->toArray();

		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => "Testpoints",
			'columns' => $columns,
			'table' => $table
		]);
	}
	// If testpoint_id is specified then list the classes, students and results
	else {

		$testpoint = new Testpoint;
		$here = $testpoint->find($testpoint_id);
		if (!$here) {
			$app->notFound();
			die();
		}
		$subject = $here->schoolsubject->name;
		$heading = 'Test Results '.$subject;
		
		$classe = new Classe;
		$classes = $classe
			->where('schoolsubject_id', $here->schoolsubject->id)
			->where('year_group',11)
			->get();
		$sclasse = new Student_Classe;
		$student = new Student;
		$students = $student
			->where('school_id', $user->school_id)
			->where('year_group',11)
			->get();
		$testresults = new Testresult;
		$results = $testresults
			->where('testpoint_id', $testpoint_id)
			->get();
		

		$table = [];
		$key = 0;
		// Build header and max marks row
		$row['school_classe_id'] = '';
		$row['id'] = '';
		$row['first_name'] = 'Max';
		$row['last_name'] = 'Marks';
		$row['UMS'] = '100';
		$row['total'] = '100';

		if ($here->template_id != 0){
			$template = new Template;
			$template = $template->where('id', $here->template_id)->first();
			if (!$template) {
				$app->notFound();
				die();
			}
			$xml = simplexml_load_file($template->filename);
			
			foreach($xml->testbody->mp as $mp){
				$i = array((string) $mp->q); // convert from simpleXml to string
				$row[$i[0]] = $mp->marks;
			};
		};
		$table[$key] = $row;
		$key +=1;
		
		foreach ($classes as $class){
			$ss = $sclasse
				->where('classe_id',$class->id)
				->get();
			foreach ($ss as $s){
				$row = [];
				$row['school_classe_id'] = $s->school_classe_id;
				$stu = $students
					->where('id',$s->student_id)
					->first();
				$res = $results
					->where('student_id',$s->student_id)
					->first();
				$row['id'] = $stu['id'];
				$row['first_name'] = $stu['first_name'];
				$row['last_name'] = $stu['last_name'];
				$marks = explode(',',$res['marks']);
				$k = 0;
				$total = 0;
				foreach($xml->testbody->mp as $mp){
					$i = array((string) $mp->q);
					if (isset($marks[$k])){
						$total = $total + $marks[$k];
						$row[$i[0]] = $marks[$k];
						if ($marks[$k] == $mp->marks){
							$row[$i[0]] = '<td class ="w3-light-green">'.$marks[$k].'</td>';
						} elseif ($marks[$k] == 0){
							$row[$i[0]] = '<td class ="w3-pale-red">'.$marks[$k].'</td>';
						} else{
							$row[$i[0]] = '<td class ="w3-amber">'.$marks[$k].'</td>';
						};
					} else {
						$row[$i[0]] = '<td class ="w3-pale-red"> </td>';
					};
					$k += 1;
				};
				$row['total'] = $total;
				$table[$key] = $row;
				$key += 1;
			}
		}
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	}

	})->name('testpoint');
