<center><h3>{$_company_name_}</h3></center>
<table width="50%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%">{$commercial_details.relationship_name}</td>
		<td align="right">S No. {$commercial_details.sales_ref}</td>
	</tr>
	<tr>
		<td width="50%">{$contact_details.0.address1}</td>
		<td align="right">{$commercial_details.sales_date|date_format:"%d/%m/%Y"}</td>
	</tr>
	<tr>
		<td>{$contact_details.0.address2}</td>
	</tr>
	<tr>
		<td>Ph: {$contact_details.0.phone_no}</td>
	</tr>
	<tr>
		<td>{$contact_details.0.email}</td>
	</tr>
	<tr>
		<td>{$contact_details.0.city_name} - {$contact_details.0.pincode}</td>
	</tr>
</table>
<br />
<table width="60%" align="center" cellpadding="0" cellspacing="0">
	<caption>Product Details</caption>
	{foreach from=$item_details[0] key="column_name" item="data"}
		<th>
			{$column_name}
		</th>
	{/foreach}
	{foreach from=$item_details item="item_array"}
		<tr>
			{foreach from=$item_array key="column_name" item="data"}
				<td style = {$item_columns_style[$column_name]}>
					{$data}
				</td>
			{/foreach}
		</tr>
	{/foreach}
</table>