<?php
	/**

		Console -> Messsage for any tab which requires API Retry

	**/

	require_once("../libs/console_modal_apikey.php"); // include the Modal (just in case user clicks the button below.)
?>
<div class="alert alert-warning"><strong>No API Keys</strong><br />A valid Rackspace API username &amp; Password is required to access this feature.</div>
<p>&nbsp;</p>
<p style="text-align:center;">
<a class="btn btn-danger" id="rslogin" href="#">Log into Rackspace API</a>
</p>
<script type="text/javascript" charset="utf-8">

$('#rslogin').click(function () { 

	$('#APIModal').modal('show')

	return false;
			
});

$('#APIModal').on('hide', function () {

	if (apistatus == 'ok') {
	
		  $.getJSON('<?php echo $www;?>/data.php?d=tab&i='+activediv).success(function(data){
		        $("#"+activediv).html(data.msg);
		  });
	}

})

</script>