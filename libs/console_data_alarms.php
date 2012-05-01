<?php
	/**

		Data (Ajax-json-thing) - Alarms List for Console.

	*/

	$output = array("aaData" => array());

//	$output['aaData'][] = array("1", "OK", "Hosta", "OK");
//	$output['aaData'][] = array("2", "WARNING", "Hostb", "dunno");
//	$output['aaData'][] = array("3", "CRITICAL", "Hostc", "Broken!");

	if ($user['rs_wh'] == "new") { // is the user a new user?

		/** 

			Fake Data for New Users

		**/
		$time = "";
		$state = "Pending";
		$label = "WebHook";
		$mz = "";
		$checktype = "Pending";
		$status = "Webhook";

		$output['aaData'][] = array($time, $state, $label, $mz, $checktype, $status);
		

	}  else { // Nope not new user, get data.

		/**

			Real Data.

		**/

		$owner = $user['uid'];

		$results = $db->get_results("SELECT * from alarms WHERE owner = \"$owner\"");

		if (!$results) { // No Data.
			$output['aaData'][] = array("", "No", "Data", "", "No", "Data");
			output_json($output);
		}
		
		foreach ($results as $row) {

			$alarm = unserialize(stripslashes($row->post));

			if ($alarm->event_id == "test_check") {

				// Test checks have different fields.

				// Currently "tests" do not have a webhook - this is for the furure - I plan to ask RackSpace to attach webhooks to tests :)

				$time = "";
				$state = $alarm->details->state;
				$label = "test_check";
				$mz = "test monitoring zone";
				$checktype = "n/a";
				$status = $alarm->details->status;

			} else {

				// Real alarms.

				// Fix the timestamp for PHP use.

				$time = $alarm->details->timestamp;
				$time = $time / 1000;
				$alarmtz = date_default_timezone_set($user['rackspace']['timezone']);

				if(!$alarmtz) { // if invalid Tz in database... use the default.
					date_default_timezone_set("Europe/London");
				}

				// Create a list of monitoring zones from array.

				$mzs = $alarm->check->monitoring_zones_poll;
				$mzcounter = 0;

				$mz = "";

				foreach ($mzs as $i) {
					$mz .= $i;

					if ($mzcounter > 0) {
						$mz .= ", ";
					}

					$mzcounter++;
				}

				// set variables for Json.
				
				$time = date('Y-m-d H:i:s', $time); 
				$state = $alarm->details->state;
				$label = $alarm->entity->label;
				#$mz
				$checktype = $alarm->check->type;
				$status = $alarm->details->status;

			}

			// Output ready for Json.
			$output['aaData'][] = array($time, $state, $label, $mz, $checktype, $status);

		}
	}

	output_json($output);
?>