<?php /* Smarty version 2.6.20, created on 2009-04-28 20:21:00
         compiled from test.tpl */ ?>
<fieldset >
	<legend>Item Details<legend>
	<table id="handln_items" width="75%" align="center" cellpadding="0" cellspacing="0">
		<tr><th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price</th><th width="10%">Action</th></tr>
		<tbody></tbody>
		<tr><td colspan="4">
			<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('handln_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
		</td></tr>
	</table>
</fieldset>		
<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
</div>
<?php echo '
<script type="text/javascript">
	addRow(\'handln_items\');
</script>
'; ?>