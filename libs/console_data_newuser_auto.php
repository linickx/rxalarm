<?php
	/**
		
		Data Response to New User (Automagic) Request - Used to find WebHook

	**/	

	require_once("../libs/rackcloudmanager.php"); // include the rackspace lib

	function rsEND($res, $msg) { // Msg & Die.

		$output = array ('response'=>$res,'msg'=> $msg);
		output_json($output);
	}

	/**
	
		Start Here.

	**/

	$RSUID = $_REQUEST['username'];
	$RSAPI = $_REQUEST['apikey'];
	$RSLOC = $_REQUEST['location'];

	$Auth = new RackAuth($RSUID,$RSAPI,$RSLOC);
	$Auth->auth();

	if ($Auth->getXAuthToken() == "") {

		$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>';

		rsEND($res, $msg); // No XAuthToken was given therefore... Authentication Failed!
	}
	
	#echo '<pre>';
	#print_r($Auth);
	#echo '</pre><hr />';
	
	/**

	Get the webhook token and save to MySQL.

	**/

	$Url = "account";
	$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
	$Response = json_decode($JsonResponse);
	
	#echo '<pre>';
	#print_r($Response);
	#echo '</pre>';

	$rs_wh = $Response->webhook_token;
	$rs_wh = $db->escape($rs_wh); 

	$uid = substr($_COOKIE['rxalarm']['uid'], 0, 256);
	$safeish_uid = $db->escape($uid); 

	$dbupdate = $db->query("UPDATE user SET rs_wh = \"$rs_wh\" WHERE id = \"$safeish_uid\"");

	if ($dbupdate) {

		/**

			Store the API Key in a cookie for a month so that the user doesn't have to keep typing it :)

		**/

		setcookie("rxalarm[rsuid]", $RSUID, time() + 2592000, "/", $_SERVER["HTTP_HOST"], 1); 
		setcookie("rxalarm[rsapi]", $RSAPI, time() + 2592000, "/", $_SERVER["HTTP_HOST"], 1);
		setcookie("rxalarm[rsloc]", $RSLOC, time() + 2592000, "/", $_SERVER["HTTP_HOST"], 1);

		$res = 'ok';
		$msg = '<div class="alert alert-success"><strong>Nice Work!</strong><br />Your WebHook Token has been found, you are all ready to go.</div><p><a href="#" class="btn" data-dismiss="modal">Close</a></p>';

		rsEND($res, $msg); // Happy!

	} else {

		$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Fatal Error!</strong><br />Oops, Something went wrong. Can you try again?</div>';

		rsEND($res, $msg); // DB Error

	}
?>