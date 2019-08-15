<div class="col-sm-12 checkout-wrap" style="padding:20px;">

	<h4><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Total window text"]; ?></h4>
	<hr />
	
	<?php
	
		$disabled = "";
	
		//campaign settings not complete
		if(!$user_completed){
			
			$disabled = " disabled";
			echo "<div class='alert alert-warning'>" . $GLOBALS["ADROCKET_DEFINITIONS"]["Campaign not complete by user checkout text"] . "</div>";
		}
		else{
			
			if($CURRENT_USER) echo "<div class='alert alert-success'>" . $GLOBALS["ADROCKET_DEFINITIONS"]["Campaign complete by user checkout text"] . "</div>";
			else echo "<div class='alert alert-success'>" . $GLOBALS["ADROCKET_DEFINITIONS"]["Campaign complete by guest checkout text"] . "</div>";
		}
	?>
	
	
	<table class="table table-condensed table-checkout">
	
		<tbody>
			<tr>
				<td><label class="budget-label"><?php echo ($daystimes->get("recur"))? $GLOBALS["ADROCKET_DEFINITIONS"]["Price Text Recurring"] : $GLOBALS["ADROCKET_DEFINITIONS"]["Price Text"]; ?>:</label></td>
				<td><span class="budget-value pull-right"><?php echo (@$budget? "$".number_format($budget, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span></td>
			</tr>
			
			<tr>
				<td><label class="fee-label">Management Fee:</label></td>
				<td><span class="fee-value pull-right"><?php echo (@$mgmtFee? "$".number_format($mgmtFee, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span></td>
			</tr>
			
			<tr><td colspan="2"><hr /></td></tr>
			
			<tr>
				<td><label class="fee-label">Subtotal:</label></td>
				<td><span class="fee-value pull-right"><?php echo (@$subTotal? "$".number_format($subTotal, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span></td>
			</tr>
			
			<tr>
				<td><label class="tax-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Tax Text"]; ?>:</label></td>
				<td><span class="tax-value pull-right"><?php echo (@$tax)? "$" . number_format($tax, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span></td>
			</tr>
			
			<tr>
				<td><label class="total-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Total Text"]; ?>:</label></td>
				<td><span class="total-value pull-right"><?php echo (@$total)? "$" . $total : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span></td>
			</tr>

		</tbody>
		
	</table>
	
	<?php /*
	<label class="budget-label"><?php echo ($daystimes->get("recur"))? $GLOBALS["ADROCKET_DEFINITIONS"]["Price Text Recurring"] : $GLOBALS["ADROCKET_DEFINITIONS"]["Price Text"]; ?>:</label>
	<span class="budget-value pull-right"><?php echo (@$budget? "$".number_format($budget, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
	<br />
	
	
	<label class="fee-label">Management Fee:</label>
	<span class="fee-value pull-right"><?php echo (@$mgmtFee? "$".number_format($mgmtFee, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
	<hr />
	
	<label class="fee-label">Subtotal:</label>
	<span class="fee-value pull-right"><?php echo (@$subTotal? "$".number_format($subTotal, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
	<br />
	
	<?php if($CURRENT_USER) : ?>
		<label class="tax-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Tax Text"]; ?>:</label>
		<span class="tax-value pull-right"><?php echo (@$tax)? "$" . number_format($tax, 2) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
		<br />
		
		<label class="total-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Total Text"]; ?>:</label>
		<span class="total-value pull-right"><?php echo (@$total)? "$" . $total : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
		<br />
	<?php endif; ?>
	<br />
	
	*/ ?>
	
	<div class="text-right">
		<?php if($CURRENT_USER) : ?>
			<button type="button" class="btn-lg btn-primary checkout"<?php echo $disabled; ?>><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Checkout button text"]; ?></button>
		<?php else : ?>
			<button type="button" class="btn-lg btn-primary signup"<?php echo $disabled; ?>>Create Account/Checkout</button>
		<?php endif; ?>
	</div>
	
</div>