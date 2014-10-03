{if !empty($message)}
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'>{$message}</td></tr>
	</table>
{else}
	<table align="right" cellpadding="0" cellspacing="0">
	<tr>
		{foreach from=$query_string name=querystr item="link"}
			<td>
				<a href="{$link.href}" onclick="{$link.onclick}" title="{$link.title}">{$link.title}</a>
				{if !$smarty.foreach.querystr.last} | {/if}
			</td>
		{/foreach}
	</tr>
	</table>
	<br /><br />
	{if $commercial_details}
		<table class="details_data" cellspacing="0" cellpadding="0" border="0">
			<thead><tr><th colspan="2" >{$module|capitalize} Details</th></tr></thead>
			<tbody>				
			{foreach from=$commercial_details key="column_name" item="data"}
				<tr>
					<th class="sub">	
						{$column_name} 
					</th>
					<td>	
						{$data} 
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
		<br />
		<table id="report_data" width="75%" align="center" >
			<caption>Item Details</caption>
			{foreach from=$item_details[0] key="column_name" item="data"}
				<th>
					{$column_name}
				</th>
			{/foreach}
			{foreach from=$item_details item="item_array"}
				<tr bgcolor="{cycle values="#ffffff,#F3F5EE"}">
					{foreach from=$item_array key="column_name" item="data"}
						<td style = {$item_columns_style[$column_name]}>
							{$data}
						</td>
					{/foreach}
				</tr>
			{/foreach}
		</table>
	{/if}
	{if $tax_details}
		<br />
		<table id="report_data" width="50%" cellpadding="0.3em" cellspacing="0.3em">
			<caption>Tax Details</caption>
			{foreach from=$tax_details[0] key="column_name" item="data"}
				<th>
					{$column_name}
				</th>
			{/foreach}
			{foreach from=$tax_details item="tax_array"}
				<tr bgcolor="{cycle values="#ffffff,#F3F5EE"}">
					{foreach from=$tax_array key="column_name" item="data"}
						<td style = {$tax_columns_style[$column_name]}>
							{$data}
						</td>
					{/foreach}
				</tr>
			{/foreach}
		</table>
	{/if}
{/if}