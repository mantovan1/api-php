<?php

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	try {

		session_start();

		if(!empty($_SESSION['userName'])) {

			$author = $_SESSION['userName'];
			$date = time();
			
			$title = $_GET['title'];
			$text = $_GET['text'];

			$myobj = ['author' => $author, 'date' => $date, 'level' => 'parent', 'childs' => array(), 'title' => $title, 'text' => $text];

                	$collection = (new MongoDB\Client('mongodb://localhost:27017'))->communityapp->threads;

                	$insertOneResult = $collection->insertOne($myobj);

			if($insertOneResult->getInsertedCount() == 1) {
				http_response_code(201);
				echo json_encode(['message' => 'Thread created', ['id' => $insertOneResult->getInsertedId(), 'author' => $myobj['author'], 'date' => $myobj['date'], 'level' => $myobj['level'], 'title' => $myobj['title'], 'text' => $myobj['text']]]);
			} else {
				http_response_code(200);
                		echo json_encode(['message' => 'Something went wrong']);
			}	
		} else {
			http_response_code(307);
			header('Location', 'http:192.168.15.152:80/api/index.php?path=temp-user/join');
			die();
		}	

	} catch (Exception $e) {
		http_response_code(500);
                echo json_encode(['message' => $e->getMessage()]);
        }
