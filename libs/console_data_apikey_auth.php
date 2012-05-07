<?php
	/**

		Lib for Caching & Creating Authentication Object.

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

	$CacheAuthKey = $user['uid'] . "_rsauth"; // Store Data in a user unique key

	if (!($Auth = apc_fetch($CacheAuthKey))) {

		$Auth = new RackAuth($RSUID,$RSAPI,$RSLOC);
		$Auth->auth();

		$AuthError = Request::getLastError();
		#error_log($AuthError, 0);

		apc_store($CacheAuthKey, $Auth, "43200"); // cache auth for 12hrs (http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/general-api-info-authentication.html#token-expiration)

		#error_log("==[FRESH AUTH]==", 0);

	} 

?>