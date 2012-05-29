<?php

	/**

	Account -> Useful Info //	Misc Dumps - useful infomation :)

	**/

	require_once("../libs/console_api.php"); // bootstap the API.

	if ($gotcookie) {

		?>

		<h3> Rackspace Cloud Monitoring Attributes <small>- Useful Infomation</small></h3>

		<p>This page provides some background infomation on the rackspace clound monitroing platorm</p>

		<?php

		require_once("../libs/console_data_apikey_auth.php"); // Authenticate with RS

		if ($AuthError != "") { // Authentication Failed.

			?>
			<div class="alert alert-error"><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>
			<?php

		} else {
							
			$CacheChkTKey = $user['uid'] . "_chkt"; // Key for Check Type Cache
			$CacheMZKey = $user['uid'] . "_mz"; // Key for Monitor Zone (list) Cache
			$CacheNotTKey = $user['uid'] . "_nott"; // Key for Notification Type Cache

			$UseCache = true; // by default use the cache

			if (isset($_REQUEST['r'])) { // allow the user to manually refresh
				$UseCache = false;
			} else {
				if (!($CacheChkTypes = apc_fetch($CacheChkTKey))) { // check the cache
					$UseCache = false;
				}

				if (!($CacheChkMz = apc_fetch($CacheMZKey))) {  // check the cache
					$UseCache = false;
				}

				if (!($CacheNotTypes = apc_fetch($CacheNotTKey))) {  // check the cache
					$UseCache = false;
				}
			}

			if (!($UseCache)) { // fail, got to rackspace.

				if (!($CacheChkTypes = apc_fetch($CacheChkTKey))) { 
					$Url = "check_types";
					$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
					$Response = json_decode($JsonResponse);
					apc_store($CacheChkTKey, $Response, "43200"); // Cache for longer as these don't change
					$CacheChkTypes = $Response;
				}

				if (!($CacheChkMz = apc_fetch($CacheMZKey))) { 
					$Url = "monitoring_zones";
					$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
					$Response = json_decode($JsonResponse);
					apc_store($CacheMZKey, $Response, "43200"); // ^same^
					$CacheChkMz = $Response;
				}

				if (!($CacheNotTypes = apc_fetch($CacheNotTKey))) { 
					$Url = "notification_types";
					$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
					$Response = json_decode($JsonResponse);
					apc_store($CacheNotTKey, $Response, "43200"); // ^same^
					$CacheNotTypes = $Response;
				}

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
				    	<i class="icon-screenshot"></i> <a data-toggle="collapse" data-parent="#infodebug" href="#collapseOneINF">debug</a>
				    </div>
					</li>
				</ul>
			</div>

			<div class="accordion" id="infodebug">
				<div class="accordion-group" style="border:none;">
				    <div id="collapseOneINF" class="accordion-body collapse">
				      <div class="accordion-inner" style="border:none;">
				      		Check Types:
				        	<pre><?php print_r($CacheChkTypes);?></pre>
				        	Monitoring Zones:
				        	<pre><?php print_r($CacheChkMz);?></pre>
				        	Notification Types:
				        	<pre><?php print_r($CacheNotTypes);?></pre>
				      </div>
				    </div>
				</div>
			</div>

			<p>
				<h4>ToC <small>- Table of Contents</small></h4>
				<ul>
					<li><a href="#ct">Check Types</a></li>
					<li><a href="#mz">Monitoring Zones</a></li>
					<li><a href="#nt">Notification Types</a></li>
				</ul>
			</p>

			<hr />
			
			<a name="ct"></a>
			<h4>Check Types</h4>
			<p>This is a list of checks which can be used to monitor applications</p>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID <br />
							&nbsp;
						</th>
						<th>
							Fields <br />
							<table style="width:100%;"><thead><tr><th>Name</th><th>Desciption</th><th>Requirement</th></tr></thead></table>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($CacheChkTypes->values as $check_types) {
							?><tr><?php
							?><td><?php echo $check_types->id;?></td><?php

							?><td><table style="width:100%;"><tbody><?php

								foreach ($check_types->fields as $field) {

									echo '<tr>';
									echo '<td>' . $field->name . '</td>';
									echo '<td>' . $field->description . '</td>';

									if ( $field->optional == "1") {
										$optional = " <em>Optional</em>";
									} else {
										$optional = ' <span class="label label-important">Mandatory</span>';
									}

									echo '<td>' . $optional . '</td>';
									echo '</tr>';
								}
							?></tbody></table></td><?php
							?></tr><?php
						}
					?>
				</body>
			</table>

			<a name="mz"></a>
			<h4>Monitoring Zones</h4>
			<p>A list of locations where rackspace servers <em>live</em> to perform checks.</p>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Label</th>
						<th>Country</th>
						<th>Source IPs</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($CacheChkMz->values as $monitoring_zones) {

							echo '<tr>';
							echo '<td>' . $monitoring_zones->id . '</td>';
							echo '<td>' . $monitoring_zones->label . '</td>';
							echo '<td>' . $monitoring_zones->country_code . '</td>';

							echo '<td><ul>';
							foreach ($monitoring_zones->source_ips as $value) {
								echo '<li>' . $value . '</li>';
							}

							echo '</ul></td>';
							echo '</tr>';
						}
					?>
				</tbody>
			</table>

			<a name="nt"></a>
			<h4>Notification Types</h4>
			<p>These are the actions which rackspace could monitoring can perform.</p>

			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID <br />
							&nbsp;
						</th>
						<th>
							Fields <br />
							<table style="width:100%;"><thead><tr><th>Name</th><th>Desciption</th><th>Requirement</th></tr></thead></table>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($CacheNotTypes->values as $notification_types) {
							?><tr><?php
							?><td><?php echo $notification_types->id;?></td><?php

							?><td><table style="width:100%;"><tbody><?php

								foreach ($notification_types->fields as $field) {

									echo '<tr>';
									echo '<td>' . $field->name . '</td>';
									echo '<td>' . $field->description . '</td>';

									if ( $field->optional == "1") {
										$optional = " <em>Optional</em>";
									} else {
										$optional = ' <span class="label label-important">Mandatory</span>';
									}

									echo '<td>' . $optional . '</td>';
									echo '</tr>';
								}
							?></tbody></table></td><?php
							?></tr><?php
						}
					?>
				</body>
			</table>
			<?php
		}	
	
	} else {
		?><p><em>Waiting for API Key Modal....</em></p><?php
	}
?>