<?php
	/**

	/console -> config Tab

	**/

?>

<div class="row-fluid">
	<div class="span2">
		<!--Sidebar content-->
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#svr" data-toggle="pill">Servers</a></li>
			<li><a <a href="#chk" data-toggle="pill">Checks</a></li>
			<li><a <a href="#alm" data-toggle="pill">Alarms</a></li>
			<li><a <a href="#not" data-toggle="pill">Notifications</a></li>
		</ul>
	</div>
	<div class="span10">
		<!--Body content-->

		<div class="tab-content">
				
				<div class="tab-pane active" id="svr">
					<?php require_once("../libs/console_tab_config_svr.php"); ?>
				</div>

				<div class="tab-pane" id="chk">
					<p><img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading Checks...</p>
				</div>

				<div class="tab-pane" id="alm">
					<p><img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading Alarms...</p>
				</div>

				<div class="tab-pane" id="not">
					<p><img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading Notifications...</p>
				</div>
		</div>

	</div>
</div>
<script type="text/javascript">

	var activediv = "svr";

	$('a[data-toggle="pill"]').on('shown', function (e) {

		var nowpill = e.target // activated tab
		activediv = $(nowpill).attr('href').substr(1);
	  
	    $.getJSON('<?php echo $www;?>/data.php?d=tab&i='+activediv).success(function(data){
			$("#"+activediv).html(data.msg);
		});
	  
	});

	$('#refresh').click(function () {

		$.getJSON('<?php echo $www;?>/data.php?d=tab&r=1&i='+activediv).success(function(data){
			$("#"+activediv).html(data.msg);
		});

		return false;
	}); 
</script> 