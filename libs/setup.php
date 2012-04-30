<?php

/**

	Setup Script, load up database, create tables and load up any libraries all scripts need..
	
*/

	/** 

	OPENSHIFT SPECIALNESS

	- Force HTTPS on OpenShift :)

	**/

	if(isset($_SERVER["HTTP_X_FORWARDED_PROTO"])) {

		if ($_SERVER["HTTP_X_FORWARDED_PROTO"] == "http") {

			/**

				$raxmon-notifications-test --auth-url=https://lon.auth.api.rackspacecloud.com/v1.0 --type=webhook --details=url=https://rxalarm-linickx.rhcloud.com/rackspace.php
				{u'message': u'139867822917408:error:14077458:SSL routines:SSL23_GET_SERVER_HELLO:reason(1112):s23_clnt.c:596:\n',
				 u'status': u'error'}
				$

			**/

			if ( $_SERVER["REQUEST_URI"] != "/rackspace.php") {
				$redirection = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
				header("Location: $redirection");
				exit;
			}			
		}

		$wwwproto = $_SERVER["HTTP_X_FORWARDED_PROTO"] . '://';

	} else {
		$wwwproto = "http://";
	}

	// What is our URL ?
	$www = $wwwproto . $_SERVER["HTTP_HOST"];

	/** 

	Get Config

	**/

	if (file_exists('./config.php')) {
		require_once('./config.php'); 
	} else {
	 	die('404: Config Not Found.');
	}

	/**

	Load up Libraries

	**/

	include_once "../libs/ez_sql_core.php";
	include_once "../libs/ez_sql_mysql.php";

	include_once "../libs/myfunctions.php";

	// MySQL Connection

	$mysql_server =  $db_host . ":" . $db_port;     // server details
	$db = new ezSQL_mysql($db_user,$db_pass,$db_name,$mysql_server); // Username , password , DB
	$debug = false; // no debug by default.

	// Table Checking...

	$my_tables = $db->get_results("SHOW TABLES LIKE '%user%'",ARRAY_N); // query db for user table

	if (!$my_tables) { // We have no tables, better create some....

		/**
			TABLE DEF:
				id = unique user id
				
				tw_at = Twitter Access Token
				tw_sec = Twitter Secret Token
				tw_account = Array of account info we want/store from Twitter

				rs_wh = Rack Space Web Hook (Secret Token)
				rs_account = Array of account info we want/store from Rack Space

		**/

		$db->query("CREATE TABLE IF NOT EXISTS user (id int(50) NOT NULL auto_increment, tw_at varchar(100), tw_sec varchar(100), tw_account TEXT, rs_wh varchar(256), rs_account TEXT,KEY id (id)) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4954 ;");

		$my_tables = $db->get_results("SHOW TABLES LIKE '%user%'",ARRAY_N);

		if ($my_tables) {
 			echo "<h2>Table created</h2>";
 			$debug = true;

		} else {
			die("Euston, We have a problem.... failed to create user table");
		}
	}

	$my_tables = $db->get_results("SHOW TABLES LIKE '%alarms%'",ARRAY_N); // query db for user table

	if (!$my_tables) { // We have no tables, better create some....

		/**
			TABLE DEF:
				
				# Need to rename table :) ... this is what is posted to me :)

				post = serialised array of post data

				headers = serialised array of the ^poster^ headers

				owner = account associated with Request (userid)

		**/

		$db->query("CREATE TABLE IF NOT EXISTS alarms (id int(50) NOT NULL auto_increment, post TEXT, headers TEXT,owner int(50),KEY id (id)) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4954 ;");

		$my_tables = $db->get_results("SHOW TABLES LIKE '%alarms%'",ARRAY_N);

		if ($my_tables) {
 			echo "<h2>Table created</h2>";
 			$debug = true;

		} else {
			die("Euston, We have a problem.... failed to create alarms table");
		}
	}

	if ($debug) {

		$db->get_results("SHOW TABLES",ARRAY_N);
		$db->debug(); // show tables
	}



?>