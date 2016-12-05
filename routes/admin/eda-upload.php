<?php

use Target\Database\Template;

$app->get('/eda-upload', function() use($app) {
	$app->render('admin/eda-upload.php');
})->name('eda-upload');

$app->post('/eda-upload', function() use($app) {

	$request = $app->request;
	$fileType = $request->post('fileType');

	if ($fileType == "schools") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$table = csv_to_array($fullFileName);
		$columns = array_keys($table[0]);
		$deletedrows = $app->school->truncate();
		$student = $app->school->insert($table);
		$heading = "School table updated";
		$app->render('admin/displayfile.php', [
			'heading' => $heading,
			'columns' => $columns,
			'table' => $table
		]);
	};

	if ($fileType == "template") {
		$fullFileName = $_FILES["fileToUpload"]["tmp_name"];
		$targetDir = "uploads/templates/";
		$targetFile = $targetDir . basename($_FILES["fileToUpload"]["tmp_name"]);
		$targetFile =substr($targetFile, 0, -strlen(pathinfo($targetFile, PATHINFO_EXTENSION))).'xml';
		echo $targetFile.'<br>';
		
		$uploadOk = 1;
		$uploadMessage = '';
		$uploadFileType = pathinfo($targetFile,PATHINFO_EXTENSION);		
		
		// Check if file already exists
		if (file_exists($targetFile)) {
			$uploadMessage = "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			$uploadMessage = "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if ($uploadFileType != "xml" && $uploadFileType != "tmp") {
			$uploadMessage = "Template must be an xml file.";
			$uploadOk = 0;
		}
		// if everything is ok, try to upload file
		if ($uploadOk == 1) {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				$uploadMessage = "Sorry, there was an error uploading your file.";
				$uploadOK = 0;
			}
		}
		
		$xml = simplexml_load_file($targetFile);
		
		$template = new Template;
		$template->create([
			'filename' => $targetFile,
			'code' => $xml->code,
			'subject' => $xml->subject,
			'board' => $xml->board,
			'paper' => $xml->paper,
			'year' => $xml->year,
			'month' => $xml->month,
			'mpcount' => $xml->mpcount,
		]);

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
		
		die();
	};
	
})->name('eda-upload.post');
