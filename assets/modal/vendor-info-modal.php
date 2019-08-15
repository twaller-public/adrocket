<?php
	//all the standard modal variables to be set 
	
	$modalID    = "vendor-info-modal";       //html id attribute for the modal
	$modalLabel = "vendor-info-modal-label";       //aria-labelledby html attribute
	$modalTitle = "Vendor Info";       //title test for the modal


?>



<!-- Button trigger modal 
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>
-->

<!-- Modal -->
<div class="modal fade" id="<?php echo $modalID;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalLabel;?>">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $modalTitle;?></h4>
			</div>
			
			<div class="modal-body">
				<?php 
					//your body content or require statements here
					require "assets/_vendor_info.php";
				?>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
			</div>
			
		</div>
	</div>
</div>