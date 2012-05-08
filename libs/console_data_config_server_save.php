<?php
	/**

	Console -> Config -> Servers -- UPDATE RS Entity

	**/

	$label = $_REQUEST['hid-rslabel']; // submissions
	$entityid = $_REQUEST['entityid'];

	$ip_addresses = array(); // Build array for posting ip addresses.
	$ipaddresforform = array(); // Array for form submission
	$ipaddr = ""; // Table output
	$ipcounter = 0; // array counter

	foreach ($_REQUEST['hid-rsip'] as $IP) { // fix the multiple ipaddresses for use!

		$ipname = $_REQUEST['hid-rsipname'][$ipcounter];
		$ip_addresses[$ipname] = $IP;

		$ipaddresforform[$ipcounter] = array($ip, $ipname);
		$ipaddr .=  $IP . ' <em>' . $ipname . '</em> <br/>';

		$ipcounter++;
	}

	require_once("../libs/console_data_apikey.php"); // bootstap the API.

	$Request = array("label"=>$label, "ip_addresses"=>$ip_addresses); // array for changes
	$JsonRequest = json_encode($Request);

	$Url = "entities/" . trim($entityid);

	$JsonResponse = Request::postAuthenticatedPutRequest($Url,$Auth,$JsonRequest);

    $Response = json_decode($JsonResponse);

    $LastCode = Request::getLastHTTPCode();

    if ($LastCode == "204") {
    	
    	$res = 'ok';
		$msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button><strong>Update Sucessful!</strong><br />Your changes have been saved.</div>';

		$ok = '<td>
				<form id="From-' . $entityid . '">
					<input type="hidden" name="i" value="tab" />
					<input type="hidden" name="update" value="sve" />
					<input type="hidden" name="entityid" value="' . $entityid . '" />
					<input type="hidden" name="rslabel" value="' . $label . '" />';

		$ok .= '<input type="hidden" name="rsip" value=' . "'" . serialize($ipaddresforform) . "' />";
		$ok .= '</form>
				</td><td>' . $entityid . '</td><td>' . $label . '</td><td>' . $ipaddr . '</td>';

		$ok .= '<td><a href="#" class="editbutton" id="edit-' . $entityid . '" rel="tooltip" title="Edit ' . $label . '"><i class="icon-edit"></i></a></td>';

		$CacheSvrsKey = $user['uid'] . "_ent"; // Cached Entities
    	apc_delete($CacheSvrsKey); // purge the cache (to show the changes at next refresh)

    } else {

    	$RSError = Request::getLastError();
    	$RSErrorCode = "$LastCode";

    	$res = 'error';
		$msg = '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><strong>Error!</strong><br />Oops, Something went wrong. - Save Failed. ($RSErrorCode: $RSError)</div>';
    }

    $output = array ('response'=>$res,'msg'=> $msg,'ok'=>$ok);
	output_json($output);
?>