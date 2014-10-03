<?php /* Smarty version 2.6.20, created on 2009-05-03 10:18:46
         compiled from handloan.tpl */ ?>
<?php echo $this->_tpl_vars['supplier_js_array']; ?>

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
	$("#handln_form").validate({
		rules: {
			handln_ref: {
				required: true
				},
			supplier:"required",
			handln_date: {
				required: true,
				dateIN: true
				}
			},
		messages:{
			handln_ref: {
				required: "Please enter handloan ref"
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
<div id="results"></div>
<form id="handln_form" name="handln_form" method="POST" action="handloan.php" class="yform">
	<fieldset>
		<legend>
			Handloan Details
		</legend>
		<table class = "type-input" width="100%" align="center">
		<tr>
			<td>
				Take From/Given To
			</td>
			<td>
				<input type="text" id="supplier" name="supplier" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_supplier.show_options(event);" onblur="javascript:eventobj_supplier.hide_options();" />
			</td>
		</tr>
		<tr>
			<td>
				 Reference
			</td>
			<td>
				<input type="text" id="handln_ref" name="handln_ref" value="" />
			</td>
			
		</tr>
		<tr>
			<td>
				Date
			</td>
			<td>
				<input type="text" id="handln_date" name="handln_date" value="<?php echo $this->_tpl_vars['handln_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset >
		<legend>Item Details<legend>
		<table id="handln_items" width="75%" align="center" cellpadding="0" cellspacing="0">
			<tr><?php if ($this->_tpl_vars['refer_array']): ?><th width="15%">Reference (if any)</th><?php endif; ?><th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price(Rs)</th><th width="10%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('handln_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>		
	<div class="type-button" align="center">
		<input type="submit" value="Save" name="save" />
	</div>
	<input type="hidden" name="handln_type" value="<?php echo $this->_tpl_vars['handln_type']; ?>
"/>

</form>

<?php echo '
<script type="text/javascript">
	var uom_arr = new Array();
	uom_arr = '; ?>
<?php echo $this->_tpl_vars['uom_array']; ?>
<?php echo ';

	'; ?>

	<?php if ($this->_tpl_vars['refer_array']): ?>
		<?php echo '
		var refer_arr = new Array();
		refer_arr = '; ?>
<?php echo $this->_tpl_vars['refer_array']; ?>
;
	<?php endif; ?>
	<?php echo '	
	
	addRow(\'handln_items\');

	eventobj_supplier = new AutoComplete(document.getElementById(\'supplier\'), \'1\', buy_array, \'eventobj_supplier\');
</script>
'; ?>