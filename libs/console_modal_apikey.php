<?php
	/**

		Modal which appears when the user needs the API Cookie Set.

	**/

?>

<div class="modal fade" id="APIModal">

  <div class="modal-header">
    <h3>API Key Required</h3>
  </div>
  <div class="modal-body">

  	<p>
  		To use this feature, we need your Rackspace username &amp; api key.
  	</p>

  	<div id="frmFAIL">

  	</div>

  	<div id="frmOK">

  		<form id="APIKeyForm">

			<input type="hidden" name="d" value="api" />

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
			
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cancel</a>
				<button type="submit" id="savekey" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>

  </div>

</div>

<script type="text/javascript" charset="utf-8">

	var apistatus; // variable so that other DIVS know what's going on.

		$('#savekey').click(function () { 

			// Save Button

				$.ajax({
					type:'POST', 
					url:'<?php echo $www;?>/data.php', 
					data:$('#APIKeyForm').serialize(),
					dataType: "json", 
					success: function(data) {
						if (data.response == 'ok') {
							$("#frmOK").html(data.msg);
							apistatus='ok';
						} else {
							$("#frmFAIL").html(data.msg);
							apistatus='fail';
						}
				}});
				
				return false;
			});

</script>