<?php

	/**

	Console Data Tabs

	- returns tab data to render

	i = ID
		- alarm (tab: console-alarms) -- not used, this was a test!
		- config (tab: console-config)
		- cnt (tab: console-new-user {manual web hook entry} )
		- aca (tab: account-api)
		- ade (tab: account-delete)
		- art (tab: api-retry)
		- svr (tab: console-config-server)
		- chk (tab: console-config-checks)
		- alm (tab: console-config-alarms)
		- not (tab: console-config-notifications)


	**/

	function myjsonmsg($buffer) { // jquery is expecting a JSON output :)

		$output = array();
		$output['msg'] = $buffer;

		$output = json_encode($output);

		return $output;

	}

	ob_start('myjsonmsg'); // buffer the scripts below, then send to callback for JSNON-ISZING

	switch ($_REQUEST['i']) {
		case "alarms":
			require_once('../libs/console_tab_alarms.php');	// user's console alarms
			break;
		case "config":
			require_once('../libs/console_tab_config.php'); // Config Tab.
			break;
		case "cnt":
			require_once('../libs/console_modal_newuser_manual.php'); // Manual WebHook Tabe for New Users		
			break;
		case "aca":
			require_once('../libs/account_tab_apilimits.php'); // API Limits TAB (Account)	
			break;
		case "ade":
			require_once('../libs/account_tab_del.php'); // Delete User Account Tab	
			break;
		case "art":
			require_once('../libs/console_apiretry.php'); // Message to retry API entry	
			break;
		case "svr":
			require_once('../libs/console_tab_config_svr.php'); // Config Tab (Servers)	
			break;
		case "chk":
			require_once('../libs/console_tab_config_chk.php'); // Config Tab (Checks)	
			break;
		case "alm":
			require_once('../libs/console_tab_config_alm.php'); // Config Tab (Alarms)	
			break;
		case "not":
			require_once('../libs/console_tab_config_not.php'); // Config Tab (Notifications)	
			break;							
		default:
			die('404: Data not found');
	}

	

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header('Content-Type: text/javascript');

	ob_end_flush(); // output the PHP from ^above^ require statements as a JSON String.

?>