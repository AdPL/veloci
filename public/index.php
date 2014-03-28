<?php
	require_once '../vendor/autoload.php';

	$app = new \Slim\Slim(array(
		'view' => new \Slim\Views\Twig(),
		'debug' => true,
		'templates.path' => '../templates'
	));

	$view = $app->view();
	$view->parserOptions = array(
		'debug' => true,
		'cache' => '../cache'
	);

	$view->parserExtensions = array(
		new \Slim\Views\TwigExtension(),
	);

	session_cache_limiter(false);
	session_start();

	$app->get('/', function() use ($app) {
		$app->render('principal.html.twig');
	})->name('principal');

	$app->get('/login', function() use ($app) {
		$app->render('login.html.twig');
	})->name('login');

	$app->run();