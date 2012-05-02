<?php

	/**

		Delete Cookie Script

	**/

	setcookie("rxalarm[rsuid]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsapi]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsloc]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);


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