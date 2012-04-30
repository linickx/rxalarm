<?php

	/**

	Data.php - returns data to the application

	d = data type
		- alarms
		- tab (html content)
		- nua (New User Automagic)
		- num (New User Manual)
		
	i = ID
		- pushed down to console_data_tabs.php

	**/

#print_r($_REQUEST);

require_once("../libs/setup.php");

if ($_REQUEST['d'] == "demoalarms") { // DEMO!

	require_once('../libs/console_data_demoalarms.php');
	exit;
}

	/**

		All Other Pages need Auth!

	**/

authuser();

switch ($_REQUEST['d']) {
		case "alarms":
			require_once('../libs/console_data_alarms.php');	// user's console alarms (Data as JSON Get)
			break;
		case "tab":
			require_once('../libs/console_data_tabs.php'); // tabs (HTML Markup via JSON Get)
			break;
		case "nua":
			require_once('../libs/console_data_newuser_auto.php'); // New user - AutoMagic WebHook (Ajax Post, JSON Response)
			break;
		case "num":
			require_once('../libs/console_data_newuser_man.php'); // New user - Manual WebHook (Ajax Post, JSON Response)
			break;			
		default:
			die('404: Data not found');
	}

?>