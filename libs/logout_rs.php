<?php
	/**

		Log out / delete rackspace API Cookies.

	**/

	setcookie("rxalarm[rsuid]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsapi]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[rsloc]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	header("Location: $www");
	
?>