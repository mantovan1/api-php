<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	try {
	
		session_start();

		if(!empty($_SESSION['userName'])) {

            		$author = $_SESSION['userName'];
            		$date = time();

			$layer = $_GET['layer'];
			$parentid = $_GET['parentid'];
			$text = $_GET['text'];

			$db = (new MongoDB\Client('mongodb://localhost:27017'))->communityapp;

			$parentdata = NULL;
			$myobj = NULL;

			$parentcollection = NULL;
			$collection = NULL;

			$insertOneResult = NULL;

			if($layer == 1) {
				$collection = $db->comments;
				$parentcollection = $db->threads;
			
				$parentdata = $parentcollection->findOne(['_id' => new MongoDB\BSON\ObjectId($parentid)]);
				$myobj = ['author' => $author, 'date' => $date, 'level' => 'layer#1', 'parntid' => $parentid, 'childs' => array(), 'text' => $text];
			}
			else if ($layer == 2) {
				$collection = $db->subcomments;
				$parentcollection = $db->comments;
			
				$parentdata = $parentcollection->findOne(['_id' => new MongoDB\BSON\ObjectId($parentid)]);
				$myobj = ['author' => $author, 'date' => $date, 'level' => 'layer#2', 'parntid' => $parentid, 'text' => $text];
			} else {
				echo json_encode(['message' => 'give a valid number for the layer (1 or 2)']);
				http_response_code(200);
				die();
			}

			if(empty($parentdata)) {
	                	echo json_encode(['message' => 'parent object does not exist, please give an existing object id']);
				http_response_code(200);
				die();
			} else {
                        	$insertOneResult = $collection->insertOne($myobj);
                 	}


			if(sizeof($parentdata['childs']) == 0) {
				$parentcollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($parentid)], ['$set' => ['childs' => [new MongoDB\BSON\ObjectId($insertOneResult->getInsertedId())]]]);
			} else {
				$parentcollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($parentid)], ['$set' => ['childs' => [... $parentdata['childs'], new MongoDB\BSON\ObjectId ($insertOneResult->getInsertedId())] ]]);
			}	


			http_response_code(201);		
			echo json_encode( ['message' => 'comment created', 'id' => $insertOneResult->getInsertedId(), 'author' => $myobj['author'], 'date' => $myobj['date'], 'level' => $myobj['level'], 'parentid' => $parentid, 'text' => $myobj['text'] ]);
		} else {
			header('Location: http://192.168.15.152:80/api/index.php?path=temp-user/join');
			http_response_code(307);
			die();
		}
	
	} catch (Exception $e) {
		echo json_encode(['message' => $e->getMessage()]);
		http_response_code(500);
		die();
	}

