<?php

	/**
		
		This will be the homepage, shown to non-logged-in users.
		
	*/

	$title = "Alarm Console for RS Cloud";
	$head = "";
	$nav = array('home' => 'true');

	print_html5_head($title, $head, $nav);
?>
	<div class="container-fluid">

		<div class="hero-unit">
					<h1>[rx]Alarm</h1>
					<p>An Open-source Alarm Console for Rackspace Cloud Monitoring.</p>
				</div>

		<div class="row-fluid">
			<div class="span6">
				<h2>What is this all about?</h2>
				<p>Those wonderful people over at <a href="https://www.rackspace.co.uk">Rackspace</a> have created a <a href="https://www.rackspace.co.uk/cloud-hosting/cloud-products/cloud-monitoring/">Cloud Monitoring</a> product which can generate alarms when things happen - this tool can receive those alarms!</p>
				<p>If you want to see for your self the <a href="<?php echo $www;?>/console/demo">demo console</a> shows all the test alarms which we have received.</p>
				<?php
					if (!isset($user)) {
						?><p><strong>To get started....</strong> <a href="<?php echo $www;?>/twitter.php"><img src="https://si0.twimg.com/images/dev/buttons/sign-in-with-twitter-d.png" alt="sign in with twitter" /></a></p>
						<?php
					}
					?>
			</div>
			<div class="span6">
				<h2>How much does this cost?</h2>
				<p>
					<strong>Nothing</strong>. [rx]Alarm is hosted on <a href="http://openshift.redhat.com">Openshift Express</a> is is currently free, cloud monitoring is currently free to and this code behind this is <a href="https://github.com/linickx/rxalarm/">published on github</a> for all to see!... The catch is that it is released under the <a href="https://creativecommons.org/licenses/by-nc-sa/3.0/">NonCommercial Creative Commons</a> license which means you can't sell this... if you want to make money from rxalarms contact me first and perhaps we can come to an agreement ;-)
				</p>

			</div>
		</div>
	</div>
<?php
	print_html5_foot();
	?>