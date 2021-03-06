<?php

	/**

		/Account --> You Tab

	**/

	// Start by getting data for form.

	// PASTE from: http://stackoverflow.com/questions/1727077/generating-a-drop-down-list-of-timezones-with-php

	static $regions = array(
    'Africa' => DateTimeZone::AFRICA,
    'America' => DateTimeZone::AMERICA,
    'Antarctica' => DateTimeZone::ANTARCTICA,
    'Asia' => DateTimeZone::ASIA,
    'Atlantic' => DateTimeZone::ATLANTIC,
    'Europe' => DateTimeZone::EUROPE,
    'Indian' => DateTimeZone::INDIAN,
    'Pacific' => DateTimeZone::PACIFIC
	);
	
	foreach ($regions as $name => $mask) {
	    $tzlist[] = DateTimeZone::listIdentifiers($mask);
	}

?>

<h3>Your Settings <small>- bits you can change</small></h3>

<p>The following settings are associated with your account.</p>

<div id="frmMSG">

</div>

<form class="form-horizontal" id="youForm">

	<input type="hidden" name="d" value="ays" />

	 <fieldset>
	 	<div class="control-group">
			<label class="control-label">TimeZone:</label>
			<div class="controls docs-input-sizes">
				 <select class="span3" name="tz">
				<?php
				foreach ($tzlist as $region) {
					foreach ($region as $timezone) {
						echo '<option ';
						if ($timezone == $user['rackspace']['timezone']) {
							echo 'selected="selected" ';
						}
						echo 'value="' . $timezone . '">' . $timezone . '</option>' . "\n";
					}
				}
				?>
				</select>
				<span class="help-inline">What timezone do you want alarms displayed in?</span>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">WebHook Token:</label>
			<div class="controls docs-input-sizes">
				<input type="text" class="span3" value="<?php echo $user['rs_wh'];?>" name="webhook">
				<span class="help-inline">Only change this if you know what you are doing.</span>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" id="saveme" class="btn">Save Changes</button>
		</div>
	 <fieldset>
</form>

<script type="text/javascript" charset="utf-8">

$('#saveme').click(function () { 

	// Save button - for posting changes to DB

	$.ajax({
		type:'POST', 
		url:'<?php echo $www;?>/data.php', 
		data:$('#youForm').serialize(),
		dataType: "json", 
		success: function(data) {
			
				$("#frmMSG").html(data.msg);
			
	}});
	
	return false;
});

</script>
