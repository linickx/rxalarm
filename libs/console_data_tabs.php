<?php

	/**

	Console Data Tabs

	- returns tab data to render

	i = ID
		- alarm (tab: console-alarms) -- not used, this was a test!
		- config (tab: console-config)
		- cnt (tab: console-new-user {manual web hook entry} )
		- you (tab: account-you)
		- aca (tab: account-api)
		- acu (tab: account-audit)
		- aci (tab: account-info)
		- ade (tab: account-delete)
		- art (tab: api-retry)
		- svr (tab: console-config-server)
		- chk (tab: console-config-checks)
		- alm (tab: console-config-alarms)
		- not (tab: console-config-notifications)
		- sve (tab: console-config-server-edit)
		- ctd (tab: console-config-check-type-detail)
		- ctt (tab: console-config-check-target)
		- cte (tab: console-config-check-edit)


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
		case "you":
			require_once('../libs/account_tab_you.php'); // You Tab (Account)		
			break;	
		case "aca":
			require_once('../libs/account_tab_apilimits.php'); // API Limits TAB (Account)	
			break;
		case "acu":
			require_once('../libs/account_tab_audit.php'); // API Audit TAB (Account)	
			break;
		case "aci":
			require_once('../libs/account_tab_info.php'); // API Info TAB (Account)	
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
		case "sve":
			require_once('../libs/console_tab_config_svr_edit.php'); // Config Tab (Servers -> Edit Form)	
			break;	
		case "chk":
			require_once('../libs/console_tab_config_chk.php'); // Config Tab (Checks)	
			break;
		case "ctd":
			require_once('../libs/console_tab_config_chk_details.php'); // Check Details (for add new form)	
			break;
		case "ctt":
			require_once('../libs/console_tab_config_chk_targets.php'); // Check Target (for add new form)	
			break;
		case "cte":
			require_once('../libs/console_tab_config_chk_edit.php'); // Config Tab (Checks -> Edit Form)	
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