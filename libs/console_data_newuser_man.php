<?php
	/**
		
		Data Response to New User (Manual) Request - Used to recieve WebHook supplied by user.

	**/

	$MANWEBHOOK = $_REQUEST['manwebhook'];

	$rs_wh = preg_replace("/[^a-zA-Z0-9]/", "", $MANWEBHOOK); // clean input.
	$rs_wh = $db->escape($rs_wh);

	$uid = substr($_COOKIE['rxalarm']['uid'], 0, 256);
	$safeish_uid = $db->escape($uid); 
	$dbupdate = $db->query("UPDATE user SET rs_wh = \"$rs_wh\" WHERE id = \"$safeish_uid\"");

	if ($dbupdate) {

		$res = 'ok';
		$msg = '<div class="alert alert-success"><strong>Nice Work!</strong><br />Your WebHook Token Saved.</div><p><a href="#" class="btn" data-dismiss="modal">Close</a></p>';

	} else {

		$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. What did you do?</div>';
	}

	$output = array ('response'=>$res,'msg'=> $msg);
	output_json($output);
?>