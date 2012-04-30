<?php

	/**
		
		It all starts here!
	
	*/

	require_once("../libs/setup.php"); // ready, setup, go!

	$url = explode("/", $_SERVER['REQUEST_URI']); // split our URL, to find what to do.

	#echo '<pre>';
	#print_r($url);
	#echo '</pre>';

	// There's probably a better way, but this will do for now :)

	switch ($url[1]) {
		case "console":
			if ($url[2] == "demo") {
				require_once("../libs/console_demo.php"); // Demo Console.
				break;
			}
			require_once("../libs/console.php");	// user logged in / console.
			break;
		case "help":
			require_once("../libs/help.php"); // Help Page.
			break;
		case "contact":
			require_once("../libs/contact.php"); // Contact Page
			break;
		case "account":
			require_once("../libs/account.php");	// User Account Mgnt
			break;	
		case "logout":
			require_once("../libs/logout.php");		// ummm.. logout ;-)
			break;	
		default:
			require_once("../libs/homepage.php");	// default, homepage.
	}

?>