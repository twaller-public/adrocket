<?php

	require_once "_purchase_ctrl.php";

?>

<html>
	<?php require_once "../assets/_header.php"; ?>
	
	<div class="container" style="margin-bottom:5px; margin-top:5px;">
		
	</div>
	
	<div class="container" style="padding-bottom:50px; margin-bottom:5px; margin-top:5px; background-color: #FFF;">
	
		<h1>Complete Your Purchase: </h1>
	
		<?php if($errorsAndAlerts) : ?>
			<br />
			<p class="alert alert-danger"><?php echo $errorsAndAlerts; ?></p>
		<?php endif; ?>
	
		<?php if(!$completed) : ?>
			<div class="row">
			
			
				<div class="col-md-12">
				
								
					<!-- Campaign Information Table -->
					<div class="col-md-6">
						
						<h3>Your Campaign Information</h3>
						<table class="table">
							<tr>
								<th>Title</th>
								<td><?php echo $title; ?></td>
							</tr>
							
							<tr>
								<th>Vendor</th>
								<td><?php echo $vendorTitle; ?></td>
							</tr>
							
							<tr>
								<th>Industry</th>
								<td><?php echo $industryTitle; ?></td>
							</tr>
							
							<tr>
								<th>Location(s)</th>
								<td><?php echo implode(",", $locationTitles); ?></td>
							</tr>
							
							<tr>
								<th>Budget</th>
								<td><?php echo "$" . number_format($budget, 2); ?></td>
							</tr>
							
							<tr>
								<th>Management Fee</th>
								<td><?php echo "$" . number_format($mgmtFee, 2); ?></td>
							</tr>
							
							<tr>
								<th>Expected Clicks</th>
								<td><?php echo $leads; ?></td>
							</tr>
							
							<tr>
								<th>Start Date</th>
								<td><?php echo date("F, j Y", strtotime($start));; ?></td>
							</tr>
							
							<tr>
								<th>End Date</th>
								<td><?php echo ($recur)? "Ad will continue until cancelled" : date("F, j Y", strtotime($end));; ?></td>
							</tr>
						
						</table>
						<?php //require "../assets/_campaign_breakdown.php"; ?>
					</div>

					
										
					<!-- Purcahse Information Table -->
					<div class="col-md-6">
							<h3>Your Purchase Information</h3>
							<table class="table">
							
								<tr>
									<th>Purchase Amount</th>
									<td><?php echo "$" . number_format($subTotal, 2); ?></td>
								</tr>
								
								<tr>
									<th>Tax</th>
									<td><?php echo "$" . number_format($tax, 2); ?></td>
								</tr>
								
								<tr>
									<th>Total</th>
									<td><?php echo "$" . number_format($total, 2); ?></td>
								</tr>
							
							</table>
							

					</div>
					
	
				</div>
				

				
				<div class="col-md-12 complete-wrap text-center">
					<p>Complete your purchase using PayPal</p>
					<a class="btn btn-success btn-lg" href="<?php echo $payPalURL;?>" role="button">Purchase</a>
					<button class="btn btn-default" onClick="window.location.assign('index.php'); return false;">Cancel</button> 
					
				</div>



			</div>
		<?php else : ?>

			<div class="row">
				<div class="col-sm-12 text-center">
					<h3>You Have Completed Your Purchase!</h3>
					<hr />
				</div>
				<div class="col-md-2"></div>
				<div class="col-md-4">
					<h3>Your Campaign Information:</h3>
					<p><strong>Title</strong> <?php echo $title; ?></p>
					<p><strong>Vendor</strong> <?php echo $vendorTitle; ?></p>
					<p><strong>Industry</strong> <?php echo $industryTitle; ?></p>
					<p><strong>Province</strong> <?php echo $provinceTitle; ?></p>
					<p><strong>Budget</strong> <?php echo "$" . number_format($budget, 2); ?></p>
					<p><strong>Expected Clicks</strong> <?php echo $leads; ?></p>
					<p><strong>Start Date</strong> <?php echo $start; ?></p>
					<p><strong>End Date</strong> <?php echo ($recur)? "Ad will continue until cancelled" : $end; ?></p>
				</div>
				
				<div class="col-md-4">
				
					<h3>Purchase Information: </h3>
					<p><?php echo "$" . $subTotal; ?></p>
					<p>Tax: <?php echo "$" . $tax; ?></p>
					<p>Total: <?php echo "$" . number_format($total, 2); ?></p> 
				</div>
				<div class="col-md-2"></div>
				
				<div class="col-sm-12 text-center">
					<p><a href="/user/dashboard.php">Return to the campaign dashboard page</a></p>
				</div>
			</div>
		<?php endif; ?>
		
	</div>

	<?php require_once "../assets/_footer.php"; ?>
	
	<script>
	</script>
	</body>
	
</html>