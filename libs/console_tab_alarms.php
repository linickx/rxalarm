<?php

	/**

	/console -> Alarms Tab

	**/

?>


	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {

			// Alarms Table
	
			oTable = $('#my_table_id').dataTable( {
				"bProcessing": true,
				"sPaginationType": "bootstrap",
				"aaSorting": [[ 0, "desc" ]],
				"oLanguage": {
					"sLengthMenu": "_MENU_ records per page"
				},
				"sAjaxSource": '<?php echo $www;?>/data.php?d=alarms'
			} );
	
			function nicksloop() {
				
				// Loop for refreshing the table

				oTable.fnReloadAjax();
				setTimeout(nicksloop, 60000);
			}
			
			nicksloop(); // loop it!
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

