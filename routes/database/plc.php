<?php

use Target\Database\Student;
use Target\Database\Plc;
use Target\Database\Checklist;

$app->get('/plc/:plc_id', function($plc_id) use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	
	$plcs = new Plc;
	$plc = $plcs->find($plc_id);
	if (!$plc) {
		$app->notFound();
		die();
	}
	$student = $plc->student;
	$checklist =$plc->checklist;
	$xml = simplexml_load_file($checklist->filename);
	$unitList = buildUnitList($xml);
	
	
	$app->render('admin/displayplc.php', [
		'subject' => $xml->subject,
		'units' => $unitList,
	]);
	
	die();

	})->name('plc');
	
	function buildUnitList($xml){
		$unitKey = 1;
		$unitList = [];
		$line = 1;
		foreach ($xml->units->children() as $unit){
			$topicKey = 1;
			$topicList = [];
			$unitLine = $line;
			$line += 1;
			foreach ($unit->topics->topic as $topic){
				$checkKey = 1;
				$checkList = [];
				$topicLine = $line;
				$line += 1;
				foreach ($topic->checks->check as $check){
					$checkList[$checkKey] = [
						'text' => $check->text,
						'rank' => $check->rank,
						'sid' => $unitKey.'.'.$topicKey.'.'.$checkKey,
						'line' => $line,
					];
					$checkKey +=1;
					$line += 1;
				}
				$topicList[$topicKey] = [
					'name' => $topic->name,
					'checks' => $checkList,
					'sid' => $unitKey.'.'.$topicKey,
					'line' => $topicLine,
				];
				$topicKey += 1;
			}
			$unitList[$unitKey] = [
				'name' => $unit->name,
				'topics' => $topicList,
				'sid' => $unitKey,
				'line' => $unitLine,
			];
			$unitKey += 1;
		}
		return $unitList;
	};