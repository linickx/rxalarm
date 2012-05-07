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

	if (!isset($_REQUEST['username'])) { // cookie fallback (if _POST failed or not required.)
		$RSUID = $_COOKIE['rxalarm']['rsuid'];
	} else {
		$RSUID = $_REQUEST['username'];
	}

	if (!isset($_REQUEST['apikey'])) {
		$RSAPI = $_COOKIE['rxalarm']['rsapi'];
	} else {
		$RSAPI = $_REQUEST['apikey'];
	}
	
	if (!isset($_REQUEST['location'])) {
		$RSLOC = $_COOKIE['rxalarm']['rsloc'];
	} else {
		$RSLOC = $_REQUEST['location'];
	}

	$Auth = new RackAuth($RSUID,$RSAPI,$RSLOC);
	$Auth->auth();

	$AuthError = Request::getLastError();
	#error_log($AuthError, 0);

	if ($AuthError != "") {

		$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>';

		rsEND($res, $msg); // No XAuthToken was given therefore... Authentication Failed!

	}
	
	#echo '<pre>';
	#print_r($Auth);
	#echo '</pre><hr />';
?>