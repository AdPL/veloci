<?php

	/*  VELOCI - Web application for management races
  Copyright (C) 2014: Adrián Pérez López

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see [http://www.gnu.org/licenses/]. */

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