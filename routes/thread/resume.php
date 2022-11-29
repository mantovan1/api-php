<?php

	header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

        try {

		$collection = (new MongoDB\Client('mongodb://localhost:27017'))->communityapp->threads;

        	$cursor = $collection->find(['level' => 'parent']);

		$stack = array();

		foreach($cursor as $document) {
			array_push($stack, $document);
		}

		echo json_encode(['threads' => $stack]);
		http_response_code(200);

        } catch (Exception $e) {
		echo json_encode(['message' => $e->getMessage()];
		http_response_code(500);
        }
