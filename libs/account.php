<?php
	/**
		
		/Account --> Everything needs an account management page.

	*/

	authuser(); // start by checking the user!

	// User, is OK - lets do something.

	$title = "Account";
	$head = "";
	$nav = "";

	print_html5_head($title, $head, $nav);

	?>

	<div class="page-header">
		<h1>Account</h1>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<!--Sidebar content-->
				<ul class="nav nav-pills nav-stacked">
					<li class="active"><a href="#you" data-toggle="tab">You</a></li>
					<li><a <a href="#aca" data-toggle="tab">API Limits</a></li>
					<li><a <a href="#ade" data-toggle="tab">Delete Account</a></li>
				</ul>
			</div>
			<div class="span10">
				<!--Body content-->

				<div class="tab-content">
						
						<div class="tab-pane active" id="you">
							
							<?php require_once("../libs/account_tab_you.php"); ?>

						</div>

						<div class="tab-pane" id="aca">
							<img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading...
						</div>

						<div class="tab-pane" id="ade">
							<img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading...
						</div>
				</div>

	 		</div>
		</div>
	</div>

	<script type="text/javascript">

	var activediv;
				
	$('a[data-toggle="tab"]').on('shown', function (e) {

		var nowtab = e.target // activated tab
		activediv = $(nowtab).attr('href').substr(1);
	
	    $.getJSON('<?php echo $www;?>/data.php?d=tab&i='+activediv).success(function(data){
	          $("#"+activediv).html(data.msg);
	    });
	  
	});  
	  
	</script>

	<?php

	print_html5_foot();

?>