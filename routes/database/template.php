<?php

use Target\Database\Template;

$app->get('/template(/:template_id)', function($template_id = 0) use($app) {
	
	// First section displays list of templates if id is not given
	if ($template_id == 0){
		$templates = new Template;
		$templates = $templates->get();
		$key = 0;
		$table= [];
		foreach ($templates as $template){
			$table[$key]['id'] = $template->id;
			$table[$key]['board'] = $template->board;
			$table[$key]['code'] = $template->code;
			$table[$key]['subject'] = $template->subject;
			$table[$key]['year'] = $template->year;
			$table[$key]['month'] = $template->month;
			$table[$key]['paper'] = '<td><a href='.$app->urlFor('template').'/'.$template->id.'>'.$template->paper.'</td>';
			$key += 1;
		}
		$columns = array_keys($table[0]);
		$app->render('admin/displayfile.php', [
			'heading' => 'Template Listing',
			'columns' => $columns,
			'table' => $table,	
		]);

	// This section displays template when id is specified	
	} else {	
		$template = new Template;
		$template = $template->where('id', $template_id)->first();
		if (!$template) {
			$app->notFound();
			die();
		}
		
		$xml = simplexml_load_file($template->filename);

		$idTable =[];
		$idTable[0] = ['Code' => $xml->code, 'Board' => $xml->board, 
			'Subject' => $xml->subject, 'Year' => $xml->year, 'Month' => $xml->month];
		
		$mpTable = [];
		$key = 0;
		foreach($xml->testbody->mp as $mp){
			$mpTable[$key] = [
				'Line' => $key + 1,
				'Qn' => $mp->q,
				'Description' => $mp->text,
				'Marks' => $mp->marks,
				'Cat' => $mp['category'],
				'Skill' => $mp['skill'],
			];
			$key += 1;
		};
		
		$gbTable = [];
		$key = 0;
		foreach($xml->gradeboundaries->gb as $gb){
			$gbTable[$key] = [
				'Grade' => $gb->grade,
				'Marks' => $gb->rawmark,
				'UMS' => $gb->ums,
			];
			$key += 1;			
		};	

		$catTable = [];
		$key = 0;
		foreach($xml->categories->category as $cat){
			$catTable[$key] = [
				'Code' => $cat->code,
				'Category' => $cat->text,
			];
			$key += 1;			
		};

		$skTable = [];
		$key = 0;
		foreach($xml->skills->skill as $sk){
			$skTable[$key] = [
				'Code' => $sk->code,
				'Skill' => $sk->text,
			];
			$key += 1;			
		};
		
		$heading = 'Template Listing';
		$idBlock = [
			'heading' => 'Identification',
			'table' => $idTable,
			'columns' => array_keys($idTable[0]),
		];
		$mpBlock = [
			'heading' => 'Questions',
			'table' => $mpTable,
			'columns' => array_keys($mpTable[0]),		
		];
		$gbBlock = [
			'heading' => 'Grade Boundaries',
			'table' => $gbTable,
			'columns' => array_keys($gbTable[0]),		
		];
		
		$catBlock = [
			'heading' => 'Categories',
			'table' => $catTable,
			'columns' => array_keys($catTable[0]),		
		];

		$skBlock = [
			'heading' => 'Skills',
			'table' => $skTable,
			'columns' => array_keys($skTable[0]),		
		];	
		
		$app->render('admin/displayblocks.php', [
			'heading' => $xml->paper,
			'blocks' => [$idBlock, $mpBlock, $gbBlock, $catBlock, $skBlock],
		]);
	}
	die();

})->name('template');
