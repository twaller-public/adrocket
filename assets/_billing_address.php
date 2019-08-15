<div class="form-group">
	<label>Address 1<span class="red-ast">*</span></label>
	<input class="form-control" type="text" name="addr_1"  value="<?php echo htmlencode(@$_REQUEST['addr_1']); ?>" />
</div>

<div class="form-group">
	<label>Address 2</label>
	<input class="form-control" type="text" name="addr_2"  value="<?php echo htmlencode(@$_REQUEST['addr_2']); ?>" />
</div>

<div class="form-group">
	<label>City<span class="red-ast">*</span></label>
	<input class="form-control" type="text" name="city"  value="<?php echo htmlencode(@$_REQUEST['city']); ?>" style="width:200px;" />
</div>

<div class="form-group">
	<label>Potal Code<span class="red-ast">*</span></label>
	<input class="form-control" type="text" name="postal"  value="<?php echo htmlencode(@$_REQUEST['postal']); ?>" style="width:120px;" />
</div>

<div class="form-group">
	<label>Country<span class="red-ast">*</span></label>
	<select class="form-control" name="country" readonly>

			<option value="Canada" selected='selected'>Canada</option>
	</select>
</div>

<div class="form-group">
	<label>Province<span class="red-ast">*</span></label>
	<select class="form-control" name="province">
		<?php echo $provinces; ?>
	</select>
</div>