<?php

	/**

		Delete Cookie Script

	**/

	setcookie("rxalarm[rsuid]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsapi]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsloc]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);

	/**

		Delete Cached rackspace API Session

	**/
	
	if (isset($_COOKIE['rxalarm']['uid'])) {
		$uid = preg_replace("/[^0-9]/", "", $_COOKIE['rxalarm']['uid']);
		$CacheAuthKey = $uid . "_rsauth"; // Users's Cached rackspace session
		apc_delete($CacheAuthKey); // delete!
	}
	

	/**

		User Log Out if Rackspace API Only

	**/

	if ($url[2] == "rs") { // Delete API Cookies Only
		header("Location: $www");
		die();
	}

	/**

		Delete All Cookies, User gets logged out of App :)

	**/

	setcookie("rxalarm[uid]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[at]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[auth]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	header("Location: $www");

?>