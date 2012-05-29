<?php
	/**

		Data (Ajax-json-thing) - Account / Audit.

	*/

	$CacheAuditKey = $user['uid'] . "_aud"; // Users audit history

	if (!($CacheAudits = apc_fetch($CacheAuditKey))) { // audit cache

		require_once("../libs/console_data_apikey.php"); // bootstap the API.

		$Url = "audits";
		$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
		$Response = json_decode($JsonResponse);
		apc_store($CacheAuditKey, $Response, "3600");
		$CacheAudits = $Response;

	}

	// array for output
	$output = array("aaData" => array());

	//
	// $output['aaData'][] = array("Time", "Action", "Status", "Url", "Payload");
	//

	foreach ($CacheAudits->values as $audit) {

		$time = $audit->timestamp;
		$time = $time / 1000;
		$alarmtz = date_default_timezone_set($user['rackspace']['timezone']);

		if(!$alarmtz) { // if invalid Tz in database... use the default.
			date_default_timezone_set("Europe/London");
		}
		$time = date('Y-m-d H:i:s', $time);

		switch ($audit->method) {
			case 'PUT':
				$action = "Update";
				break;
			case 'DELETE':
				$action = "Delete";
				break;
			case 'POST':
				$action = "New";
				break;		
				default:
				$action = "Read";
		}

		if ($audit->statusCode < 400) {
			$status = "OK";
		} else {
			$status = "FAIL";
		}

		$url = $audit->url;

		$payload = '<pre>' . $audit->payload . '</pre>';	 

		$output['aaData'][] = array($time, $action, $status, $url, $payload);
	}

	// done!
	output_json($output);
?>