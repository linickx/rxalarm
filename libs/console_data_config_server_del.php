<?php
	/**

	/Console -> Config Tab -> Server -> Delete

	**/

	$entityid = $_REQUEST['entityid'];

	require_once("../libs/console_data_apikey.php"); // bootstap the API.

	$Url = "entities/" . trim($entityid);

	$JsonResponse = Request::postAuthenticatedDeleteRequest($Url,$Auth);

    $Response = json_decode($JsonResponse);

    $LastCode = Request::getLastHTTPCode();

    if ($LastCode == "204") {

    	$CacheSvrsKey = $user['uid'] . "_ent"; // Cached Entities
    	apc_delete($CacheSvrsKey); // purge the cache (to show the changes at next refresh)
    	
    	$res = 'ok';
		$msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button><strong>Delete Sucessful!</strong><br />Your server (<em>entity</em>) has been deleted.</div>';

		$ok = '';

	} else {

		$RSError = Request::getLastError();
		$RSErrorCode = "$LastCode";

		$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. - Delete Failed. ($RSErrorCode: $RSError)</div>';
    }

    $output = array ('response'=>$res,'msg'=> $msg,'ok'=>$ok);
	output_json($output);


	?>