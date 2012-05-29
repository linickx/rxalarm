<?php
	/**

	Account -> API Audi

	**/

	require_once("../libs/console_api.php"); // bootstap the API.

	if ($gotcookie) {
		?>
<h3>Rackspace API Audit <small>- big brother is watching you</small></h3>
<p>Every write operation performed against the API generates an audit record that is stored for 30 days. Audits record a variety of information about the request including the method, URL, headers, query string, transaction ID, the request body and the response code. They also store information about the action performed including a JSON list of the previous state of any modified objects. For example, if you perform an update on an server/entity, this will record the state of the entity before modification..</p>
<?php

	/**
		
		Make Rackspace API Call.

	**/

		require_once("../libs/console_data_apikey_auth.php"); // Authenticate with RS

		if ($AuthError != "") { // Authentication Failed.

			?>
			<div class="alert alert-error"><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>
			<?php

		} else {

			$CacheAuditKey = $user['uid'] . "_aud"; // Store Data in a user unique key

			$UseCache = true; // by default use the cache

			if ($_REQUEST['r'] == "1") { // allow the user to manually refresh
				$UseCache = false;
			} else {
				if (!($CacheAudits = apc_fetch($CacheAuditKey))) { // check the cache
					$UseCache = false;
				}
			}

			if (!($UseCache)) { // fail, got to rackspace.

				$Url = "audits";
				$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
				$Response = json_decode($JsonResponse);

				
				$CacheAudits = apc_store($CacheAuditKey, $Response, "3600");

				if (!$CacheAudits) {
					?><div class="alert alert-warning"><strong>Warning!</strong><br />There is something wrong with the [rx]Alarm Cache.</div><?php
				}

				$Cache = false;
				$CacheAudits = $Response;

			} else {
				$Cache = true; // Cache is gooood!
			}

			?>

			<div style="float:right;padding-top:10px;">
				<ul class="unstyled">
					<li>
						<?php
							if ($Cache) {
								?><i class="icon-warning-sign"></i> cached data<?php
							} else {
								?><i class="icon-cog"></i> fresh data<?php
							}
						?>
					</li>
					<li><i class="icon-refresh"></i> <a href="#" class="refreshbutton">refresh</a></li>
					<li>
						<div class="accordion-heading">
				    	<i class="icon-screenshot"></i> <a data-toggle="collapse" data-parent="#auddebug" href="#collapseOneAUD">debug</a>
				    </div>
					</li>
				</ul>
			</div>

			<div class="accordion" id="auddebug">
				<div class="accordion-group" style="border:none;">
				    <div id="collapseOneAUD" class="accordion-body collapse">
				      <div class="accordion-inner" style="border:none;">
				        	<pre>
								<?php print_r($CacheAudits); ?>
							</pre>
				      </div>
				    </div>
				</div>
			</div>

			<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {

					// Alarms Table
			
					oTable = $('#my_table_id').dataTable( {
						"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

							switch(aData[2]) {
								case 'FAIL':
									$('td:eq(2)', nRow).addClass('redText');
									break;
								case 'OK':
									$('td:eq(2)', nRow).addClass('greenText');
									break;
								default:
									break;			
							}

						},
						"bAutoWidth": false,
						"bProcessing": true,
						"sPaginationType": "bootstrap",
						"aaSorting": [[ 0, "desc" ]],
						"oLanguage": {
							"sLengthMenu": "_MENU_ records per page"
						},
						"sAjaxSource": '<?php echo $www;?>/data.php?d=aau'
					} );

					oTable.fnReloadAjax();
			
				} );
			</script>

			<table id="my_table_id" class="table table-striped">
				<thead>
					<tr>
						<th>Time</th>
						<th>Action</th>
						<th>Status</th>
						<th>Url</th>
						<th>Payload</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<?php
		}

	} else {
		?><p><em>Waiting for API Key Modal....</em></p><?php
	}
?>