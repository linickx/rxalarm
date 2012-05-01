<?php
	/**

	Account -> API Limits

	**/

	require_once("../libs/rackcloudmanager.php"); // include the rackspace lib

	function forceapimodal(){
		require_once("../libs/console_modal_apikey.php");
	}

?>
<h3>Rackspace API LIMITS <small>- nothing is infinite</small></h3>
<p>The Cloud Monitoring API has limits, they are <a href="http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/api-rsource-limits.html">documented here</a>, this page will show your current quota usage.</p>
<?php

	if (isset($_COOKIE['rxalarm']['rsuid'])) { 

			$alarm = "info";

		if (!isset($_COOKIE['rxalarm']['rsapi'])) {
			$alarm = "error";
		}

		if (!isset($_COOKIE['rxalarm']['rsloc'])) {
			$alarm = "error";
		}

		if ($alarm == "error") {
			forceapimodal();
		} else {

				/**

					We have cookies, let's display them.

				**/
			?>
				<h4>Cookie Contents</h4>
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

					$RSUID = $_COOKIE['rxalarm']['rsuid'];
					$RSAPI = $_COOKIE['rxalarm']['rsapi'];
					$RSLOC = $_COOKIE['rxalarm']['rsloc'];

					$Auth = new RackAuth($RSUID,$RSAPI,$RSLOC);
					$Auth->auth();

					if ($Auth->getXAuthToken() == "") { // Authentication Failed.

						?>
						<div class="alert alert-error"><strong>Error!</strong><br />Oops, Something went wrong. Is you username &amp; API key correct?</div>
						<?php

					} else {

							$Url = "limits";
							$JsonResponse = Request::postAuthenticatedRequest($Url,$Auth);
							$Response = json_decode($JsonResponse);

							?>
							<h4>Resource Limits</h4>
							<p>Resource limits are the maximum numbers you can configure:</p>
							<ul>
								<li>Checks:<?php echo $Response->resource->checks;?></li>
								<li>Alarms:<?php echo $Response->resource->alarms;?></li>
							</ul>
							<h4>Rate Limits</h4>
							<p>Rate limits are the speed / volume at which you can make API requests:</p>
							<ul>
								<li>
									Global API Rate Limits:
									<ul>
										<li>Limit:<?php echo $Response->rate->global->limit;?></li>
										<li>Used:<?php echo $Response->rate->global->used;?></li>
										<li>Time Window:<?php echo $Response->rate->global->window;?></li>
									</ul>
								</li>
								<li>
									Test Checks:
									<ul>
										<li>Limit:<?php echo $Response->rate->test_check->limit;?></li>
										<li>Used:<?php echo $Response->rate->test_check->used;?></li>
										<li>Time Window:<?php echo $Response->test_check->global->window;?></li>
									</ul>
								</li>
								<li>
									Test Alarms:
									<ul>
										<li>Limit:<?php echo $Response->rate->test_alarm->limit;?></li>
										<li>Used:<?php echo $Response->rate->test_alarm->used;?></li>
										<li>Time Window:<?php echo $Response->test_alarm->global->window;?></li>
									</ul>
								</li>
							</ul>
							<?php
							#echo '<pre>';
							#print_r($Response);
							#echo '</pre>';

					}
		}

	} else{

		forceapimodal();
	}
?>