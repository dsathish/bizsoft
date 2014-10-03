<form id="customization_form" name="customization_form" method="POST" action="filters.php?report_id={$report_id}&customization_id={$customization_id}">
{if $filters_array}
	<table align="center" cellpadding="0" cellspacing="0">
	<tr>
	{foreach from=$filters_array item=values}
		<td align="center" cellpadding="0" cellspacing="0">
			<table>
				<tr><th>{$values.display_name}</th></tr>
				<tr><td>
					<select name="column_id[{$values.column_id}][]" style="width:150px;" size="4" multiple>
						<option value="" {$values.all}>All</option>
						{foreach from=$values.filter_values item=options}
							<option {$options.selected}>{$options.value}</option>
						{/foreach}
					</select>
				</tr></td>
			</table>
		</td>
	{/foreach}
	</tr>
	<tr>
		<td>
			<input type="submit" name="save" value="Save" />
		</td>
	</tr>
	</table>

{/if}

</form>