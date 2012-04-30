<?php
	/**

		Help

	**/

	$head = "";
	$title = "Contact";
	$nav = array('contact' => 'true');

	print_html5_head($title, $head, $nav);
?>

	<div class="page-header">
		<h1>Contact</h1>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<p> There are a few ways you can get in touch...</p>
				<h2>Say hi!</h2>
				<p>I'm on twitter, gimme a shout out via <a href="https://twitter.com/#!/linickx">@linickx</a> :)</p>
				<h2>Got Issues?</h2>
				<p>Bugs and problems are to be tracked on Github <i class="icon-arrow-right"></i> <a href="https://github.com/linickx/rxalarm/issues">https://github.com/linickx/rxalarm/issues</a> </p>
				<h2>Got Fixes?</h2>
				<p>If you can patch or improve this, please clone <a href="https://github.com/linickx/rxalarm">the github repo</a> and send me a pull request.</p>
				<h2>Say hi! <small>>160char</small></h2>
				<p> I have <a href="http://www.linickx.com/contact">a website with a contact page</a> which usually works.</p>
			</div>
		</div>
	</div>

<?php
	print_html5_foot();

?>