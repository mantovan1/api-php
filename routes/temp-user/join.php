<?php

	header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        date_default_timezone_set('America/Sao_Paulo');

	session_start();

	if(empty($_SESSION['userName'])) {

		$_SESSION['userName'] = 'random_name@' . uniqid();
		$_SESSION['date_of_creation'] = time();

		$_SESSION['date_of_expiration'] = $_SESSION['date_of_creation'] + 12 * 60 * 60 * 1000;

		$date_of_creation = new DateTime($_SESSION['date_of_creation']);
		$date_of_expiration = new DateTime($_SESSION['date_of_expiration']);

		echo json_encode(['message' => 'temp user created, you can use this account for the next 12 hours. After it will be deleted', 'userName' => $_SESSION['userName']]);	
	}  else {
		echo json_encode(['message' => 'tem user already created', 'username' => $_SESSION['userName'], 'date_of_creation' => $_SESSION['date_of_creation'], 'date_of_expiration' => $_SESSION['date_of_expiration']]);
	}

	http_response_code(200);
