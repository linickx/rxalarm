<?php
	/**

	Console -> Config -> Server -> Add New (Data Response)

	**/

	$label = $_REQUEST['addlabel']; // submissions
	
	$ip_addresses = array(); // Build array for posting ip addresses.
	$ipaddresforform = array(); // Array for form submission
	$ipaddr = ""; // Table output
	$ipcounter = 0; // array counter

	foreach ($_REQUEST['addip'] as $IP) { // fix the multiple ipaddresses for use!

		$ipname = $_REQUEST['addipname'][$ipcounter];
		$ip_addresses[$ipname] = $IP;

		$ipaddresforform[$ipcounter] = array($ip, $ipname);
		$ipaddr .=  $IP . ' <em>' . $ipname . '</em> <br/>';

		$ipcounter++;
	}

	require_once("../libs/console_data_apikey.php"); // bootstap the API.

	$Request = array("label"=>$label, "ip_addresses"=>$ip_addresses); // array for changes
	$JsonRequest = json_encode($Request);

	$Url = "entities";

	$Response = Request::postAuthenticatedRequest($Url,$Auth,$JsonRequest,true);

	$Headers = Request::parseHeaders($Response); // the new server/enity ID is hidden in the Location Header returned.
	$HiddenEntityID = explode("/", $Headers['Location']);
    $entityid = $HiddenEntityID['6'];

	$LastCode = Request::getLastHTTPCode();

	if ($LastCode == "201") { // 201 is good, all else is bad.
    	
    	$res = 'ok';

    		    $res = 'ok';
		$msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button><strong>Update Sucessful!</strong><br />Server has been saved.</div>';

		
			$ok = '<tr>
					<td>
					<form id="From-' . $entityid . '">
						<input type="hidden" name="d" value="tab" />
						<input type="hidden" name="i" value="sve" />
						<input type="hidden" name="update" value="yep" />
						<input type="hidden" name="entityid" value="' . $entityid . '" />
						<input type="hidden" name="rslabel" value="' . $label . '" />';

			$ok .= '<input type="hidden" name="rsip" value=' . "'" . serialize($ipaddresforform) . "' />";
			$ok .= '</form>
					</td><td>' . $entityid . '</td><td>' . $label . '</td><td>' . $ipaddr . '</td>';

			$ok .= '<td><a href="#" class="editbutton" id="edit-' . $entityid . '" rel="tooltip" title="Edit ' . $label . '"><i class="icon-edit"></i></a></td>
					</tr>';

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