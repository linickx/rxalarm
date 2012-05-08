<?php
	/**

	/Console -> Config -> Servers -> Edit Form (user clicks edit button.)

	**/

	$RSentityID = $_REQUEST['entityid'];
	$RSentityLABEL = $_REQUEST['rslabel'];
	$RSentityIP = unserialize($_REQUEST['rsip']);

	if ($_REQUEST['update'] == "cancel") { // POST Cancel from Self

		$ipaddr = "";

			foreach ($RSentityIP as $IP) {

				$ipaddr .=  $IP[0] . ' <em>' . $IP[1] . '</em> <br/>';
			}
		
		$ipaddr .= "";

		?>
			<td>
				<form id="From-<?php echo $RSentityID; ?>">
					<input type="hidden" name="d" value="tab" />
					<input type="hidden" name="i" value="sve" />
					<input type="hidden" name="update" value="yep" />
					<input type="hidden" name="entityid" value="<?php echo $_REQUEST['entityid']; ?>" />
					<input type="hidden" name="rslabel" value="<?php echo $_REQUEST['rslabel']; ?>" />
					<input type="hidden" name="rsip" value='<?php echo $_REQUEST['rsip']; ?>' />
				</form>
			</td>
			<td><?php echo $RSentityID; ?></td>
			<td><?php echo $RSentityLABEL; ?></td>
			<td><?php echo $ipaddr; ?></td>
			<td><a href="#" class="editbutton" id="edit-<?php echo $RSentityID; ?>" rel="tooltip" title="Edit <?php echo $RSentityLABEL; ?>"><i class="icon-edit"></i></a></td>
		<?php

	} else { 

?>
	<td>
		<form id="From-<?php echo $RSentityID; ?>">
			<input type="hidden" name="i" value="" />
			<input type="hidden" name="update" value="" />
			<input type="hidden" name="entityid" value="<?php echo $_REQUEST['entityid']; ?>" />
			<input type="hidden" name="rslabel" value="<?php echo $_REQUEST['rslabel']; ?>" />
			<input type="hidden" name="rsip" value='<?php echo $_REQUEST['rsip']; ?>' />
		</form>
		<form id="Del-<?php echo $RSentityID; ?>">
			<input type="hidden" name="d" value="csd" />
			<input type="hidden" name="entityid" value="<?php echo $RSentityID; ?>" />
		</form>
	</td>
	<td><button class="btn btn-danger btn-mini" id="delentity"><i class="icon-trash"></i> delete <?php echo $RSentityID; ?></button></td>
	<td><input type="text" name="new-rslabel" class="txtbox" value="<?php echo $RSentityLABEL; ?>"></td>
	<td>
		<?php
			$ipcounter = 0;
			foreach ($RSentityIP as $IP) {
				?>
					<input type="text" name="new-rsip[<?php echo $ipcounter;?>]" class="txtbox" value="<?php echo $IP[0]; ?>"> 
				
					<input type="text" name="new-rsipname[<?php echo $ipcounter;?>]" class="txtbox" value="<?php echo $IP[1]; ?>">
				<br/><?php
				$ipcounter++;
			}
		?>
	</td>
	<td>
		<button type="submit" id="savechanges" class="btn btn-mini btn-primary">Save</button> <button id="cancelform" class="btn btn-mini">Cancel</button>
		<form id="Save-<?php echo $RSentityID; ?>">
			<input type="hidden" name="d" value="css" />
			<input type="hidden" name="entityid" value="<?php echo $RSentityID; ?>" />
			<input type="hidden" name="hid-rslabel" value="<?php echo $RSentityLABEL; ?>" />
			<?php
			$ipcounter = 0;
			foreach ($RSentityIP as $IP) {
				?>
				<input type="hidden" name="hid-rsip[<?php echo $ipcounter;?>]" value="<?php echo $IP[0]; ?>">
				<input type="hidden" name="hid-rsipname[<?php echo $ipcounter;?>]" value="<?php echo $IP[1]; ?>">
				<?php
				$ipcounter++;
			}
		?>
		</form>
	</td>
<script type="text/javascript">

	$('#cancelform').click(function () { 

		// User clicks Cancel Button - which submits a form to get the "<tr>" back
	
		$('input[name="update"]').val('cancel');
		$('input[name="i"]').val('sve');

		$.ajax({
			type:'POST', 
			url:'<?php echo $www;?>/data.php?d=tab', 
			data:$('#From-<?php echo $RSentityID; ?>').serialize(),
			dataType: "json", 
			success: function(data) {
				$("#entity-<?php echo $RSentityID; ?>").html(data.msg); 
		}});

		return false;
	}); 

	$('input.txtbox').keyup(function () {

		// The "typed" data is copied into our hidden form for submission

		var ThisInput = this.name.substr(4);
		var NewInput ='hid-' + ThisInput;
		var ThisVAL = $(this).val();

		$("input[name='" +NewInput+ "']").val(ThisVAL);

	});

	$('#savechanges').click(function () {

		// User clicks Save, which is posted to rackspace.

		// #SVEfrmMSG == Warning Popup
		// #entity-<?php echo $RSentityID; ?> == this.tr

		//$('input[name="label"]').val($('input[name="new-rslabel"]').val);
		//$('input[name="rsip"]').val($('input[name="new-rsip"]').val);
	
		$.ajax({
			type:'POST', 
			url:'<?php echo $www;?>/data.php', 
			data:$('#Save-<?php echo $RSentityID; ?>').serialize(),
			dataType: "json", 
			success: function(data) {

				$("#SVEfrmMSG").html(data.msg);

				if (data.response == 'ok') {
					$("#entity-<?php echo $RSentityID; ?>").html(data.ok);	
				} 
		}});


		return false;
	});

	$('#delentity').click(function () {

		// User clicks Delete, which is posted to rackspace

		// #SVEfrmMSG == Warning Popup
		// #entity-<?php echo $RSentityID; ?> == this.tr
		
		$.ajax({
			type:'POST', 
			url:'<?php echo $www;?>/data.php', 
			data:$('#Del-<?php echo $RSentityID; ?>').serialize(),
			dataType: "json", 
			success: function(deldata) {

				$("#SVEfrmMSG").html(deldata.msg);

				if (deldata.response == 'ok') {
					$("#entity-<?php echo $RSentityID; ?>").html(deldata.ok);	
				} 
		}});


		return false;
	});

</script>
<?php
	}
?>