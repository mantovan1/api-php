<?php

	header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

        try {

        $threadid = $_GET['threadid'];

		$collection = (new MongoDB\Client('mongodb://localhost:27017'))->communityapp->threads;

        $data = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($threadid)]);

		echo json_encode(['threads' => $data]);
		http_response_code(200);	

	} catch (Exception $e) {
		http_response_code(500);
                echo json_encode(['message' => $e->getMessage()];
        }
