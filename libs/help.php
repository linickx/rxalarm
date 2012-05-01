<?php
	/**

		Help

	**/

	$title = "Help";
	$nav = array('help' => 'true');

	print_html5_head($title, $head, $nav);
?>

	<div class="page-header">
		<h1>Help</h1>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<p> Some Questions &amp; Answers</p>

				<div class="accordion" id="accordion2">
				  <div class="accordion-group">
				    <div class="accordion-heading">
				      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
				        How do I make this thing work? <small>i.e. Get alarms sent to [rx]Alarm</small>
				      </a>
				    </div>
				    <div id="collapseOne" class="accordion-body collapse">
				      <div class="accordion-inner">
				        Whilst I code up the <em>Configuration</em> section you'll have to use <a href="https://github.com/racker/rackspace-monitoring-cli">raxmon</a> to create checks &amp; alarms to send to [rx]Alarm. See <a href="http://docs.rackspace.com/cm/api/v1.0/cm-getting-started/content/Introduction.html">the cloud monitoring getting started guide</a> on how to use raxmon. <br />
				        Start by creating an entity and a check, then you'll need to create a webhook notification... like this..
				        	<pre>raxmon-notifications-create --label="[rx]Alarm Console" --type=webhook --details=url=http://rxalarm-linickx.rhcloud.com/rackspace.php  --auth-url=https://lon.auth.api.rackspacecloud.com/v1.0</pre>
				        And and alarm plan like this...
				        	<pre>raxmon-notification-plans-create --label="Default Notification Plan" --critical-state=abc --warning-state=abc --ok-state=abc  --auth-url=https://lon.auth.api.rackspacecloud.com/v1.0</pre>
				        	Finally create an alarm, you should see them <a href="<?php echo $www;?>/console">here</a> when you stuff goes up/down.
				      </div>
				    </div>
				  </div>
				  <div class="accordion-group">
				    <div class="accordion-heading">
				      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
				        Why do I need a twitter account?
				      </a>
				    </div>
				    <div id="collapseTwo" class="accordion-body collapse">
				      <div class="accordion-inner">
				        ... because I don't want to have to deal with usernames / passwords / password reset's etc!
				      </div>
				    </div>
				  </div>
				  <div class="accordion-group">
				    <div class="accordion-heading">
				      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
				        How safe is my Rackspace API key?
				      </a>
				    </div>
				    <div id="collapseThree" class="accordion-body collapse">
				      <div class="accordion-inner">
				        Very, you may notice in the source code that we do not store your keys... they are saved on your machine as a cookie for later use and transmitted over HTTPS... what more could you ask for?
				      </div>
				    </div>
				  </div>
				  <div class="accordion-group">
				    <div class="accordion-heading">
				      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
				        Can I forward the alarms to my phone/email/twitter/web2.0app?
				      </a>
				    </div>
				    <div id="collapseFour" class="accordion-body collapse">
				      <div class="accordion-inner">
				        All in good time, that's the plan it just doesn't work yet.
				      </div>
				    </div>
				  </div>
				</div>

			</div>
		</div>
	</div>

<?php
	print_html5_foot();

?>