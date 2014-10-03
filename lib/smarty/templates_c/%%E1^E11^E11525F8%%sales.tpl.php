<?php /* Smarty version 2.6.20, created on 2009-05-19 09:05:28
         compiled from sales.tpl */ ?>
<?php echo $this->_tpl_vars['buyer_js_array']; ?>

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
//	$("#sales_form").validate();
	
	// validate signup form on keyup and submit
	$("#sales_form").validate({
		rules: {
			sales_ref: {
				required: true
			},
			buyer:"required",
			sales_date: {
				required: true,
				dateIN: true
			},
			conversion_rate: {
				required: true,
				number: true
			},
			currency_id:"required"
		},
		messages: {
			sales_ref: {
				required: "Please enter sales ref"
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
<form id="sales_form" name="sales_form" method="POST" action="sales.php" class="yform">
	<fieldset>
	<legend>Commercial Details</legend>
		<table class = "type-input" width="100%" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				Buyer
			</td>
			<td>
				<input type="text" id="buyer" name="buyer" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_buyer.show_options(event);" onblur="javascript:eventobj_buyer.hide_options();"  onchange="xajax_alertOrdersInfo('buy',this.value);"/>
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
				Sales Reference
			</td>
			<td>
				<input type="text" id="sales_ref" name="sales_ref" value="" />
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
				Sales Date
			</td>
			<td>
				<input type="text" id="sales_date" name="sales_date" value="<?php echo $this->_tpl_vars['sales_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br/>
	<fieldset>
		<legend>Item Details<legend>
		<table id="sales_items" width="75%" align="center" cellpadding="0" cellspacing="0">
			<tr><?php if ($this->_tpl_vars['refer_array']): ?><th width="15%">Orders(if any)</th><?php endif; ?><th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price</th><th width="10%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('sales_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Tax Details<legend>
		<table id="tax_items" width="40%" align="center" cellpadding="0" cellspacing="0">
			<tr><th width="10%">Tax</th><th width="5%">Rate</th><th width="5%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_tax" id="add_tax" value="Add" onclick="addRow('tax_items','tax_table');document.getElementById('tax_id['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>
	
	<div class="type-button" align="center">
		<input type="submit" value="Save" name="save" />
	</div>

</form>
<?php echo '
<script type="text/javascript">
	var tax_arr = new Array();
	var uom_arr = new Array();
	
	'; ?>

	<?php if ($this->_tpl_vars['refer_array']): ?>
		<?php echo '
		var refer_arr = new Array();
		refer_arr = '; ?>
<?php echo $this->_tpl_vars['refer_array']; ?>
;
	<?php endif; ?>
	<?php echo '
	
	tax_arr = '; ?>
<?php echo $this->_tpl_vars['tax_array']; ?>
<?php echo ';
	uom_arr	= '; ?>
<?php echo $this->_tpl_vars['uom_array']; ?>
<?php echo ';

	addRow(\'sales_items\');
	eventobj_buyer = new AutoComplete(document.getElementById(\'buyer\'), \'1\', buy_array, \'eventobj_buyer\');
</script>
'; ?>