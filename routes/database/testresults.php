<?php

use Target\Database\Testresult;

$app->get('/testresults', function() use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	$student_id = $app->request()->params('id');
    $student = $app->student->where('id',$student_id)->first();
	
    $resultList = buildResultList($app, $student);
    $jsonRestultList = json_encode($resultList);

    $columns = array_keys($resultList[0]);
    $app->render('admin/displayfile.php', [
        'heading' => "Results List",
        'columns' => $columns,
        'table' => $resultList,
    ]);
	
	die();

	})->name('testresults');

function buildResultList($app, $student){

    $testResult = $student->testresult;
    $k = 0;
    $list = [];
    foreach ($testResult as $r){

        $list[$k]['id'] ='<td><a href='.$app->urlFor('testresult').'?id='.$r->id.'>'.$r->id.'</td>';
        $list[$k]['chktricode'] = $r->testpoint->checkpoint->tricode;
        $list[$k]['subject'] = $r->testpoint->schoolsubject->name;
        $list[$k]['ums'] = $r->ums;
        $list[$k]['total'] = $r->total;
        $list[$k]['grade'] = $r->grade;
        $list[$k]['chkname'] = $r->testpoint->checkpoint->name;
        $list[$k]['template_id'] = $r->testpoint->template_id;
        $list[$k]['type'] = $r->testpoint->test_type;
        $list[$k]['weight'] = $r->testpoint->weighting;
        $list[$k]['index'] = $k;

        $k += 1;
    }
    return $list;
}