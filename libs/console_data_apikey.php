<?php
	/**
	
		Library: API Save & Test.

	**/

	require_once("../libs/rackcloudmanager.php"); // include the rackspace lib

	function rsEND($res, $msg) { // Msg & Die.

		$output = array ('response'=>$res,'msg'=> $msg);
		output_json($output);
	}

	/**
	
		Start Here.

	**/

	require_once("../libs/console_data_apikey_auth.php"); // Authenticate with RS

	$LastCode = Request::getLastHTTPCode();

	if ($LastCode>=304) {

		$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct? <br />' . $LastCode . ': ' . $AuthError . '</div>';

		rsEND($res, $msg); // No XAuthToken was given therefore... Authentication Failed!

	}
?>