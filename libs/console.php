<?php
	/**

		Alarm Console

	**/

	if ($url[2] == "demo") {
		require_once("../libs/console_demo.php"); // Demo Console is Required.
		die();
	}

	/**

		Real Alarm Console

	**/

	authuser();

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

	if ($user['rs_wh'] == "new") { // is the user a new user?
		require_once("../libs/console_modal_newuser_default.php"); // print the modal.
	}

	?>

	<div class="page-header">
		<h1>Console</h1>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">

				<ul class="nav nav-tabs">
					<li class="active"><a href="#alarms" data-toggle="tab">Alarms</a></li>
					<li><a href="#config" data-toggle="tab">Configuration</a></li>
				</ul>

				<div class="tab-content">

					<div class="tab-pane active" id="alarms">

						<?php require_once("../libs/console_tab_alarms.php"); ?>

					</div>

					<div class="tab-pane" id="config">

						<img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading Config...

					</div>
				</div>

				<script type="text/javascript">
				
				$('a[data-toggle="tab"]').on('shown', function (e) {

					// Bootstrap tab toggler

					var nowtab = e.target // activated tab
					var divid = $(nowtab).attr('href').substr(1);
				  
				    $.getJSON('<?php echo $www;?>/data.php?d=tab&i='+divid).success(function(data){
				          $("#"+divid).html(data.msg);
				    });
				  
				});  
				  
				</script>
			</div>
		</div>
	</div>

<?php
	print_html5_foot();

?>