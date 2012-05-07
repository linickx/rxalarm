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
					<li>
						<div class="accordion-heading">
				    	<i class="icon-screenshot"></i> <a data-toggle="collapse" data-parent="#svrdebug" href="#collapseOneSVR">debug</a>
				    </div>
					</li>
				</ul>
			</div>
			<h3>Servers / Entities <small>- Things that you monitor</small></h3>
			<p>&nbsp;</p>
			
			<div id="SVEfrmMSG">

  			</div>
		
			<table id="my_table_id" class="table table-striped">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>ID</th>
						<th>Name</th>
						<th>IP Address</th>
						<th>&nbsp;</th>
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

			$ipaddr = "";
			$ipaddresforform = array();

			foreach ($entity->ip_addresses as $key => $ip) {

				$ipaddresforform[$ipcounter] = array($ip, $key);

				$ipaddr .=  $ip . ' <em>' . $key . '</em> <br/>';
			}
			$ipaddr .= "";
			
			?>
				<tr id="entity-<?php echo $entity->id; ?>" class="<?php echo $style;?>">
					<td>
						<form id="From-<?php echo $entity->id; ?>">
						<input type="hidden" name="d" value="tab" />
						<input type="hidden" name="i" value="sve" />
						<input type="hidden" name="update" value="yep" />
						<input type="hidden" name="entityid" value="<?php echo $entity->id; ?>" />
						<input type="hidden" name="rslabel" value="<?php echo $entity->label; ?>" />
						<input type="hidden" name="rsip" value='<?php echo serialize($ipaddresforform); ?>' />
						</form>
					</td>
					<td><?php echo $entity->id; ?></td>
					<td><?php echo $entity->label; ?></td>
					<td><?php echo $ipaddr; ?></td>
					<td><a href="#" class="edit" id="edit-<?php echo $entity->id; ?>" rel="tooltip" title="Edit <?php echo $entity->label; ?>"><i class="icon-edit"></i></a></td>
				</tr>
			<?php
		
			$counter++;
		}

		?>
				</tbody>
			</table>

			<div class="accordion" id="svrdebug">
				<div class="accordion-group" style="border:none;">
				    <div id="collapseOneSVR" class="accordion-body collapse">
				      <div class="accordion-inner" style="border:none;">
				        	<pre>
								<?php print_r($CacheSvrs); ?>
							</pre>
				      </div>
				    </div>
				</div>
			</div>

			<script type="text/javascript">

    			$("a.edit").click(function () {

    				var RSentityID = this.id.substr(5);

					$.ajax({
					type:'POST', 
					url:'<?php echo $www;?>/data.php', 
					data:$('#From-'+RSentityID).serialize(),
					dataType: "json", 
					success: function(data) {
						
						$("#entity-"+RSentityID).html(data.msg);
						
					}});

					$('[rel=tooltip]').tooltip('hide')

    				
    			return false;
				}); 

				$("[rel=tooltip]").tooltip();

			</script>

		<?php
		

	} else {

		?>
		<p>Waiting for API Key...</p>
		<?php

	}

?>