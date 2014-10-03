<?php /* Smarty version 2.6.20, created on 2009-05-02 22:21:18
         compiled from order.tpl */ ?>
<?php echo $this->_tpl_vars['relationship_js_array']; ?>

<?php echo $this->_tpl_vars['product_js_array']; ?>

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
	$("#order_form").validate({
		rules: {
			order_ref: {
				required: true
			},
			relationship:"required",
			payment_mode_id:"required",
			order_date: {
				required: true,
				dateIN: true
			}
		},
		messages: {
			order_ref: {
				required: "Please enter order ref"
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
<form id="order_form" name="order_form" method="POST" action="order.php" class="yform">
	<fieldset>
	<legend>Commercial Details</legend>
		<table class="type-input" width="100%" align="center">
		<tr>
			<td>
				<?php if ($this->_tpl_vars['activity'] == 'sel'): ?>
					Supplier
				<?php elseif ($this->_tpl_vars['activity'] == 'buy'): ?>
					Buyer
				<?php endif; ?>
			</td>
			<td>
				<input type="text" id="relationship" name="relationship" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_relationship.show_options(event);" onblur="javascript:eventobj_relationship.hide_options();" />
			</td>
			<td>
				Currency 
			</td>
			<td>
				<select id="currency_id" name="currency_id">
					<option value="">Select</option>
					<?php $_from = $this->_tpl_vars['currency_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['currency']):
?>
						<?php if ($this->_tpl_vars['currency']['currency_id'] == 1): ?>
							<option value="<?php echo $this->_tpl_vars['currency']['currency_id']; ?>
" selected><?php echo $this->_tpl_vars['currency']['currency_name']; ?>
</option>
						<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['currency']['currency_id']; ?>
"><?php echo $this->_tpl_vars['currency']['currency_name']; ?>
</option>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Order Reference
			</td>
			<td>
				<input type="text" id="order_ref" name="order_ref" value="" />
			</td>
			<td>
				Exchange Rate
			</td>
			<td>
				<input type="text" id="conversion_rate" name="conversion_rate" value="1" size="5" maxlength="5" />
			</td>
		</tr>
		<tr>
			<td>
				Order Date
			</td>
			<td>
				<input type="text" id="order_date" name="order_date" value="<?php echo $this->_tpl_vars['order_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br/>
	<fieldset>
		<legend>Item Details<legend>
		<table id="order_items" width="75%" align="center" cellpadding="0" cellspacing="0">
			<tr><th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price</th><th width="10%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('order_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>

	<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
	</div>
		
	<input type="hidden" name="activity" value="<?php echo $this->_tpl_vars['activity']; ?>
"/>
</form>

<?php echo '
<script type="text/javascript">
	var tax_arr = new Array();
	var uom_arr = new Array();

	tax_arr = '; ?>
<?php echo $this->_tpl_vars['tax_array']; ?>
<?php echo ';
	uom_arr	= '; ?>
<?php echo $this->_tpl_vars['uom_array']; ?>
<?php echo ';

	addRow(\'order_items\');
	eventobj_relationship = new AutoComplete(document.getElementById(\'relationship\'), \'1\','; ?>
<?php echo $this->_tpl_vars['activity']; ?>
_array<?php echo ', \'eventobj_relationship\');
</script>
'; ?>