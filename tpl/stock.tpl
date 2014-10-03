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
{/literal}
{if !empty($message)}
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'>{$message}</td></tr>
	</table>
{/if}
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
				Transaction Date
			</td>
			<td>
				<input type="text" id="transaction_date" name="transaction_date" value="{$transaction_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
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

{literal}
<script type="text/javascript">
	var uom_arr = new Array();
	uom_arr = {/literal}{$uom_array}{literal};

	addRow('stock_items');
</script>
{/literal}