<script src="{$offset_path}/lib/js/jquery.validate.js"></script>
{literal}
<script type="text/javascript">
$().ready(function() {
	// validate signup form on keyup and submit
	$("#customization_form").validate({
		rules: {
			customization_name: {
				required: true
			},
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
			customization_name: {
				required: "Enter customization name"
			}
		}
	});
});

</script>
{/literal}

<form id="customization_form" name="customization_form" method="POST" action="customizations.php">
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>Customization Name</td>
		<td>
			{if $action eq 'edit'}
				<input type="text" id="customization_name" name="customization_name" value="{$customization_array.customization_name}" size="30" />
				<input type="hidden" name="customization_id" value="{$customization_array.customization_id}" size="5" />
			{else}
				<input type="text" id="customization_name" name="customization_name" size="30" />
			{/if}
			<input type="hidden" name="report_id" value="{$customization_array.report_id}" size="5" />
			<input type="hidden" name="date_column" value="{$customization_array.date_column}" size="5" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			{if $action eq 'edit' or $action eq 'copy'}
				Default : <input type="checkbox" name="is_default" value="1" {if $customization_array.is_default eq 1}checked{/if} />&nbsp;&nbsp;
				Sub Total : <input type="checkbox" name="sub_total" value="1" {if $customization_array.sub_total}checked{/if} />&nbsp;&nbsp;
				Grand Total : <input type="checkbox" name="grand_total" value="1" {if $customization_array.grand_total}checked{/if} />&nbsp;&nbsp;
			{else}
				Default : <input type="checkbox" name="is_default" value="1" />&nbsp;&nbsp;
				Sub Total : <input type="checkbox" name="sub_total" value="1" />&nbsp;&nbsp;
				Grand Total : <input type="checkbox" name="grand_total" value="1" />&nbsp;&nbsp;
			{/if}
		</td>
	</tr>
</table>
<br />
<table width="80%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<th>Column</th>
		<th>Column Name</th>
		<th>Display Name</th>
		<th>Group</th>
		<th>Need Sum</th>
		<th>Filter</th>
		<th>Display Order</th>
	</tr>
	{foreach from=$columns_array key=key item="columns"}
	<tr>
		<td>
			{$columns.column_name}
		</td>
		<td>
			{$columns.default_display_name}
			<input type="hidden" name="column_id[]" value="{$columns.column_id}" size="5" />
		</td>
		<td>
			<input type="text" id="display_name[{$key}]" name="display_name[{$key}]" value="{$columns.display_name}" class="required" />
		</td>
		<td>
			{if $columns.default_is_group ne ''}
				<input type="checkbox" name="is_group[{$columns.column_id}]" value="1" {if $columns.is_group}checked{/if} />
			{/if}
		</td>
		<td>
			{if $columns.default_display_total ne ''}
				<input type="checkbox" name="display_total[{$columns.column_id}]" value="1" {if $columns.display_total}checked{/if} />
			{/if}
		</td>
		<td>
			{if $columns.default_is_filter ne ''}
				<input type="checkbox" name="is_filter[{$columns.column_id}]" value="1" {if $columns.is_filter}checked{/if} />
			{/if}
		</td>
		<td>
			<input type="text" id="display_order[{$key}]" name="display_order[{$key}]" value="{if $columns.display_order}{$columns.display_order}{/if}" size="5" maxlength="4" class="number" />

			<input type="hidden" name="sort_order[]" value="{$columns.sort_order}" />
			<input type="hidden" name="decimal_places[]" value="{$columns.decimal_places}" />
			<input type="hidden" name="date_format[]" value="{$columns.date_format}" />
			<input type="hidden" name="style[]" value="{$columns.style}" />
		</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="6" align="center">
			<input type="submit" name="save" value="Save" />
		</td>
	</tr>
</table>
</form>