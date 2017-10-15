<?php

use Target\Database\Plc;

$app->get('/hotlists', function() use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	$student_id = $app->request()->params('id');
    $student = $app->student->where('id',$student_id)->first();
	
    $subjectList = buildSubjectList($student);
    $jsonSubjectList = json_encode($subjectList);

    $columns = array_keys($subjectList[0]);
    $app->render('admin/displayhotlists.php', [
        'subjectlist' => $subjectList,
        'jsonsubjectlist' => $jsonSubjectList,
    ]);
	
	die();

	})->name('hotlists');

function buildSubjectList($student){

    $classes = $student->classes;
    $plc = new Plc;
    $k = 0;
    $subjectList = [];
    foreach ($classes as $classe){
        $subjectList[$k]['index'] = $k;
        $subjectList[$k]['id'] = $classe->schoolsubject_id;
        $subjectList[$k]['name'] = $classe->schoolsubject->name;
        $subjectplc = $plc->where([
            ['student_id', $student->id],
            ['schoolsubject_id', $classe->schoolsubject_id],
        ])->first();
        if ($subjectplc){
            $subjectList[$k]['plc_id'] = $subjectplc->id;
            $subjectList[$k]['ratings'] = $subjectplc->ratings;
            $subjectList[$k]['hotlist'] = buildHotList($subjectplc->id);
        } else {
            $subjectList[$k]['plc_id'] = null;
            $subjectList[$k]['ratings'] = "";
            $subjectList[$k]['hotlist'] = [];
        }
        $k += 1;
    }
    return $subjectList;
}