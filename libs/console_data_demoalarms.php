<?php

	/**

		Demo Data - Alarms for Demo Console.

	*/

	$output = array("aaData" => array());

	$results = $db->get_results("SELECT * from alarms WHERE owner = \"-1\"");

	if (!$results) { // No Data.
			$output['aaData'][] = array("", "No", "Data", "", "No", "Data");
			output_json($output);
		}

	foreach ($results as $row) {

			$alarm = unserialize(stripslashes($row->post));

			if ($alarm->event_id == "test_check") {

				// Test checks have different fields.

				// Currently "tests" do not have a webhook - this is for the future - I plan to ask RackSpace to attach webhooks to tests :)

				$time = "";
				$state = $alarm->details->state;
				$label = "test_check";
				$mz = "test monitoring zone";
				$checktype = "n/a";
				$status = $alarm->details->status;

			} 

			// Output ready for Json.
			$output['aaData'][] = array($time, $state, $label, $mz, $checktype, $status);

		}

	output_json($output);	

?>