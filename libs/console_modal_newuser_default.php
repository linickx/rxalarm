<?php

	/**

	Console -> New User Modal (Default with APIKey Tab)

	**/
	?>

<div class="modal fade" id="myModal">

  <div class="modal-header">
    <h3>Welcome New User!</h3>
  </div>
  <div class="modal-body">

  	<p>
  		Hello, <br/ >
  		To get started you need to supply your <a href="http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/service-notification-types-crud.html#service-notification-headers">webhook token</a> so that we can identify your alarms. <br />
  		To find your webhook token you can enter your Rack space username &amp; API key for us to get it auto-magically, or if you perfer (<em>and have another method</em>) use the manual tab to supply the token yourself.
  	</p>
    
  	<ul class="nav nav-tabs">
		<li class="active"><a href="#auto" data-toggle="tab">Automatic</a></li>
		<li><a href="#man" data-toggle="tab">Manual</a></li>
	</ul>

	<div class="tab-content">

  		<div class="tab-pane active" id="auto">

  			<div id="frmMSG">

  			</div>

  			<form class="well" id="APIKeyForm">

  				<input type="hidden" name="d" value="nua" />

  				<div style="float:right;">
					<label class="radio">
						<input type="radio" name="location" id="location1" value="uk" checked>
						<img src="<?php echo $www . "/img/uk.png";?>" alt="UK" /> UK
					</label>
					<label class="radio">
						<input type="radio" name="location" id="location2" value="us">
						<img src="<?php echo $www . "/img/us.png";?>" alt="US" /> US
					</label>
				</div>

  				<label>UserName</label>
				<input type="text" class="span3" placeholder="rackspace" name="username">

				<label>API Key</label>
				<input type="text" class="span3" placeholder="" name="apikey">
				<span class="help-inline">API Key &amp; Username are found under "you account" on the RackSpace Console.</span>
				
				<button type="submit" id="gettoken" class="btn">Get my Token</button>
			</form>

		</div>

		<div class="tab-pane" id="man">

			<img src="<?php echo $www;?>/img/loading.gif" alt="Loading..." height="16px" width="16px" /> Loading...	

		</div>
	</div>

  </div>

</div>

<script type="text/javascript" charset="utf-8">

	$('#myModal').modal('show')

		$('#gettoken').click(function () { 

				$.ajax({
					type:'POST', 
					url:'<?php echo $www;?>/data.php', 
					data:$('#APIKeyForm').serialize(),
					dataType: "json", 
					success: function(data) {
						if (data.response == 'ok') {
							$("#auto").html(data.msg);
						} else {
							$("#frmMSG").html(data.msg);
						}
				}});
				
				return false;
			});

</script>