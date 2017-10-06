<?php

use Target\Database\Student;
use Target\Database\Plc;
use Target\Database\Checklist;

$app->get('/plc', function() use($app) {

	$user = $app->user->where('id',$_SESSION[$app->config->get('auth.session')])->first();
	
	$plc_id = $app->request()->params('id');
	
	$plcs = new Plc;
	$plc = $plcs->find($plc_id);
	if (!$plc) {
		$app->notFound();
		die();
	}

	$student = $plc->student;
	$checklist =$plc->checklist;
	$xml = simplexml_load_file($checklist->filename);
	$checklistLookUpTable = buildLookUpTable($xml);
	
	if ($plc->ratings == null){
		$blank = array_fill(0,count($checklistLookUpTable),"0");
		$plc->update(['ratings' => implode("",$blank),]);
	};
	$jsonLookUpTable = json_encode(buildJsLookUpTable($xml));
//    $jsonLookUpTable = json_encode($checklistLookUpTable);
	$app->render('admin/displayplc.php', [
		'plc_id' => $plc_id,
		'checklist' => $checklistLookUpTable,
		'jsonLookUpTable' => $jsonLookUpTable,
		'ratings' => $plc->ratings,
	]);
	
	die();

	})->name('plc');
	
	function buildLookUpTable($xml){

		$lookUpTable = [];
		$lookUpTable[0] = [
			'id' => 0,
			'nodetype' => 'r',
            'depth' => 0,
			'text' => (string)$xml->subject,
            'rank' => 0,
		];
		$line = 1;
		foreach ($xml->units->children() as $unit){
			$lookUpTable[$line] = [
				'id' => $line,
				'nodetype' => 'u',
                'depth' => 1,
				'text' => (string)$unit->name,
                'rank' => 0,
			];
			$line += 1;
			foreach ($unit->topics->topic as $topic){
				$lookUpTable[$line] = [
					'id' => $line,
					'nodetype' => 't',
                    'depth' => 2,
					'text' => (string)$topic->name,
				];
				$line += 1;
				foreach ($topic->checks->check as $check){
						$lookUpTable[$line] = [
							'id' => $line,
							'nodetype' => 'c',
                            'depth' => 3,
							'text' => (string)$check->text,
							'rank' => $check->rank,
						];
						$line += 1;
					};
			};
		};
		return $lookUpTable;
	};


function buildJsLookUpTable($xml){

    $lookUpTable = [];
    $lookUpTable[0] = [
        'id' => 0,
        'nodetype' => 'r',
        'depth' => 0,
        'rank' => 0,
        'parent' => -1,
        'child' => 1,
        'next' => -1,
    ];
    $line = 1;
    $unitLine = -1;
    foreach ($xml->units->children() as $unit){
        $lookUpTable[$line] = [
            'id' => $line,
            'nodetype' => 'u',
            'depth' => 1,
            'rank' => 0,
            'parent' => 0,
            'child' => $line + 1,
            'previous' => $unitLine,
            'next' => -1,
        ];
        if ($lookUpTable[$line]['previous'] <> -1){
            $lookUpTable[$lookUpTable[$line]['previous']]['next'] = $line;
        }
        $unitLine = $line;
        $line += 1;
        $topicLine = -1;
        foreach ($unit->topics->topic as $topic){
            $lookUpTable[$line] = [
                'id' => $line,
                'nodetype' => 't',
                'depth' => 2,
                'parent' => $unitLine,
                'child' => $line + 1,
                'previous' => $topicLine,
                'next' => -1,
            ];
            if ($lookUpTable[$line]['previous'] <> -1){
                $lookUpTable[$lookUpTable[$line]['previous']]['next'] = $line;
            }
            $topicLine = $line;
            $line += 1;
            $checkLine = -1;
            foreach ($topic->checks->check as $check){
                $lookUpTable[$line] = [
                    'id' => $line,
                    'nodetype' => 'c',
                    'depth' => 3,
                    'rank' => $check->rank,
                    'search' => (string)$check->search,
                    'parent' => $topicLine,
                    'child' => -1,
                    'previous' => $checkLine,
                    'next' => -1,
                ];
                if ($lookUpTable[$line]['previous'] <> -1){
                    $lookUpTable[$lookUpTable[$line]['previous']]['next'] = $line;
                }
                $checkLine = $line;
                $line += 1;
            };
        };
    };
    return $lookUpTable;
};

	function buildJsLookUpTableX($xml){

		$jsLookUpTable = [];
		$jsLookUpTable[0] = [
			'nodetype' => 'r',
			'parent' => -1,
		];
		$line = 1;
		foreach ($xml->units->children() as $unit){
			$jsLookUpTable[$line] = [
				'nodetype' => 'u',
				'parent' => 0,
			];
			$unitLine = $line;
			$line += 1;
			foreach ($unit->topics->topic as $topic){
				$jsLookUpTable[$line] = [
					'nodetype' => 't',
					'parent' => $unitLine,					
				];
				$topicLine = $line;
				$line += 1;
				foreach ($topic->checks->check as $check){
						$jsLookUpTable[$line] = [
							'nodetype' => 'c',
							'rank' => $check->rank,
							'parent' => $topicLine,
						];
						$line += 1;
					};
			};
		};
		return $jsLookUpTable;
	};
	
/*
$app->post('/plc', function() use($app) {
	
	$request = $app->request;
	$plc_id = $request->post('plc_id');
	$ratings = $request->post('ratings');
	echo $plc_id;
	echo 'ratings:';
	echo $ratings;
	die();
	
})->name('plc.post');
*/
