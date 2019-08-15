<!-- CAMPAIGN FILTER FORM -->
<div class="row">
	<div class="col-md-12">
		<h2>Your Campaigns</h2>
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
				</table>
				<div class="panel-body" style="padding:10px 10px">
					<button class="btn btn-primary" type="submit" name="cpn-filter" value="1" style="margin-top:15px;">Go</button>
					<button class="btn btn-default" type="submit" name="reset-filter" value="1" style="margin-top:15px;">Reset</button>
				</div>
			</form>
		</div>
	</div>
</div>