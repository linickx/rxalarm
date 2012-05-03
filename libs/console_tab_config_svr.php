<?php

	/**

	Console -> Config Tab -> Servers

	View/Configure Monitored Servers

	**/

	require_once("../libs/console_api.php"); // bootstap the API.

	if ($gotcookie) {

		function is_odd($number) {
   			return $number & 1; // 0 = even, 1 = odd
		}

		/**

			Everything lives in here.

			This generate a list of users servers (entities) which are monitored by RSCM.

		**/

		$CacheSvrsKey = $user['uid'] . "_ent"; // Store Data in a user unique key

		$UseCache = true; // by default use the cache

		if ($_REQUEST['r'] == "1") { // allow the user to manually refresh
			$UseCache = false;
		} else {
			if (!($CacheSvrs = apc_fetch($CacheSvrsKey))) { // check the cache
				$UseCache = false;
			}
		}
		

		if (!($UseCache)) { // fail, got to rackspace.

			$RSUID = $_COOKIE['rxalarm']['rsuid'];
			$RSAPI = $_COOKIE['rxalarm']['rsapi'];
			$RSLOC = $_COOKIE['rxalarm']['rsloc'];

			$Auth = new RackAuth($RSUID,$RSAPI,$RSLOC);
			$Auth->auth();

			$AuthError = Request::getLastError();

			if ($AuthError != "") { // Authentication Failed.

				?>
				<div class="alert alert-error"><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>
				<?php

			} else {

					$Url = "entities";
					$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
					$Response = json_decode($JsonResponse);

					
					$CacheSvrs = apc_store($CacheSvrsKey, $Response, "3600");

					if (!$CacheSvrs) {
						?><div class="alert alert-warning"><strong>Warning!</strong><br />There is something wrong with the [rx]Alarm Cache.</div><?php
					}

					$Cache = false;
					$CacheSvrs = $Response;

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
					<li><i class="icon-refresh"></i> <a href="#" id="refresh">refresh</a></li>
				</ul>
			</div>
			<h3>Servers / Entities <small>- Things that you monitor</small></h3>
			<p>&nbsp;</p>
		<?php

		#echo '<pre>';
		#print_r($CacheSvrs);
		#echo '</pre>';

		?>
			<table id="my_table_id" class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>IP Address</th>
					</tr>
				</thead>
				<tbody>
		<?php

		$counter = 0;
		foreach ($CacheSvrs->values as $entity) {

			if (is_odd($counter)) {
				$style="odd";
			} else {
				$style="even";
			}

			$ipcounter = 0;
			$ipaddr = "";

			foreach ($entity->ip_addresses as $ip) {
				$ipaddr .= $ip;

				if ($ipcounter > 0) {
					$ipaddr .= ", ";
				}
			}
			
			?>
				<tr class="<?php echo $style;?>">
					<td><?php echo $entity->id; ?></td>
					<td><?php echo $entity->label; ?></td>
					<td><?php echo $ipaddr; ?></td>
				</tr>
			<?php
		
			$counter++;
		}

		?>
				</tbody>
			</table>
		<?php
		

	} else {

		?>
		<p>Waiting for API Key...</p>
		<?php

	}

?>