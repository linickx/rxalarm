<?php

	/**

	Data.php - returns data to the application

	d = data type
		- alarms
		- alarmhistory (alarm history)
		- tab (html content)
		- nua (New User Automagic)
		- num (New User Manual)
		- ays (Account / You / Settings)
		- aau (Account / Audit)
		- css (Console/Config -> Server -> Save)
		- csa (Console/Config -> Server -> Add)
		- csd (Console/Config -> Server -> Delete)
		- ccs (Console/Config -> Check -> Save)
		- cca (Console/Config -> Check -> Add)
		- ccd (Console/Config -> Check -> Delete)
		- cnts (Console/Config -> Notification [Type] -> Save)
		- cnta (Console/Config -> Notification [Type] -> Add)
		- cntd (Console/Config -> Notification [Type] -> Delete)
		- cnps (Console/Config -> Notification Plan -> Save) 
		- cnpa (Console/Config -> Notification Plan -> Add)
		- cnpd (Console/Config -> Notification Plan -> Delete)
		- caa (Console/Config -> Alarm -> Add) 
		- cas (Console/Config -> Alarm -> Save) 
		- cad (Console/Config - Alarm -> Delete) 
		
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

# Future feature.
#$who = "rxalarm," . $user['uid'] . "," . $user['twitter']->name; // used for rackspace audit trail.

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
		case "ays":
			require_once('../libs/account_data_you.php'); // Settings from Account (you)
			break;
		case "api":
			require_once('../libs/console_data_apikey_modal.php'); // API Key (Modal)
			break;
		case "css":
			require_once('../libs/console_data_config_server_save.php'); // Update Existing RS Server (Entity)  with new  Details
			break;
		case "csa":
			require_once('../libs/console_data_config_server_add.php'); // Add New Server
			break;
		case "csd":
			require_once('../libs/console_data_config_server_del.php'); // Delete a server / entity
			break;	
		case "ccs":
			require_once('../libs/console_data_config_check_save.php'); // Update Existing RS Check  with new  Details
			break;
		case "cca":
			require_once('../libs/console_data_config_check_add.php'); // Add New Check
			break;
		case "ccd":
			require_once('../libs/console_data_config_check_del.php'); // Delete a check
			break;
		case "cnts":
			require_once('../libs/console_data_config_notifyt_save.php'); // Update / Save Existing Notification (Type)
			break;	
		case "cnta":
			require_once('../libs/console_data_config_notifyt_add.php'); // Add New Notification (Type)
			break;
		case "cntd":
			require_once('../libs/console_data_config_notifyt_del.php'); // Delete Notification (Type)
			break;
		case "cnps":
			require_once('../libs/console_data_config_notifyp_save.php'); // Update / Save Existing Notification Plan
			break;		
		case "cnpa":
			require_once('../libs/console_data_config_notifyp_add.php'); // Add New Notification Plan
			break;
		case "cnpd":
			require_once('../libs/console_data_config_notifyp_del.php'); // Delete Notification Plan
			break;
		case "caa":
			require_once('../libs/console_data_config_alarm_add.php'); // Add New Alarm
			break;
		case "cas":
			require_once('../libs/console_data_config_alarm_save.php'); // Update (Save) Alarm Config
			break;
		case "cad":
			require_once('../libs/console_data_config_alarm_del.php'); // Delete Alarm Config
			break;
		case "alarmhistory":
			require_once('../libs/alarm_data_history.php'); // Alarm History
			break;
		case "aau":
			require_once('../libs/account_data_audit.php'); // Account -> Audit (data for table)
			break;																		
		default:
			die('404: Data not found');
	}

?>