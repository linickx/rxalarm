<?php

	/**

		Console Demo - Displays Test Data.

	**/

	/**

		Alarm Output

	**/

	if (isset($url[3])) {
		require_once("../libs/console_demo_alarm.php");
		die();
	}

	/**

		Console Output.

	**/

	$head = '	<script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>';
	$head .= "\n";
	$head .= '	<link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables.css">';
	$head .= "\n";
	$head .= '<link rel="stylesheet" type="text/css" href="' . $www . '/inc/dt.css">';
	$head .= "\n";
	$head .= '<script type="text/javascript" charset="utf-8" language="javascript" src="' . $www . '/inc/dt.js"></script>';
	

	$title = "Console";
	$nav = array('console' => 'true');

	print_html5_head($title, $head, $nav);
?>
		<div class="page-header">
			<h1>Demo Console</h1>
		</div>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">

				<script type="text/javascript" charset="utf-8">
					$(document).ready(function() {
				
						oTable = $('#my_table_id').dataTable( {
							"bProcessing": true,
							"sPaginationType": "bootstrap",
							"aaSorting": [[ 0, "desc" ]],
							"oLanguage": {
								"sLengthMenu": "_MENU_ records per page"
							},
							"sAjaxSource": '<?php echo $www;?>/data.php?d=demoalarms'
						} );
				
						function nicksloop() {
							//alert("Hello World");
							//oTable.fnDraw();
							oTable.fnReloadAjax();
							setTimeout(nicksloop, 60000);
						}
						
						nicksloop();
					} );
				</script>
						
				
				<table id="my_table_id" class="table table-striped">
					<thead>
						<tr>
							<th>Time</th>
							<th>State</th>
							<th>Host</th>
							<th>Monitoring Zone</th>
							<th>Type</th>
							<th>Status</th>
							<th>ID</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php
	print_html5_foot();

?>