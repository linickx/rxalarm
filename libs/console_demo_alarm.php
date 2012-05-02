<?php
	/**

	/demo/alarm -> Detailed View of a given Alarm

	**/


	/**

		Search for the Alarm

	**/

	$AlarmID = preg_replace("/[^0-9]/", "", $url[3]); // clean input.
	
	$AlarmData = $db->get_row("SELECT * from alarms WHERE owner = \"-1\" AND id = \"$AlarmID\"");

	if ($AlarmData) {

		$AlarmDetails = unserialize($AlarmData->post); // Alarm is an array in the DB.

		$title = "Alarm ($AlarmID)";

		// Head stuff for CSS.

		$head .= '	<link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables.css">';
		$head .= "\n";
		$head .= '	<link rel="stylesheet" type="text/css" href="' . $www . '/inc/dt.css">';
		$head .= "\n"; 
		
		$nav = "";

		print_html5_head($title, $head, $nav);

		/**

			This output twofold
				- Alarm: A manual display of what I think is useful
				- RAW: A dump of the Alarm object, containing the stuff I've ignored. 

		**/
		?>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">

					<ul class="nav nav-tabs">
						<li class="active"><a href="#alarm" data-toggle="tab">Alarm <?php echo $AlarmID;?></a></li>
						<li><a href="#raw" data-toggle="tab">RAW</a></li>
					</ul>

					<div class="tab-content">

						<div class="tab-pane active" id="alarm">

							<table id="my_table_id" class="table table-striped">
								<thead>
									<tr>
										<th>Field</th>
										<th>Value</th>
									</tr>
								</thead>
								<tbody>

									<tr class="even">
										<td>Host ID</td>
										<td><?php echo $AlarmDetails->entity->id;?></td>
									</tr>

									<tr class="odd">
										<td>Host</td>
										<td><?php echo $AlarmDetails->entity->label;?></td>
									</tr>	
									<tr class="even">
										<td>IP Adrress</td>
										<td><?php echo $AlarmDetails->details->target;?></td>
									</tr>

									<?php
										
										date_default_timezone_set("Europe/London");
										
										$time = $AlarmDetails->details->timestamp;
										$time = $time / 1000; // format for PHP

										$time = date('Y-m-d H:i:s', $time); 

									?>
									<tr class="odd">
										<td>Time</td>
										<td><a href="#" rel="tooltip" title="On the real version, this time is in the user timezone."><?php echo $time;?></a></td>
									</tr>

									<?php
										/**

											This is crap, there must be a better way!

										**/ 

										date_default_timezone_set("UTC");

										$time = $AlarmDetails->details->timestamp;
										$time = $time / 1000;

										$time = date('Y-m-d H:i:s', $time); 

									?>

									<tr class="even">
										<td>Time (UTC)</td>
										<td><?php echo $time;?></td>
									</tr>

									<tr class="odd">
										<td>State</td>
										<td><?php echo $AlarmDetails->details->state;?></td>
									</tr>
									<tr class="even">
										<td>Status</td>
										<td><?php echo $AlarmDetails->details->status;?></td>
									</tr>
									<tr class="odd">
										<td>Check ID</td>
										<td><?php echo $AlarmDetails->check->id;?></td>
									</tr>

									<tr class="even">
										<td>Check</td>
										<td><?php echo $AlarmDetails->check->label;?></td>
									</tr>

									<?php
										// Create a list of monitoring zones from array.

										$mzs = $AlarmDetails->check->monitoring_zones_poll;
										$mzcounter = 0;

										$mz = "";

										foreach ($mzs as $i) {
											$mz .= $i;

											if ($mzcounter > 0) {
												$mz .= ", ";
											}

											$mzcounter++;
										}
									?>

									<tr class="even">
										<td>Monitoring Zone(s)</td>
										<td><?php echo $mz;?></td>
									</tr>

									<tr class="odd">
										<td>Alarm ID</td>
										<td><?php echo $AlarmDetails->alarm->id;?></td>
									</tr>

									<tr class="even">
										<td>Alarm Criteria</td>
										<td><?php echo $AlarmDetails->alarm->criteria;?></td>
									</tr>

								</tbody>
							</table>

						</div>

						<div class="tab-pane" id="raw">

							<pre>
								<?php print_r($AlarmDetails); ?>
							</pre>

						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
    		$("[rel=tooltip]").tooltip();
		</script>

	<?php
		print_html5_foot();

	} else {

	/**
	
		Alarm Not found.

	**/	

		$title = "Alarm (Not Found)";
		$head = "";
		$nav = "";

		print_html5_head($title, $head, $nav);
		?>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">

					<div class="alert alert-error"><strong>Error!</strong><br />404: Alarm Not Found.</div>

				</div>
			</div>
		</div>
		<?php
		print_html5_foot();
	}
?>