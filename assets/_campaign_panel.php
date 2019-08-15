<div class="panel panel-default" style="margin-bottom:10px;">

	<div class="panel-heading">
		<h5 class="panel-title" style="font-size:16px; padding:10px;">
			<a class="collapsed" data-toggle="collapse" data-parent="#campaign-panels" href="#collapse<?php echo $num; ?>" aria-expanded="false">
				<strong>
				
					<span class="pull-right">
						<?php echo $status; ?>
					</span>
				
				
					<?php echo $num; ?>
					<?php if($AGENT_TYPE !== "USER") echo " - " . $userName; ?> 
					<?php echo " - " . $title; ?> <br /><br />
					Created: <?php echo date("l, F j Y", strtotime($created)); ?>
					
					
				</strong>
			</a>
		</h5>
	</div>
	
	<div id="collapse<?php echo $num; ?>" class="panel-collapse collapse in">
		<div class="panel-body">
			
			
			<div class="col-sm-12 cpn-main-info">
				<h2 style="margin:0;"><?php echo $title; ?></h2>
				
				<div class="col-sm-7">
					<h3>
						<?php if($logo) : ?>
							<img src="<?php echo $logo; ?>" height="50" alt="Vendor Logo" class="img-circle" style="padding:5px; border:1px solid #999;">
						<?php 
							endif; 
							echo $vendor;
						?>
					</h3>
				</div>
				
				<div class="col-sm-5">
					<table>
						<tr>
							<td class="text-right">Start:</td>
							<td><strong><?php echo $start; ?></strong></td>
						</tr>
						<tr>
							<td class="text-right">End:</td>
							<td><strong><?php echo $end; ?></strong></td>
						</tr>
					</table>
				</div>
				
				
				
			</div>
			
			<?php if($stat_num > 4 && in_array($AGENT_TYPE, array("MANAGER", "ADMIN", "CEO"))): ?>
				<div class="col-sm-12">
					<p style="margin-top:20px;">Management URL: <strong><?php echo $url; ?></strong></p>
				</div>
			<?php endif; ?>
			
			
			
			<!-- ACTION BUTTONS -->
			<div class="col-md-12 action-buttons" style="margin-top:30px;">
				<?php if($stat_num > 2) : ?>
				
					<button class="btn btn-primary" type="button" onClick="window.location.href = '/campaign/create.php?num=<?php echo $num; ?>'">View</button>
				<?php endif; ?>
			
			
				<?php if($stat_num == 1) : //In Progress  ?>
					<button class="btn btn-primary" type="button" onClick="window.location.href = '/campaign/create.php?num=<?php echo $num; ?>'">Open</button>
					
					<?php if($AGENT_TYPE == "USER") : ?>
						<button class="btn btn-danger" type="button" onClick="confirmCampaignDelete(<?php echo $num; ?>); return false;">Delete</button>
					<?php endif; ?>
					
					
				<?php elseif($stat_num == 2) : //Ready For Purchase ?>
				
					<button class="btn btn-primary" type="button" onClick="window.location.href = '/campaign/create.php?num=<?php echo $num; ?>'">Open</button>
					<button class="btn btn-success" type="button" onClick="window.location.href = '/campaign/purchase.php?campaign=<?php echo $num; ?>'">Purchase</button>
					
					<?php if($AGENT_TYPE == "USER") : ?>
						<button class="btn btn-danger" type="button" onClick="confirmCampaignDelete(<?php echo $num; ?>); return false;">Delete</button>
					<?php endif; ?>
					
					
					
				<?php elseif($stat_num == 3) : //Waiting for Start ?>
				
					<?php if($AGENT_TYPE == "ADMIN" || $AGENT_TYPE == "CEO") : ?>
						<button class="btn btn-success" type="button" onClick="window.location.href = '/admin/dashboard.php?approve=1&num=<?php echo $num; ?>'">Approve User Build</button>
					<?php endif; ?>
					
					
					<!-- <button class="btn btn-primary" type="button" onClick="window.location.href = '/campaign/create.php?num=<?php //echo $num; ?>'">View</button> -->
				
				<?php elseif($stat_num == 4) : //Waiting for Start ?>
				
					<?php if($AGENT_TYPE == "ADMIN" || $AGENT_TYPE == "CEO") : ?>
						<!-- <button class="btn btn-warning" type="button" onClick="window.location.href = '/admin/dashboard.php?assign=1&num=<?php //echo $num; ?>'">Assign To Manager</button> -->
						<button class="btn btn-warning" type="button" onClick="modal_showAssignModal(this, <?php echo $num; ?>); return false;">Assign To Manager</button>
					<?php endif; ?>
					
				
				<?php elseif($stat_num == 5) : //Waiting for manager build ?>
				
					<?php if($AGENT_TYPE == "MANAGER") : ?>
						<!-- <button class="btn btn-warning" type="button" onClick="window.location.href = '/admin/dashboard.php?assign=1&num=<?php //echo $num; ?>'">Assign To Manager</button> -->
						<button class="btn btn-success" type="button" onClick="manager_submitCampaignBuild(<?php echo $num; ?>); return false">Submit Campaign Build</button>
					<?php endif; ?>
					
				<?php elseif($stat_num == 6) : //Waiting for admin approval of manager build?>
				
					<?php if($AGENT_TYPE == "ADMIN") : ?>
						<!-- <button class="btn btn-warning" type="button" onClick="window.location.href = '/admin/dashboard.php?assign=1&num=<?php //echo $num; ?>'">Assign To Manager</button> -->
						<button class="btn btn-primary" type="button" onClick="window.open('<?php echo $url; ?>', '_blank');return false;">View on Vendor</button>
						<button class="btn btn-success" type="button" onClick="alert('TBI'); return false;">Approve Manager Build</button>
					<?php endif; ?>
					
				<?php endif; ?>
				
				<?php if($AGENT_TYPE != "USER" && $AGENT_TYPE != "GUEST" && $stat_num > 2) : ?>
					<button class="btn btn-warning" type="button" onClick="alert('csv download triggered (TBI)')">Download Campaign CSV</button>
				<?php endif; ?>
				
				
			</div>
		</div>
	</div>
</div>


<script>


function modal_showAssignModal(elt, cpnNum){
	
	var modal = "#manager-assign-modal";
	var info  = $(elt).parents(".panel-body").find(".cpn-main-info").clone();
	
	console.log($(elt));
	
	$(modal).find("#assign-cpn-num").text(cpnNum);
	$(modal).find(".main-info").after(info);
	$(modal).modal();
}
	

function confirmCampaignDelete(cpnNum){
	
	if(confirm("Really delete this campaign?")){ window.location.href = window.location.href = '/user/dashboard.php?delete=1&num=' + cpnNum; }
}
</script>