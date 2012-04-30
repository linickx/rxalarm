<?php
	/**
		
		This is for logged in users - their dashboard!

	*/

	authuser(); // start by checking the user!

	// User, is OK - lets do something.

	$title = "Account";
	$head = "";

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
					<li class="active"><a href="#">You</a></li>
					<li><a href="#">Delete</a></li>
				</ul>
			</div>
			<div class="span10">
				<!--Body content-->
				<p>Hello!</p>
				<p>2do: TimeZone - View Cookies - Delete Account - Manual Webhook (no apikey)</p>
	 		</div>
		</div>
	</div>
	<?php

	print_html5_foot();

?>