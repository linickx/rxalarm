<?php
	/**

		Lib for Caching & Creating Authentication Object.

	**/

	if (isset($_REQUEST['username'])) { 
		
		if ($_REQUEST['username'] == "") { // catch blanks early

			$res = 'error';
			$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oh dear, you forgot to supply a username :(</div>';

			rsEND($res, $msg); 

		} else {
			$RSUID = $_REQUEST['username'];
		}

	} else { // cookie fallback (if _POST failed or not required.)
		$RSUID = $_COOKIE['rxalarm']['rsuid'];
	}

	if (isset($_REQUEST['apikey'])) {

		if ($_REQUEST['apikey'] == "") {

			$res = 'error';
			$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Doh! Passwords cannot be blank!</div>';

			rsEND($res, $msg); 

		} else {
			$RSAPI = $_REQUEST['apikey'];
		}

	} else {
		$RSAPI = $_COOKIE['rxalarm']['rsapi'];
	}
	
	if (!isset($_REQUEST['location'])) { // no blank capture required, as the default is US-of-A.
		$RSLOC = $_COOKIE['rxalarm']['rsloc'];
	} else {
		$RSLOC = $_REQUEST['location'];
	}

	$CacheAuthKey = $user['uid'] . "_rsauth"; // Store Data in a user unique key

	if (!($Auth = apc_fetch($CacheAuthKey))) {

		$Auth = new RackAuth($RSUID,$RSAPI,$RSLOC);
		$Auth->auth();

		$LastCode = Request::getLastHTTPCode();

		$AuthError = Request::getLastError();
		#error_log($AuthError, 0);

		if ($LastCode<304) {

			apc_store($CacheAuthKey, $Auth, "43200"); // cache auth for 12hrs (http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/general-api-info-authentication.html#token-expiration)

		} 

		#error_log("==[FRESH AUTH]==", 0);

	} 

?>