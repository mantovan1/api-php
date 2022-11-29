<?php

	require_once __DIR__ . '/vendor/autoload.php';

	//phpinfo();

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	if(isset($_GET['path'])) {
		$path = explode('/', $_GET['path']);
	} else {
		echo json_encode(['api_name' => 'api community', 'year_of_creation' => '2022', 'see_more' => 'https://github.com/mantovan1']);
	}

	$valid_paths = array('temp-user', 'thread');

	if(isset($path[0]) && isset($path[1])) {

		switch($path[0]) {
			case 'thread':
				if($path[1] == 'resume') {
					include_once __DIR__ . '/routes/thread/resume.php';
				}
				elseif($path[1] == 'search') {
					if(isset($path[2])) {
						$_GET['threadid'] = $path[2];
	
						include_once __DIR__ . '/routes/thread/search.php';
					}
				}
				elseif($path[1] == 'create') {
					if(isset($path[2]) && isset($path[3])) {
						
						$_GET['title'] = $path[2];
						$_GET['text'] = $path[3];
	
						 include_once __DIR__ . '/routes/thread/create.php';
					}
				}
				elseif($path[1] == 'comment') {
					if(isset($path[2]) && isset($path[3]) && isset($path[4])) {
						$_GET['layer'] = $path[2];
						$_GET['parentid'] = $path[3];
						$_GET['text'] = $path[4];
	
						//echo $_GET['layer'];
						//echo $_GET['parentid'];
						//echo $_GET['text'];
	
						include_once __DIR__ . '/routes/thread/comment.php';
					}
				}
				else {
					echo json_encode(['message' => 'invalid action']);
					http_response_code(400);
				}
				
				break;

			case 'temp-user':
				if($path[1] == 'join') {
					include_once __DIR__ . '/routes/temp-user/join.php';
				}
				elseif ($path[1] == 'quit') {
					include_once __DIR__ . '/routes/temp-user/quit.php';
				}
				else {
					echo json_encode(['message' => 'invalid action']);
					http_response_code(400);
				}
			
				break;
			
			default:
				echo json_encode(['message' => 'invalid path']);
				http_response_code(400);	

		}
	}	
