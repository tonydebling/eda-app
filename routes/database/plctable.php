<?php

use Target\Database\Student;
use Target\Database\Schoolsubject;
use Target\Database\Classe;
use Target\Database\Student_Classe;
use Target\Database\Checklist;
use Target\Database\Plc;


$app->get('/plctable', function() use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	$school_id = $user->school_id;

	$subject_id = $app->request()->params('subjid');
    if (!$subject_id) {
        $app->notFound();
        die();
    }
    $yrgp = $app->request()->params('yrgp');
    if ($yrgp == NULL){
        $yrgp = 11;}
    $schoolsubject = new Schoolsubject;
    $subject = $schoolsubject->find($subject_id);
    if (!$subject) {
        $app->notFound();
        die();
        }
    $classe = new Classe;
    $classes = $classe
        ->where('schoolsubject_id', $subject_id)
        ->where('year_group',$yrgp)
        ->get();
    $sclasse = new Student_Classe;
    $student = new Student;
    $students = $student
        ->where('school_id', $user->school_id)
        ->where('year_group',$yrgp)
        ->get();

    $plc = new Plc;
    $plcTable = $plc
        ->where('schoolsubject_id', $subject_id)
        ->get();

    $checklists = new Checklist;
    $checklist = $checklists->where('id', $subject->checklist_id)->first();
    if (!$checklist) {
        $app->notFound();
        die();
    }
    $xml = simplexml_load_file($checklist->filename);
    $checklistLookUpTable = buildLookUpTable($xml);
    $blank = array_fill(0,count($checklistLookUpTable),"0");
    $table = [];
    $index = 0;
    foreach ($classes as $class){
        $ss = $sclasse
            ->where('classe_id',$class->id)
            ->get();
        foreach ($ss as $s){
            $row = [];
            $row['index'] = $index;
            $row['class'] = $s->school_classe_id;
            $stu = $students->find($s->student_id);
            $row['student name'] = $stu->last_name.", ".$stu->first_name;
            $splc = $plcTable
                ->where('student_id',$s->student_id)
                ->first();
            if(!$splc){
                // make a new plc record
                $splc = $plc->insert([
                    'student_id' => $s->student_id,
                    'checklist_id' => $subject->checklist_id,
                    'schoolsubject_id' => $subject_id,
                    'ratings' => "",
                ]);
                $splc = $plc
                    ->where('student_id',$s->student_id)
                    ->where('schoolsubject_id', $subject_id)
                    ->first();
            }
            $row['plc'] = $splc->id;
            $row['ratings'] = $splc->ratings;
            $table[$index] = $row;
            $index += 1;
			}
		}

    $heading = 'Learning Checklist: '.$subject->name;
		$columns = array_keys($table[0]);
		$app->render('admin/displaydatatable.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);

	})->name('plctable');
