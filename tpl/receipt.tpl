{$supplier_js_array}
{$product_js_array}
<script src="{$offset_path}/lib/js/autocomplete.js"></script>
<script src="{$offset_path}/lib/js/epochprime_classes.js"></script>
<script src="{$offset_path}/lib/js/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="{$offset_path}/lib/css/epochprime_styles.css" />
{literal}
<script type="text/javascript">
window.onload = function()
{
	var xml = '<configs><initcfg><mode>popup</mode></initcfg></configs>';
	popup = new EpochPrime('',xml);
};
</script>
{/literal}
{literal}
<script type="text/javascript">
$().ready(function() {
	// validate the comment form when it is submitted
//	$("#receipt_form").validate();
	
	// validate signup form on keyup and submit
	$("#receipt_form").validate({
		rules: {
			receipt_ref: {
				required: true
			},
			supplier:"required",
			receipt_date: {
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
			receipt_ref: {
				required: "Please enter receipt ref"
			}
		}
	});
});

</script>
{/literal}
{if !empty($message)}
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'>{$message}</td></tr>
	</table>
{/if}
<form id="receipt_form" name="receipt_form" method="POST" action="receipt.php" class="yform">
	<fieldset>
		<legend>
			Commercial Details
		</legend>
	<table class = "type-input" width="100%" align="center">
		<tr>
			<td>
				Supplier
			</td>
			<td>
				<input type="text" id="supplier" name="supplier" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_supplier.show_options(event);" onblur="javascript:eventobj_supplier.hide_options();" onchange="xajax_alertOrdersInfo('sel',this.value);"/>
			</td>
			<td>
				Currency
			</td>
			<td>
				<select name="currency_id">
					<option value="">Select</option>
					{foreach from=$currency_array item="currency"}
						{if $currency.currency_id eq 1}
							<option value="{$currency.currency_id}" selected>{$currency.currency_name}</option>
						{else}
							<option value="{$currency.currency_id}">{$currency.currency_name}</option>
						{/if}
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Receipt Reference
			</td>
			<td>
				<input type="text" id="receipt_ref" name="receipt_ref" value="" />
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
				Receipt Date
			</td>
			<td>
				<input type="text" id="receipt_date" name="receipt_date" value="{$receipt_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset >
		<legend>Item Details<legend>
		<table id="receipt_items" width="75%" align="center" cellpadding="0" cellspacing="0">
			<tr>{if $refer_array}<th width="15%">Indents(if any)</th>{/if}<th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price</th><th width="10%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('receipt_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>
	<fieldset >
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

{literal}
<script type="text/javascript">
	var tax_arr = new Array();
	var uom_arr = new Array();

	{/literal}
	{if $refer_array}
		{literal}
		var refer_arr = new Array();
		refer_arr = {/literal}{$refer_array};
	{/if}
	{literal}

	tax_arr = {/literal}{$tax_array}{literal};
	uom_arr = {/literal}{$uom_array}{literal};

	addRow('receipt_items');
	//addRow('tax_items','tax_table');
	eventobj_supplier = new AutoComplete(document.getElementById('supplier'), '1', sel_array, 'eventobj_supplier');
</script>
{/literal}