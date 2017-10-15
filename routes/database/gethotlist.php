<?php

use Target\Database\Plc;

$app->get('/gethotlist', function() use($app) {

	$plc_id = $app->request()->params('id');

	$hotList = buildHotList($plc_id);
	$jsonHotList = json_encode($hotList);
    echo $jsonHotList;
    exit();

	})->name('gethotlist');

function buildHotList($plc_id){
    $plcs = new Plc;
    $plc = $plcs->find($plc_id);
    if (!$plc) {
        return [];
    }
    $checklist =$plc->checklist;
    $xml = simplexml_load_file($checklist->filename);
    $hotList = [];
    $hotListIndex = 0;
    $line = 1;
    foreach ($xml->units->children() as $unit){
        $line += 1;
        foreach ($unit->topics->topic as $topic){
            $line += 1;
            foreach ($topic->checks->check as $check){
                if ($plc->ratings[$line] > 3){
                    // This one is hot!
                    $hotList[$hotListIndex] = [
                        'index' => $hotListIndex,
                        'line' => $line,
                        'plc_id' => $plc_id,
                        'rank' => $check->rank,
                        'text' => (string)$check->text,
                        'search' => (string)$check->search,
                        'rating' => $plc->ratings[$line],
                    ];
                    $hotListIndex += 1;
                }
                $line += 1;
            };
        };
    };
    return $hotList;
};
