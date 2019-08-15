<?php
	//all the standard modal variables to be set 
	
	$modalID    = "manager-assign-modal";       //html id attribute for the modal
	$modalLabel = "manager-assign-modal-label";       //aria-labelledby html attribute
	$modalTitle = "Assign A Campaign Manager";       //title test for the modal


?>



<!-- Button trigger modal 
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>
-->

<!-- Modal -->
<div class="modal fade" id="<?php echo $modalID;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalLabel;?>">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $modalTitle;?></h4>
			</div>
			
			<div class="row modal-body">
				<div class="col-sm-12 main-info">
					<p>Campaign: <strong id="assign-cpn-num"></strong></p>
				</div>
				
				<div class="col-sm-12">
					<div class="form-group">
						<label>Management URL: </label>
						<input class="form-control" type="text" name="management_url" />
						<p>Copy and paste the URL for this campaign (as found on vendor site) into the box.<br />The Manager that has been available the longest will be assigned to this campaign.</p>
					</div>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onClick="modal_submitManagerAssign(this); return false;">Submit</button>
			</div>
			
		</div>
	</div>
</div>


<script>
function modal_submitManagerAssign(elt){
	
	var cpnNum  = $("#assign-cpn-num").text();
	var mgm_url = $("input[name='management_url']").val();
	
	if(!mgm_url){
		
		alert("No Management URL given.");
		return false;
	} 
	
	var loc = "/admin/dashboard.php?assign=1&num=" + cpnNum + "&management_url=" + mgm_url;
	location.href = loc;
}

</script>