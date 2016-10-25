<?php 

return [
	'app'=> [
		'url' => 'http://localhost',
		'hash' => [
			'algo' => PASSWORD_BCRYPT,
			'cost' => 10
		]
	],
	'db' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'name' => 'target',
		'username' => 'root',
		'password' => 'root',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => ''
	],
	'auth' => [
		'session' => 'user_id',
		'remember' => 'user_r'
	],
	'mail' => [
		'secret' => 'key-58e14f14956e8761e6b320f0c063fb16',
		'domain' => 'https://api.mailgun.net/v3/msg.edanalytics.co.uk',
		'from' => 'noreply@msg.edananalytics.co.uk'
	],
	'csrf' => [
		'key' => 'csrf_token'
	]
];
	