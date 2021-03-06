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

	$subject_id = $app->request()->params('id');
    if (!$subject_id) {
        $app->notFound();
        die();
    }
    $yrgp = $app->request()->params('yrgp');
    if ($yrgp == NULL){
        $yrgp = 11;
    }
    $schoolsubject = new Schoolsubject;
    $subject = $schoolsubject->find($subject_id);
    if (!$subject) {
        $app->notFound();
        die();
        }
    $checklists = new Checklist;
    $checklist = $checklists->where('id', $subject->checklist_id)->first();
    if (!$checklist) {
        $app->notFound();
        die();
    }
    $xml = simplexml_load_file($checklist->filename);
    $checklistLookUpTable = buildLookUpTable($xml);
    $jsonLookUpTable = json_encode(buildJsLookUpTable($xml));

    $plcTable = buildPlcTable($subject_id, $yrgp, $user->school_id);
    $jsonPlcTable = json_encode($plcTable);

    $app->render('admin/displayplctable.php',[
        'subject_id' => $subject_id,
        'subject_name' => $subject->name,
        'checklist_id' => $subject->checklist_id,
        'jsonLookUpTable' => $jsonLookUpTable,
        'checklist' => $checklistLookUpTable,
        'jsonPlcTable' => $jsonPlcTable,
        'plcTable' => $plcTable,
    ]);

	})->name('plctable');


function buildPlcTable($schoolsubject_id, $yrgp, $school_id){

    $classe = new Classe;
    $classes = $classe
        ->where('schoolsubject_id', $schoolsubject_id)
        ->where('year_group',$yrgp)
        ->get();
    $sclasse = new Student_Classe;
    $student = new Student;
    $students = $student
        ->where('school_id', $school_id)
        ->where('year_group',$yrgp)
        ->get();
    $plc = new Plc;
    $plcTable = $plc
        ->where('schoolsubject_id', $schoolsubject_id)
        ->get();

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
            $row['name'] = $stu->last_name.", ".$stu->first_name;
            $splc = $plcTable
                ->where('student_id',$s->student_id)
                ->first();
            if(!$splc){
                $row['plc_id'] = 0;
                $row['ratings'] = "";
            } else{
                $row['plc_id'] = $splc->id;
                $row['ratings'] = $splc->ratings;
            }

            $table[$index] = $row;
            $index += 1;
        }
    }
    return $table;
};