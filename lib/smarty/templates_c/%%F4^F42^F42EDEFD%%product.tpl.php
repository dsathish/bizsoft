<?php /* Smarty version 2.6.20, created on 2009-05-28 10:21:58
         compiled from product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'product.tpl', 129, false),)), $this); ?>
<?php echo $this->_tpl_vars['product_js_array']; ?>

<?php echo $this->_tpl_vars['product_category_js_array']; ?>


<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/autocomplete.js"></script>
<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquery.validate.js"></script>
<?php echo '
<script type="text/javascript">
$().ready(function() {

	// validate signup form on keyup and submit
	$("#product_form").validate({
		rules: {
			product_desc: {
				required: true
			},
			product_category: {
				required: true
			},
			cost_price: {
				digits: true
			},
			selling_price: {
				digits: true
			},
			},
		messages: {
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
<form id="product_form" name="product_form" method="POST" action="product.php" class="yform">
	<fieldset>
	<legend>Product Details<legend>
	<table class="type-input" width="100%" align="center">
		<tr>
			<td>
				Name
			</td>
			<td colspan="2">
				<input type="text" id="product_desc" name="product_desc" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_product.show_options(event);" onblur="javascript:eventobj_product.hide_options();" value="<?php echo $this->_tpl_vars['product_desc']; ?>
"/>
			</td>	
			<td>
				Category
			</td>
			<td colspan="2">
				<input type="text" id="product_category" name="product_category" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_category.show_options(event);" onblur="javascript:eventobj_category.hide_options();" value="<?php echo $this->_tpl_vars['product_category']; ?>
"/>
			</td>
		</tr>
		<tr>	
			<td>
				Literature
			</td>
			<td colspan="2">
				<input type="file" name="product_lit" value="<?php echo $this->_tpl_vars['product_lit']; ?>
"/>
			</td>
		</tr>
		<tr>
			<td>
				Cost Price
			</td>
			<td width="20px">
				<input type="text" id="cost_price" name="cost_price" size="8" value="<?php echo $this->_tpl_vars['cost_price']; ?>
"/>
			</td>
			<td>
				<select name="cp_currency_id" style="width:70px;">
						<?php $_from = $this->_tpl_vars['currency_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['currency']):
?>
						<?php if ($this->_tpl_vars['currency']['cp_currency_id'] == 1): ?>
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
			<td>
				Selling Price
			</td>
			<td width="20px">
				<input type="text" id="selling_price" name="selling_price" size="8" value="<?php echo $this->_tpl_vars['selling_price']; ?>
"/>
			</td>
			<td>
				<select name="sp_currency_id" style="width:70px;">
					<?php $_from = $this->_tpl_vars['currency_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['currency']):
?>
						<?php if ($this->_tpl_vars['currency']['sp_currency_id'] == 1): ?>
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
				Reorder (Kgs)
			</td>
			<td colspan="2">
				<input type="text" id="reorder" name="reorder" size="8" value="<?php echo $this->_tpl_vars['reorder']; ?>
"/>
			</td>
		</tr>
	</table>
	</fieldset>
	<input type="hidden" name="product_id" value="<?php echo $this->_tpl_vars['product_id']; ?>
"/>
	<input type="hidden" name="action" value="<?php echo $this->_tpl_vars['action']; ?>
"/>
	<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
	</div>

</form>

<?php if ($this->_tpl_vars['product_details']): ?>
	<br />
	<table id="report_data" align="center" width="80%">
	<caption>Product Details</caption>
		<tr>
		<?php $_from = $this->_tpl_vars['product_details'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
			<th><?php echo $this->_tpl_vars['column_name']; ?>
</th>
		<?php endforeach; endif; unset($_from); ?>
		</tr>
		<?php $_from = $this->_tpl_vars['product_details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product_array']):
?>
		<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#ffffff,#F3F5EE"), $this);?>
">
			<?php $_from = $this->_tpl_vars['product_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
				<td>
					<?php echo $this->_tpl_vars['data']; ?>

				</td>
			<?php endforeach; endif; unset($_from); ?>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
<?php endif; ?>

<?php echo '
<script type="text/javascript">
	eventobj_product = new AutoComplete(document.getElementById(\'product_desc\'), \'1\', product_array, \'eventobj_product\');
	eventobj_category = new AutoComplete(document.getElementById(\'product_category\'), \'1\', product_category_array, \'eventobj_category\');
</script>
'; ?>

