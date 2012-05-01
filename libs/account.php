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
					<li><a <a href="#api" data-toggle="tab">API Limits</a></li>
					<li><a <a href="#delete" data-toggle="tab">Delete Account</a></li>
				</ul>
			</div>
			<div class="span10">
				<!--Body content-->

				<div class="tab-content">
						
						<div class="tab-pane active" id="you">
							
							<?php require_once("../libs/account_tab_you.php"); ?>

						</div>

						<div class="tab-pane" id="api">
							<h3>Rackspace API LIMITS <small>- nothing is infinite</small></h3>
							<p>LIMITS</p>
						</div>

						<div class="tab-pane" id="delete">
							<h3>Delete your Data <small>- Missing you already!</small></h3>
							<p>DEL</p>
						</div>
				</div>

	 		</div>
		</div>
	</div>
	<?php

	print_html5_foot();

?>