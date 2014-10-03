<?php /* Smarty version 2.6.20, created on 2009-05-03 10:40:45
         compiled from stock.tpl */ ?>
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
	$("#stock_form").validate({
		rules: {
			stock_ref: {
				required: true
			},
			transaction_date: {
				required: true,
				dateIN: true
			}
		},
		messages: {
			stock_ref: {
				required: "Please enter ref"
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
<form id="stock_form" name="payment_form" method="POST" action="stock.php" class="yform">
	<fieldset>
	<legend>Stock Details</legend>
		<table class="type-input" width="100%" align="center">
		<tr>
			<td>
				Opening/Adjustment Reference
			</td>
			<td>
				<input type="text" id="stock_ref" name="stock_ref" value=""/>
			</td>
			<td>
				Currency
			</td>
			<td>
				<select name="currency_id">
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
				Transaction Date
			</td>
			<td>
				<input type="text" id="transaction_date" name="transaction_date" value="<?php echo $this->_tpl_vars['transaction_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
			<td>
				Exchange Rate
			</td>
			<td>
				<input type="text" id="conversion_rate" name="conversion_rate" value="1" size="5" maxlength="5" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br/>
	<fieldset >
		<legend>Item Details<legend>
		<table id="stock_items" width="75%" align="center" cellpadding="0" cellspacing="0">
			<tr><th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price</th><th width="10%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('stock_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>
	<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
	</div>
</form>

<?php echo '
<script type="text/javascript">
	var uom_arr = new Array();
	uom_arr = '; ?>
<?php echo $this->_tpl_vars['uom_array']; ?>
<?php echo ';

	addRow(\'stock_items\');
</script>
'; ?>