<?php
	require_once "_dashboard_ctrl.php";
	require_once "../assets/_header.php";
	
	//showme($campaigns[0]);
	//echo count($campaigns);
?>

<!-- main page content -->
<div class="container-fluid">
	<?php ?>
	
	
	<div class="col-sm-3 left-sidebar" style="position:sticky; top:0;">
	
		<!-- SIDEBAR LINKS -->

		<ul class="dashboard-side-menu" style="font-size:16px;">
			<!-- <li><input type="checkbox" checked data-toggle="toggle" /></li> -->
			<li><a href="/campaign/create.php">Available Managers: <?php echo count($available_managers); ?></a></li>
			<li><a href="/campaign/create.php">Create Campaign</a></li>
			<li><a href="#">Purchases</a></li>
			<li><a href="#">Learning Center</a></li>
			<li><a href="#">ROI Calculator</a></li>
		</ul>
	</div>
	
	<div class="col-sm-7 main-content dashboard">
	
		<div style="color: #C00; font-weight: bold; font-size: 16px;">
			<?php echo $alerts; ?>
		</div>
		
		<!-- CAMPAIGN FILTER FORM -->
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Search:</strong></div>
		
			<form class="cpn-filter" action="?" method="POST">
				<table class="table" style="margin-bottom:0;">
					<tr>
						<th>Title:<br /><input type="text" name="filter-title" placeholder="Title" value="<?php echo htmlentities(@$_REQUEST["filter-title"]); ?>" style="width:130px;"/></th>
						<th>Vendor:<br />
							<select name="filter-vendor">
								<?php echo $vendor_opts; ?>
							</select>
						</th>
						<th>Status:<br />
							<select name="filter-campaign_status">
								<?php echo $status_opts; ?>
							</select>
						</th>
					</tr>
					<tr>
						<td colspan="3"><input type="checkbox" name="omit-user-progress" value="1" <?php if(@$_REQUEST['omit-user-progress'] == 1) echo " checked"; ?> />&nbsp;Omit Campaigns Under User Construction</td>
					</tr>
				</table>
				<div class="panel-body" style="padding:0px 10px">
					<button class="btn btn-primary" type="submit" name="cpn-filter" value="1" style="margin-top:15px;">Go</button>
					<button class="btn btn-default" type="submit" name="reset-filter" value="1" style="margin-top:15px;">Reset</button>
				</div>
			</form>
		</div>
		
		
		<div class="panel-group" id="campaign-panels" style="display:none;">
			<!-- CAMPAING LISTING -->
			<?php 
				foreach($campaigns as $c){
				
					$num       = $c[0]->get("num");
					$created   = $c[0]->get("createdDate");
					$status    = $c[0]->get("admin_status:label");
					$stat_num  = $c[0]->get("admin_status");
					$title     = $c[0]->get("title")?: "[Title not set]";
					$vendor    = $c[0]->get("vendor:label")?: "[Vendor not chosen]";
					$logo      = $c["vendor_logo"];
					$is_active = $c[0]->get("is_active:text");
					$active    = $c[0]->get("is_active");
					$start     = $c[1]->get("start_date");
					$end       = $c[1]->get("end_date");
					$url       = $c[0]->get("management_url");
					
					$userName  = $c["user_name"];
					
					
					if(!$start || $start == "0000-00-00 00:00:00") $start = "[None]";
					else $start = date("D, M jS Y", strtotime($start));
					
					if(!$end || $end == "0000-00-00 00:00:00") $end = "[None]";
					else $end = date("D, M jS Y", strtotime($end));
					
					if($end == "[None]" && $c[1]->get("recur")) $end = "Recurring";
					
					include "../assets/_campaign_panel.php";
				}
			?>
		</div>
		
		
		<!-- PAGINATION -->
		<div class="pages text-center">
		
			<ul class="pagination">
				<li><a href="#">&laquo;</a></li>
				<li><a href="#">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><a href="#">4</a></li>
				<li><a href="#">5</a></li>
				<li><a href="#">&raquo;</a></li>
			</ul>
		</div>
		
	</div>
	
	<!--<div class="col-sm-2 right-sidebar"></div>-->
</div>

<?php 
	require_once "../assets/_footer.php"; 
	include_once "../assets/modal/manager-assign-modal.php";
?>

<script>

$(function(){
	
	$('.collapse').collapse();
	$('#campaign-panels').show("slow");
});
</script>

</body>
</html>