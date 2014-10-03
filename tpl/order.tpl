{$relationship_js_array}
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
{/literal}
{if !empty($message)}
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'>{$message}</td></tr>
	</table>
{/if}
<form id="order_form" name="order_form" method="POST" action="order.php" class="yform">
	<fieldset>
	<legend>Commercial Details</legend>
		<table class="type-input" width="100%" align="center">
		<tr>
			<td>
				{if $activity eq 'sel'}
					Supplier
				{elseif $activity eq 'buy'}
					Buyer
				{/if}
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
				<input type="text" id="order_date" name="order_date" value="{$order_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
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
		
	<input type="hidden" name="activity" value="{$activity}"/>
</form>

{literal}
<script type="text/javascript">
	var tax_arr = new Array();
	var uom_arr = new Array();

	tax_arr = {/literal}{$tax_array}{literal};
	uom_arr	= {/literal}{$uom_array}{literal};

	addRow('order_items');
	eventobj_relationship = new AutoComplete(document.getElementById('relationship'), '1',{/literal}{$activity}_array{literal}, 'eventobj_relationship');
</script>
{/literal}