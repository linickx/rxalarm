<?php

	/**

	Account -> Useful Info //	Misc Dumps - useful infomation :)

	**/

	require_once("../libs/console_api.php"); // bootstap the API.

	if ($gotcookie) {

		?>

		<h3> Rackspace Cloud Monitoring Attributes <small>- Useful Infomation</small></h3>
		<?php

		require_once("../libs/console_data_apikey_auth.php"); // Authenticate with RS

		if ($AuthError != "") { // Authentication Failed.

			?>
			<div class="alert alert-error"><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>
			<?php

		} else {
							
			$CacheChkTKey = $user['uid'] . "_chkt"; // Key for Check Type Cache
			$CacheMZKey = $user['uid'] . "_mz"; // Key for Monitor Zone (list) Cache

			$UseCache = true; // by default use the cache

			if ($_REQUEST['r'] == "1") { // allow the user to manually refresh
				$UseCache = false;
			} else {
				if (!($CacheChkTypes = apc_fetch($CacheChkTKey))) { // check the cache
					$UseCache = false;
				}

				if (!($CacheChkMz = apc_fetch($CacheMZKey))) {  // check the cache
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
				        	<pre>Something</pre>
				      </div>
				    </div>
				</div>
			</div>

			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span5">
						<pre><?php print_r($CacheChkTypes);?></pre>
					</div>
					<div class="span5">
						<pre><?php print_r($CacheChkMz);?></pre>
					</div>
				</div>
			</div>
			<?php
		}	
	
	} else {
		?><p><em>Waiting for API Key Modal....</em></p><?php
	}
?>