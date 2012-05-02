<?php

	/**

		User Log Out if Rackspace API Only

	**/

	if ($url[2] == "rs") {
		require_once("../libs/logout_rs.php"); // Delete API Cookies.
		die();
	}

	/**

		User Log Out of App :)

	**/

	setcookie("rxalarm[uid]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[at]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[auth]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	header("Location: $www");

?>