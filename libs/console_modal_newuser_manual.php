<?php
	/**
		Console -> Modal (New User) -> Manual WebHook Tab
	**/
	?>

<div id="manMSG">

</div>

<form class="well" id="WebHookForm">
	<input type="hidden" name="d" value="num" />
	<label>WebHook</label>
	<input name="manwebhook" type="text" class="span3" placeholder="abc123zyx098">
	<span class="help-inline">Enter Your WebHook Token.</span>
	<button type="submit" id="savetoken" class="btn">Save</button>
</form>

<script type="text/javascript" charset="utf-8">

$('#savetoken').click(function () { 

	// Click save button (post token to Database)

	$.ajax({
		type:'POST', 
		url:'<?php echo $www;?>/data.php', 
		data:$('#WebHookForm').serialize(),
		dataType: "json", 
		success: function(data) {
			if (data.response == 'ok') {
				$("#man").html(data.msg);
			} else {
				$("#manMSG").html(data.msg);
			}
	}});
	
	return false;

});

</script>
