<?php

	/**
		RackSpace Web Hook - http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/service-notification-types-crud.html


		JSON Reponsese
		--------------

		Status = 
					OK = It's all groovey baby!
					Auth = Authentication Failed, WebHook not in DB
					Fail = Post could not be decoded (i.e. Failed JSON Decode)
					Inval = Post has Empty Token as is not a "Test" Post.
					Error = Post could not be stored in Database
					Fatal = Something went really wrong!
					NoData = No RackSpace Header.

		Msg = Human Readable message for ^above^ status codes. 	


	*/

	require_once("../libs/setup.php"); // Libs.

	/**

		We will respond to all requests with a JSON response :)

	**/

	function json_respond($status, $msg) {

		if ( $status != "OK" ) { // Log Failures.
			
			$logmsg = "RXALARM - POSTFAIL - " . $status . " - " . $msg;
			error_log($logmsg, 0);
		
		}

		$output = array ('status'=>$status,'msg'=> $msg);
		output_json($output);

	}

	/**

		Start by checking the HTTP Header.

	**/

	if (isset($_SERVER['HTTP_X_RACKSPACE_WEBHOOK_TOKEN'])) {

		$post = file_get_contents("php://input"); // POST DATA
		$post = json_decode($post); // Decode what has been posted.

		if (is_null($post)) { // JSON Decode Failed... not a valid post.

			$status = "Fail";
			$msg = "Invalid Submission";
			json_respond($status, $msg);

		}

		if ($_SERVER['HTTP_X_RACKSPACE_WEBHOOK_TOKEN'] == "null") { // WebHook Test Posts.

			if($post->event_id == "test_check") {

				$go = true;
				$owner = "-1";

			} else {

				// Empty Header, JSON Post decoded ok... but fields are wrong.

				$status = "Inval";
				$msg = "Incorrect Header / Post Combination";
				json_respond($status, $msg);

			}

		} else { // WebHook "Real" Posts

			$owner = preg_replace("/[^a-zA-Z0-9]/", "", $_SERVER['HTTP_X_RACKSPACE_WEBHOOK_TOKEN']); // Remove any nasty stuff
			$owner = $db->escape($owner);

			$user_account = $db->get_row("SELECT * FROM user WHERE rs_wh = \"$owner\" ");

			if ($user_account) {

				$go = true;
				$owner = $user_account->id;

			} else {

				// DB Lookup Failed.

				$status = "Auth";
				$msg = "Authentication Failed - " . $owner;
				json_respond($status, $msg);

			}

		}


		if ($go) { // passed ^above^ checks, time to write to DB.

			$headers = getHeaders();
			$headers = serialize($headers);
			$headers = $db->escape($headers);

		
			$post = serialize($post);
			$post = $db->escape($post);
		
			$saved = $db->query("INSERT INTO alarms (id, post, headers, owner) VALUES (NULL,\"$post\", \"$headers\", \"$owner\")");

			if ($saved) {

				$status = "OK";
				$msg = $db->insert_id . ": Alarm Saved.";
				json_respond($status, $msg);

			} else {

				$status = "Error";
				$msg = "Database Error, failed to save post.";
				json_respond($status, $msg);

			}

		} else {

			// ^Above^ checks failed, user ends up here.

			$status = "Fatal";
			$msg = "Fatal Error Occurred, it all went wrong!";
			json_respond($status, $msg);

		}
		

	}  else {

	/**
	
		Rackspace Header was not found, end.

	**/	

		$status = "NoData";
		$msg = "Error: No Data Received.";
		json_respond($status, $msg);

		
	}

?>