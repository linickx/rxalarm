<?php

	/**

	Lib for getting ready to use the RS API within the console.

	**/

	require_once("../libs/rackcloudmanager.php"); // include the rackspace lib

	function forceapimodal(){
			global $www;

		require_once("../libs/console_modal_apikey.php");

		?>

		<script type="text/javascript" charset="utf-8">

		$('#APIModal').modal('show')

		$('#APIModal').on('hide', function () {

			if (apistatus == 'ok') {				
				  
				$.getJSON('<?php echo $www;?>/data.php?d=tab&i='+activediv).success(function(data){
					$("#"+activediv).html(data.msg);
				});

			} else {
				$.getJSON('<?php echo $www;?>/data.php?d=tab&i=art').success(function(data){
	          		$("#"+activediv).html(data.msg);
	    		});
			}
 			 
		})

		</script>

		<?php
	}

	$gotcookie = true;

	if (!isset($_COOKIE['rxalarm']['rsuid'])) { 
		$gotcookie = false;
	} else {
		if ($_COOKIE['rxalarm']['rsuid'] == "") { 
		$gotcookie = false;
		}
	}

	
			
	if (!isset($_COOKIE['rxalarm']['rsapi'])) {
		$gotcookie = false;
	} else {
		if ($_COOKIE['rxalarm']['rsapi'] == "") { 
		$gotcookie = false;
		}
	}

	

	if (!isset($_COOKIE['rxalarm']['rsloc'])) {
		$gotcookie = false;
	} else {
		if ($_COOKIE['rxalarm']['rsloc'] == "") { 
		$gotcookie = false;
	}
	}

	

	if (!$gotcookie) {
		forceapimodal();
	} 


	/**

					We have cookies, let's do something..

	**/ 
?>	