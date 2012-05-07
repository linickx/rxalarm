<?php
	/**

	Account -> API Limits

	**/

	require_once("../libs/console_api.php"); // bootstap the API.

	if ($gotcookie) {

		?>
<h3>Rackspace API LIMITS <small>- nothing is infinite</small></h3>
<p>The Cloud Monitoring API has limits, they are <a href="http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/api-rsource-limits.html">documented here</a>, this page will show your current quota usage.</p>
<?php



				/**

					We have cookies, let's display them.

				**/
			?>
				<h4>Cookie Contents</h4>
				<div style="float:right;">
					<a class="btn btn-danger" href="<?php echo $www;?>/logout/rs">Log Out of Rackspace API</a>
				</div>
				<p>The following infomation is stored in you cookies and is used to access the Rackspace API.</p>
				<div class="accordion" id="apicookies">
				  <div class="accordion-group" style="border:none;">
				    <div class="accordion-heading">
				    	<ul>
				    		<li> Location: <img src="<?php echo $www . "/img/" . $_COOKIE['rxalarm']['rsloc'] .".png";?>" alt="flag" /> </li>
				    		<li> Username: <strong><?php echo $_COOKIE['rxalarm']['rsuid'];?></strong> </li>
				      		<li> Secret Key: 
				      			<a data-toggle="collapse" data-parent="#apicookies" href="#collapseOne">
				      			  [Click to Show]
				      			</a>
				      		</li>	
				      	</ul>
				    </div>
				    <div id="collapseOne" class="accordion-body collapse">
				      <div class="accordion-inner">
				        <code><?php echo $_COOKIE['rxalarm']['rsapi']; ?></code>
				      </div>
				    </div>
				  </div>
				</div>

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

							$CacheLimitsKey = $user['uid'] . "_lim"; // Store Data in a user unique key

							$UseCache = true; // by default use the cache

							if ($_REQUEST['r'] == "1") { // allow the user to manually refresh
								$UseCache = false;
							} else {
								if (!($Cachelimits = apc_fetch($CacheLimitsKey))) { // check the cache
									$UseCache = false;
								}
							}

							if (!($UseCache)) { // fail, got to rackspace.

								$Url = "limits";
								$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
								$Response = json_decode($JsonResponse);

								
								$Cachelimits = apc_store($CacheLimitsKey, $Response, "3600");

								if (!$Cachelimits) {
									?><div class="alert alert-warning"><strong>Warning!</strong><br />There is something wrong with the [rx]Alarm Cache.</div><?php
								}

								$Cache = false;
								$Cachelimits = $Response;

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
								    	<i class="icon-screenshot"></i> <a data-toggle="collapse" data-parent="#limdebug" href="#collapseOneLIM">debug</a>
								    </div>
									</li>
								</ul>
							</div>

							<h4>Resource Limits</h4>

							<div class="accordion" id="limdebug">
								<div class="accordion-group" style="border:none;">
								    <div id="collapseOneLIM" class="accordion-body collapse">
								      <div class="accordion-inner" style="border:none;">
								        	<pre>
												<?php print_r($Cachelimits); ?>
											</pre>
								      </div>
								    </div>
								</div>
							</div>

							<p>Resource limits are the maximum numbers you can configure:</p>
							<ul>
								<li>Checks:<?php echo $Cachelimits->resource->checks;?></li>
								<li>Alarms:<?php echo $Cachelimits->resource->alarms;?></li>
							</ul>
							<h4>Rate Limits</h4>
							<p>Rate limits are the speed / volume at which you can make API requests:</p>
							<ul>
								<li>
									Global API Rate Limits:
									<ul>
										<li>Limit:<?php echo $Cachelimits->rate->global->limit;?></li>
										<li>Used:<?php echo $Cachelimits->rate->global->used;?></li>
										<li>Time Window:<?php echo $Cachelimits->rate->global->window;?></li>
									</ul>
								</li>
								<li>
									Test Checks:
									<ul>
										<li>Limit:<?php echo $Cachelimits->rate->test_check->limit;?></li>
										<li>Used:<?php echo $Cachelimits->rate->test_check->used;?></li>
										<li>Time Window:<?php echo $Cachelimits->test_check->global->window;?></li>
									</ul>
								</li>
								<li>
									Test Alarms:
									<ul>
										<li>Limit:<?php echo $Cachelimits->rate->test_alarm->limit;?></li>
										<li>Used:<?php echo $Cachelimits->rate->test_alarm->used;?></li>
										<li>Time Window:<?php echo $Cachelimits->test_alarm->global->window;?></li>
									</ul>
								</li>
							</ul>
							<?php

					}

	} else {
		?><p><em>Waiting for API Key Modal....</em></p><?php
	}




?>