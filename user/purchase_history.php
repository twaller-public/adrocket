<?php
	require_once "_purchase_history_ctrl.php";
	require_once "../assets/_header.php";
	
	//showme($campaigns[0]);
	//echo count($campaigns);
?>

<!-- main page content -->
<div class="container-fluid">
	<?php ?>
	
	<div class="row">
	
		<div class="col-lg-1"></div>
		

		
		
		
		<div class="col-lg-10 main-content dashboard" style="background-color:white;">
      <h1>Purchase History</h1>
			<div class="row">
				<div class="col-md-12">
				
					<div class="panel-group" id="campaign-panels" >
            <?php if(@$purchases): ?>
              <?php foreach($purchases as $purchase): ?>
  
              <div class="panel panel-default" style="margin-bottom:10px;">
              
                <div class="panel-heading">
                    <h5 class="panel-title" style="font-size:16px; padding:10px;">
                      <?php echo $purchase['createdDate']; ?><br>
                      <?php echo $purchase['campaign:label']; ?><br>
                      <?php echo $purchase['name']; ?><br>
                      Total: <?php echo $purchase['total']; ?><br>
                      Completed: <?php echo $purchase['completed:text']; ?>
                    </h5>
                </div>
                
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="panel panel-default" style="margin-bottom:10px;">
              
                <div class="panel-heading">
                    <h5 class="panel-title" style="font-size:16px; padding:10px;">
                      No purchase histoy
                    </h5>
                </div>
                
              </div>
            <?php endif; ?>

					</div>
				</div>
			</div>
		
			
		</div>
		
	</div>
</div>

<?php require_once "../assets/_footer.php"; ?>



</body>
</html>