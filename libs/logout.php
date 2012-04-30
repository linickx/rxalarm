<?php

	/**

		User Log Out :)

	**/

	setcookie("rxalarm[uid]", "", time() - 3600);
	setcookie("rxalarm[at]", "", time() - 3600);
	setcookie("rxalarm[auth]", "", time() - 3600);
	header("Location: $www");

?>