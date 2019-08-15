<?php
	//all the standard modal variables to be set 
	
	if(!@$modalID)    $modalID    = "WasNotSet";       //html id attribute for the modal
	if(!@$modalLabel) $modalLabel = "WasNotSet";       //aria-labelledby html attribute
	if(!@$modalTitle) $modalTitle = "Was Not Set";     //title test for the modal
	if(!@$fileReqs)   $fileReqs   = array();
	if(!@$size)       $size       = "";
	
	$size = (@$size)? " modal-$size" : $size;
?>

<!-- Modal -->
<div class="modal fade campaign-create-modal" id="<?php echo $modalID;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalLabel;?>">
	<div class="modal-dialog<?php echo $size; ?>" role="document">
		<div class="modal-content">
		
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
				<button type="button" class="btn btn-primary pull-right save-modal">Save changes</button>
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $modalTitle;?></h4>
			</div>
			
			<div class="modal-body">
				<form class="campaign-create-modal-form" action="POST" method="?">
			
					<input type="hidden" name="num" value="<?php echo $cpnNum; ?>" />
					<input type="hidden" name="<?php echo $modalID; ?>" value="1" />
					<?php 
						//required form elements
						foreach($fileReqs as $f) @include $f;
					?>
				</form>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary save-modal">Save changes</button>
			</div>
			
		</div>
	</div>
</div>