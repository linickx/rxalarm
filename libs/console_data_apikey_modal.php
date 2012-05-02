<?php
	/**
	
		Modal Data Response - Test a given API Key, and set a cookie.

	**/

	/**

	Get the API key from the modal, and test that it works.

	**/

	require_once("../libs/console_data_apikey.php");

	/**

	Lib ^above^ didn't bomb-out... we are good to go!

	**/

	setcookie("rxalarm[rsuid]", $RSUID, time() + 2592000, "/", $_SERVER["HTTP_HOST"], 1); 
	setcookie("rxalarm[rsapi]", $RSAPI, time() + 2592000, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsloc]", $RSLOC, time() + 2592000, "/", $_SERVER["HTTP_HOST"], 1);

	$res = 'ok';
	$msg = '<div class="alert alert-success"><strong>Success!</strong><br />API Key Works.</div><p><a href="#" class="btn" data-dismiss="modal">Close</a></p>';

	rsEND($res, $msg); // Happy!
?>