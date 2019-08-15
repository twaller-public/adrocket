<?php
	require_once "_list_ctrl.php";
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
		
			
			
			<!-- CAMPAIGN FILTER FORM -->
			<?php require "../assets/_cpn_filter_form.php"; ?>
			
			
			
			
			<!-- CAMPAING LISTING -->
			<div class="row">
				<div class="col-md-12">
				
					<div class="panel-group" id="campaign-panels" style="display:none;">
						
						<?php dashboard_listCampaignPanels($campaigns); ?>
					</div>
				</div>
			</div>
			
			
			<!-- PAGINATION -->
			<?php require "../assets/_pagination.php"; ?>
			
			
		</div>
		
	</div>
</div>

<?php require_once "../assets/_footer.php"; ?>


<script>

$(function(){
	
	$('.collapse').collapse();
	
	$('#campaign-panels').show("slow");
});
</script>

</body>
</html>