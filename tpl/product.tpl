{$product_js_array}
{$product_category_js_array}

<script src="{$offset_path}/lib/js/autocomplete.js"></script>
<script src="{$offset_path}/lib/js/jquery.validate.js"></script>
{literal}
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
{/literal}
{if !empty($message)}
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'>{$message}</td></tr>
	</table>
{/if}
<form id="product_form" name="product_form" method="POST" action="product.php" class="yform">
	<fieldset>
	<legend>Product Details<legend>
	<table class="type-input" width="100%" align="center">
		<tr>
			<td>
				Name
			</td>
			<td colspan="2">
				<input type="text" id="product_desc" name="product_desc" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_product.show_options(event);" onblur="javascript:eventobj_product.hide_options();" value="{$product_desc}"/>
			</td>	
			<td>
				Category
			</td>
			<td colspan="2">
				<input type="text" id="product_category" name="product_category" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_category.show_options(event);" onblur="javascript:eventobj_category.hide_options();" value="{$product_category}"/>
			</td>
		</tr>
		<tr>	
			<td>
				Literature
			</td>
			<td colspan="2">
				<input type="file" name="product_lit" value="{$product_lit}"/>
			</td>
		</tr>
		<tr>
			<td>
				Cost Price
			</td>
			<td width="20px">
				<input type="text" id="cost_price" name="cost_price" size="8" value="{$cost_price}"/>
			</td>
			<td>
				<select name="cp_currency_id" style="width:70px;">
						{foreach from=$currency_array item="currency"}
						{if $currency.cp_currency_id eq 1}
							<option value="{$currency.currency_id}" selected>{$currency.currency_name}</option>
						{else}
							<option value="{$currency.currency_id}">{$currency.currency_name}</option>
						{/if}
					{/foreach}
				</select>
			</td>
			<td>
				Selling Price
			</td>
			<td width="20px">
				<input type="text" id="selling_price" name="selling_price" size="8" value="{$selling_price}"/>
			</td>
			<td>
				<select name="sp_currency_id" style="width:70px;">
					{foreach from=$currency_array item="currency"}
						{if $currency.sp_currency_id eq 1}
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
				Reorder (Kgs)
			</td>
			<td colspan="2">
				<input type="text" id="reorder" name="reorder" size="8" value="{$reorder}"/>
			</td>
		</tr>
	</table>
	</fieldset>
	<input type="hidden" name="product_id" value="{$product_id}"/>
	<input type="hidden" name="action" value="{$action}"/>
	<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
	</div>

</form>

{if $product_details}
	<br />
	<table id="report_data" align="center" width="80%">
	<caption>Product Details</caption>
		<tr>
		{foreach from=$product_details[0] key="column_name" item="data"}
			<th>{$column_name}</th>
		{/foreach}
		</tr>
		{foreach from=$product_details item="product_array"}
		<tr bgcolor="{cycle values="#ffffff,#F3F5EE"}">
			{foreach from=$product_array key="column_name" item="data"}
				<td>
					{$data}
				</td>
			{/foreach}
		</tr>
		{/foreach}
	</table>
{/if}

{literal}
<script type="text/javascript">
	eventobj_product = new AutoComplete(document.getElementById('product_desc'), '1', product_array, 'eventobj_product');
	eventobj_category = new AutoComplete(document.getElementById('product_category'), '1', product_category_array, 'eventobj_category');
</script>
{/literal}

