<?php 

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Mailgun\Mailgun;

use Noodlehaus\Config;
use RandomLib\Factory as RandomLib;

use Target\User\User;
use Target\Database\School;
use Target\Database\Student;
use Target\Database\Set;
use Target\Database\Studentset;
use Target\Helpers\Hash;
use Target\Mail\Mailer;
use Target\Validation\Validator;
use Target\Middleware\BeforeMiddleware;
use Target\Middleware\CsrfMiddleware;

session_cache_limiter(false);
session_start();

ini_set('display_errors', 'On');

define('INC_ROOT', dirname(__DIR__));

require INC_ROOT . '/vendor/autoload.php';

$app = new Slim([
	'mode' => trim(file_get_contents(INC_ROOT . '/app/mode.php')),
	'view' => new Twig(),
	'templates.path' => INC_ROOT . '/app/views'
]);

$app->add(new BeforeMiddleware);
$app->add(new CsrfMiddleware);

$app->configureMode($app->config('mode'), function() use($app) {
	$app->config = Config::load(INC_ROOT . "/app/config/{$app->mode}.php");
});

require 'database.php';
require 'filters.php';
require 'routes.php';

$app->auth = false;

$app->container->set('user', function() {
	return new User;
});

$app->container->set('school', function() {
	return new School;
});

$app->container->set('student', function() {
	return new Student;
});

$app->container->set('set', function() {
	return new Set;
});

$app->container->set('studentset', function() {
	return new Studentset;
});

$app->container->singleton('hash', function() use($app){
	return new Hash($app->config);
});

$app->container->singleton('validation', function() use($app){
	return new Validator($app->user, $app->hash, $app->auth);
});

$app->container->singleton('mail', function() use ($app){
	$mgClient = new Mailgun($app->config->get('mail.secret'));	
	return new Mailer($app->view, $app->config, $mgClient);
});

$app->container->singleton('randomLib', function() {
	$factory = new RandomLib;	
	return $factory->getMediumStrengthGenerator();
});

$view = $app->view();

$view->parserOptions = [
	'debug' => $app->config->get('twig.debug')
];

$view->parserExtensions = [
	new TwigExtension
];


