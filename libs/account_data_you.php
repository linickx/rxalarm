<?php

	/**

		Data Response to Settings Form (Under Account)

	**/

	$TIMEZONE = $_REQUEST['tz']; // form data
	$RSWEBHOOK = $_REQUEST['webhook'];
	
	/**

		Webhook Changes.

	**/

	if ($RSWEBHOOK != $user['rs_wh']) {

		// If thew webhook has changed, do something.

		$rs_wh = preg_replace("/[^a-zA-Z0-9]/", "", $RSWEBHOOK); // clean input.
		$rs_wh = $db->escape($rs_wh);

		$uid = substr($_COOKIE['rxalarm']['uid'], 0, 256);
		$safeish_uid = $db->escape($uid); 
		$dbupdate = $db->query("UPDATE user SET rs_wh = \"$rs_wh\" WHERE id = \"$safeish_uid\"");

		if ($dbupdate) {
			$status = true; // ok msg (below)
		} else {
			$status = false; // fail msg (below)
		}
	}

	/**

		Timezone updates.

	**/

	if ($TIMEZONE != $user['rackspace']['timezone']) {

		$tz_submit = explode("/", $TIMEZONE); // format is country / city ... this approach isn't perfect but should stop the script kiddies.

		if (count($tz_submit) == "2") {

			$tz_submit['0'] = preg_replace("/[^a-zA-Z]/", "", $tz_submit['0']); // Strip chars.
			$tz_submit['1'] = preg_replace("/[^a-zA-Z]/", "", $tz_submit['1']);

			$TIMEZONE = $tz_submit['0'] . "/" . $tz_submit['1']; // glue back together.

			$rs_account = array(); // new array.
			$rs_account = $user['rackspace']; // copy user array into ours.
			$rs_account['timezone'] = $TIMEZONE; // over-write timezone.
			$rs_account = serialize($rs_account); 
			$rs_account = $db->escape($rs_account);

			if (!isset($safeish_uid)) {
				$uid = substr($_COOKIE['rxalarm']['uid'], 0, 256);
				$safeish_uid = $db->escape($uid); 
			}
			
			$dbupdate2 = $db->query("UPDATE user SET rs_account = \"$rs_account\" WHERE id = \"$safeish_uid\"");

			if ($dbupdate2) {
				if (!isset($staus))	{ // if it is set, keep old value.
					$status = true;
				}
			} else {
					$status = false;
			}
			
		} else { // Format has be frigged with.
			$status = false;
		}
	}

	/**
		
		User Msg (Output).

	**/

	if (!isset($status)) { // no status, no change.
		$msg = '<div class="alert alert-info"><button class="close" data-dismiss="alert">×</button><strong>No Change!</strong><br />Nothing to do.</div>';
	} else {

		if ($status) {
		$msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button><strong>Sucess!</strong><br />Changes Saved.</div>';	
		} else {
			$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. Reboot and try again.</div>';	
		}

	}

	$output = array ('msg'=> $msg);
	output_json($output);
?>