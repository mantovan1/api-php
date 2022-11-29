<?php

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        date_default_timezone_set('America/Sao_Paulo');

	session_start();
	session_unset();
	session_destroy();

	echo json_encode(['message' => 'temp-user deleted']);
	http_response_code(200);
