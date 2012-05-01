# rxalarm

An Alarm WebHook for Rackspace Cloud Monitoring - **See it in action! ==> https://rxalarm-linickx.rhcloud.com**

## File Structure

Below is a list of what all these files do....

* ./php - Openshift WWW Root. (Files exposed to the internet)
** bootstrap - [Twitter Bootstrap](http://twitter.github.com/bootstrap)
** inc - general includes (non-twitter)
** img - images
** index.php - Start Here.
** data.php - Javascript response API (Json/Ajax/Thing)
** twitter.php - Twitter Login/Callback
** rackspace.php - Rackspace Post hook
** config.php - Secret Config Stuff

* ./libs - Library
** OAuthSimple.php - Oauth Library (for twitter login) [@jrconlin](https://github.com/jrconlin/oauthsimple)
** account.php - /account - User Account Mgnt
** account_data_you.php - Data Response: Account *you* tab
** account_tab_apilimits.php - Account: API Tab
** account_tab_del.php - Account: Delete Tab
** account_tab_you.php - Account: You Tab
** console.php - /console - Main App
** console_data_alarms.php - Data Response: Console Alarms
** console_data_apikey.php - Data Response: API Key Modal
** console_data_demoalarms.php - Data Response: Demo Alarms
** console_data_newuser_auto.php - Data Response: New User Auto Webhook 
** console_data_newuser_man.php - Data Response: New User Manual Webhook
** console_data_tabs.php - Data Response: repond with HTML Tabs
** console_demo.php - /console/demo - Demo App
** console_modal_apikey.php - Modal to request API key when needed
** console_modal_newuser_default.php - New User modal (Default HTML) for Auto
** console_modal_newuser_manual.php - New User modal for Manual WebHook
** console_tab_alarms.php - /Console - Alarm Tab
** console_tab_config.php - / Console - Config Tab
** contact.php - /contact
** ez_sql_core.php - SQL Library [@jv2222](https://github.com/jv2222/ezSQL)
** ez_sql_mysql.php - SQL Library [@jv2222](https://github.com/jv2222/ezSQL)
** help.php - /help
** homepage.php - / 
** logout.php - /logout.php (delete cookies)
** myfunctions.php - My custom library of random functions.
** rackcloudmanager.php - Rackspace Cloud Library [phprackcloud](http://code.google.com/p/phprackcloud/)
** setup.php - Application bootstrap, setup database etc.

## Setup

To make this thing work, you're gonna need.

* Webserver (Apache)
* PHP (Tested on PHP/5.3)
* MySQL (Tested on MySQL 5.1)

### ./php/config.php

The secrets for twitter / mysql / cookie salt etc live in config.php, below is an example.

```
<?php
	// mySQL Config
	// OpenShift Variables (openshift.redhat.com)
	$db_host = $_ENV['OPENSHIFT_DB_HOST']; // Server
	$db_user = $_ENV['OPENSHIFT_DB_USERNAME']; // Username
	$db_pass = $_ENV['OPENSHIFT_DB_PASSWORD']; // Password
	$db_name = $_ENV['OPENSHIFT_APP_NAME']; // Database Name
	$db_port = $_ENV['OPENSHIFT_DB_PORT']; // Database Connection TCP Port

	// register at http://twitter.com/oauth_clients and fill these two 
	define("TWITTER_CONSUMER_KEY", "abc");
	define("TWITTER_CONSUMER_SECRET", "abc");

	// Secret Key for Cookie CheckSum
	define("SALT", "password");
?>
```

## License

[Attribution-NonCommercial-ShareAlike 3.0 Unported](https://creativecommons.org/licenses/by-nc-sa/3.0/)