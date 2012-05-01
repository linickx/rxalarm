<?php

	/**

		User Log Out :)

	**/

	setcookie("rxalarm[uid]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[at]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	setcookie("rxalarm[auth]", "", time() - 3600, "/", $_SERVER["HTTP_HOST"], 1);
	header("Location: $www");

?>