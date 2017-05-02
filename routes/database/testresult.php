<?php

use Target\Database\Student;
use Target\Database\Schoolsubject;
use Target\Database\Classe;
use Target\Database\Student_Classe;
use Target\Database\Testpoint;
use Target\Database\Testresult;
use Target\Database\Template;

$app->get('/testresult', function() use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	$testresult_id = $app->request()->params('id');
	if (!$testresult_id) {
		$app->notFound();
		die();
	};
	$testresults = new Testresult;
	$result = $testresults->find($testresult_id);
	$student = $result->student;
	$testpoint =$result->testpoint;

	
	$heading = $student->first_name. ' '. $student->last_name;	
	$idtable = [];
	$idrow['Checkpoint'] = $testpoint->checkpoint->tricode;
	$idrow['Name'] = $student->first_name.' '.$student->last_name;
	$idrow['Subject'] = $testpoint->schoolsubject->name;
	$idrow['Grade'] = $result->grade;
	$idrow['Marks'] = $result->total;
	$idrow['UMS'] = $result->ums;
	
	$idtable[0] = $idrow;
	$idBlock = [
		'heading' => '',
		'columns' => array_keys($idtable[0]),
		'table' => $idtable,
	];
	
	if ($testpoint->template_id == 0){
		$app->render('admin/displayblocks.php', [
			'heading' => $heading,
			'blocks' => [$idBlock,],
		]);		
	};
	
	$template = new Template;
	$template = $template->where('id', $testpoint->template_id)->first();
	if (!$template) {
		$app->notFound();
		die();
	}
	// Build paper description
	$qrow = $template->toArray();
	
	$qtable[0] = $qrow;

	$pBlock = [
		'heading' => 'Test paper',
		'table' => $qtable,
		'columns' => array_keys($qrow),
	];
	// Load template file
	$xml = simplexml_load_file($template->filename);

	$total = 0;
	$mark = explode(',',$result->marks);
	foreach ($mark as $m){
		$total += $m;
	};
	$gbTable = [];
	$key = 0;
	$grade = '';
	$gradeKey = 0;

	foreach($xml->gradeboundaries->gb as $gb){
		$gbTable[$key] = [
			'Grade' => $gb->grade,
			'Marks' => $gb->rawmark,
			'UMS' => $gb->ums,
		];
		if ($total >= $gb->rawmark){
			$grade = $gb->grade;
			$gradeKey = $key;
		};
		$key += 1;			
	};

	$marksMax = 0;
	foreach($xml->testbody->mp as $mp){
		$marksMax += $mp->marks;
	};
	
	// These variables are needed for colour-coding the Question analysis
	// ... and calculating the UMS score
	$marksForNext = 0;
	$marksForNextPlusOne = 0;
	if ($gradeKey == $key - 1){
		// Already has top grade
		$marksForNext = 0;
		$marksForNextPlusOne = 0;
		$gradeWidth = $marksMax - $gbTable[$gradeKey]['Marks'];
		$umsWidth = 100 - $gbTable[$gradeKey]['UMS'];
	} elseif ($gradeKey == $key - 2){
		// Only one more grade to go
		$marksForNext = $gbTable[$gradeKey + 1]['Marks'] - $total;
		$marksForNextPlusOne = 0;
		$gradeWidth = $gbTable[$gradeKey + 1]['Marks'] - $gbTable[$gradeKey]['Marks'];
		$umsWidth = $gbTable[$gradeKey + 1]['UMS'] - $gbTable[$gradeKey]['UMS'];
	} else {
		// Could improve by two grades
		$marksForNext = $gbTable[$gradeKey + 1]['Marks'] - $total;
		$marksForNextPlusOne = $gbTable[$gradeKey + 2]['Marks'] - $total;
		$gradeWidth = $gbTable[$gradeKey + 1]['Marks'] - $gbTable[$gradeKey]['Marks'];
		$umsWidth = $gbTable[$gradeKey + 1]['UMS'] - $gbTable[$gradeKey]['UMS'];
	};
	$ums = $gbTable[$gradeKey]['UMS'];
	if ($gradeWidth != 0){
		$ums += round($marksForNext * $umsWidth / $gradeWidth);
	};
	
	// Check calculated results match those stored in testresult record
	
	if (($result->marks != $total) or ($result->grade != $grade) or ($result->ums != $ums)){
		// If not, patch up display data
		$idBlock['table'][0]['Marks'] = $total;
		$idBlock['table'][0]['Grade'] = $grade;
		$idBlock['table'][0]['UMS'] = $ums;
		// Fix up database
		$result->update([
			'total' => $total,
			'grade' => $grade,
			'ums' => $ums,
		]);
	}
	
	$mtable = [];
	$marksLostSoFar = 0;
	$k = 0;
	
	foreach($xml->testbody->mp as $mp){
		$row['Qn'] = $mp->q;
		$row['Topic'] = $mp->text;
		if (isset($mark[$k])){
			$row['Mark'] = $mark[$k];
		} else {
			$row['Mark'] = '';
		};
		$row['Max'] = $mp->marks;
		$row['Lost'] = $row['Max'] - $row['Mark'];
		$marksLostSoFar += $row['Lost'];
		$topic = $row['Topic'];
		
		if ($row['Mark'] == $row['Max']){
			$row['Topic'] = '<td class = "w3-light-green">'.$topic.'</td>';
		} elseif ($marksLostSoFar <= $marksForNext){
			$row['Topic'] = '<td class = "w3-yellow">'.$topic.'</td>';
		} elseif ($marksLostSoFar <= $marksForNextPlusOne){
			$row['Topic'] = '<td class = "w3-light-blue">'.$topic.'</td>';
		};
		
		$mtable[$k] = $row;
		$k += 1;
	};
	$mBlock = [
		'heading' => 'Analysis by question',
		'columns' => array_keys($mtable[0]),
		'table' => $mtable,
	];
	
	$gbTable = [];
	$gbrow0['Grade'] = 'Boundary';
	$gbrow1['Grade'] = 'UMS';
	foreach($xml->gradeboundaries->gb as $gb){
		$i = array((string) $gb->grade)[0];
		$gbrow0[$i] = $gb->rawmark;
		$gbrow1[$i] = $gb->ums;
	};
	$gbTable[0] = $gbrow0;
	$gbTable[1] = $gbrow1;
	$gBlock = [
		'heading' => 'Grade boundaries',
		'columns' => array_keys($gbrow1),
		'table' => $gbTable,
	];


	$app->render('admin/displayblocks.php', [
		'heading' => $heading,
		'blocks' => [$idBlock, $pBlock, $mBlock,$gBlock],
	]);
	die();

	})->name('testresult');
