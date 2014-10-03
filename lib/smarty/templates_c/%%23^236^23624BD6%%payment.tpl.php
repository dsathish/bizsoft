<?php /* Smarty version 2.6.20, created on 2009-05-02 22:48:10
         compiled from payment.tpl */ ?>
<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/autocomplete.js"></script>
<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/epochprime_classes.js"></script>
<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/css/epochprime_styles.css" />
<?php echo '
<script type="text/javascript">
window.onload = function()
{
	var xml = \'<configs><initcfg><mode>popup</mode></initcfg></configs>\';
	popup = new EpochPrime(\'\',xml);
};
</script>
'; ?>

<?php echo '
<script type="text/javascript">
$().ready(function() {
	// validate the comment form when it is submitted
//	$("#receipt_form").validate();
	
	// validate signup form on keyup and submit
	$("#payment_form").validate({
		rules: {
			payment_ref: {
				required: true
			},
			relationship:"required",
			payment_mode_id:"required",
			payment_date: {
				required: true,
				dateIN: true
			}
		},
		messages: {
			receipt_ref: {
				required: "Please enter receipt ref"
			}
		}
	});
});

</script>
'; ?>

<?php if (! empty ( $this->_tpl_vars['message'] )): ?>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'><?php echo $this->_tpl_vars['message']; ?>
</td></tr>
	</table>
<?php endif; ?>
<form id="payment_form" name="payment_form" method="POST" action="payment.php" class="yform">
	<fieldset>
	<legend>Commercial Details</legend>
		<table class="type-input" width="100%" align="center">
		<tr>
			<td class="tHead">
				<?php if ($this->_tpl_vars['action'] == 'sel'): ?>
					Supplier
				<?php elseif ($this->_tpl_vars['action'] == 'buy'): ?>
					Buyer
				<?php endif; ?>
			</td>
			<td>
				<select name="relationship_id" style="width:200px;" onchange="<?php echo 'if(this.value>0){this.form.submit();}'; ?>
">
					<option value="">Select</option>
					<?php $_from = $this->_tpl_vars['relationship_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['relationship']):
?>
						<?php if ($this->_tpl_vars['_post']['relationship_id'] == $this->_tpl_vars['relationship']['relationship_id']): ?>			
							<option value="<?php echo $this->_tpl_vars['relationship']['relationship_id']; ?>
" selected><?php echo $this->_tpl_vars['relationship']['relationship_name']; ?>
</option>
						<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['relationship']['relationship_id']; ?>
"><?php echo $this->_tpl_vars['relationship']['relationship_name']; ?>
</option>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
			<td class="tHead">
				Payment Mode
			</td>
			<td>
				<select name="payment_mode_id">
					<option value="">Select</option>
					<?php $_from = $this->_tpl_vars['payment_mode_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment_mode']):
?>
						<option value="<?php echo $this->_tpl_vars['payment_mode']['payment_mode_id']; ?>
" ><?php echo $this->_tpl_vars['payment_mode']['payment_mode_name']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tHead">
				Payment Reference
			</td>
			<td>
				<input type="text" id="payment_ref" name="payment_ref" value="" />
			</td>
			<td class="tHead">
				Payment Date
			</td>
			<td>
				<input type="text" id="payment_date" name="payment_date" value="<?php echo $this->_tpl_vars['payment_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br />
	<?php if ($this->_tpl_vars['payment_reference_array'][0]['refer_id']): ?>
		<fieldset>

		<?php if ($this->_tpl_vars['action'] == 'sel'): ?>
			<legend>Supplier Item Details</legend>
		<?php elseif ($this->_tpl_vars['action'] == 'buy'): ?>
			<legend>Buyer Item Details</legend>
		<?php endif; ?>
		
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<table id="payment_items" width="80%" align="center" cellpadding="0" cellspacing="0">
					<?php if ($this->_tpl_vars['action'] == 'sel'): ?>
						<tr>	
							<th width="30%">Receipt Reference</th>
							<th width="20%">Amount</th>
							<th width="20%">Deductions</th>
							<th>Action</th>
						</tr>
					<?php elseif ($this->_tpl_vars['action'] == 'buy'): ?>
						<tr>	
							<th width="30%">Sales Reference</th>
							<th width="10%">Amount</th>
							<th width="10%">Deductions</th>
							<th width="10%">Action</th>
						</tr>
					<?php endif; ?>
					<tbody></tbody>
					<tr>
					<td colspan="4">
						<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('payment_items','payment');document.getElementById('payment_items['+(row_id - 1)+']').focus();" />
					</td></tr>
				</table>
			</tr>
		</table>
		</fieldset>
		<div class="type-button" align="center">
		<input type="submit" value="Save" name="save" />
		</div>

	<?php elseif ($this->_tpl_vars['_post']): ?>
		<blink class="error" text-align="center">No Pending Payments!!!</blink>
	<?php endif; ?>
		
	<input type="hidden" name="action" value="<?php echo $this->_tpl_vars['action']; ?>
"/>
</form>

<?php echo '
<script type="text/javascript">
	var refer_arr = new Array();
	refer_arr = '; ?>
<?php echo $this->_tpl_vars['js_refer_array']; ?>
<?php echo ';
'; ?>

	<?php if ($this->_tpl_vars['payment_reference_array'][0]['refer_id']): ?>
		<?php echo ' addRow(\'payment_items\',\'payment\'); '; ?>

	<?php endif; ?>
<?php echo '
</script>
'; ?>