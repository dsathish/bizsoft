{if $action ne 'print' }
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
	<form class="yform" name="filter-items" method="POST" action="">
		<table class="type-input" width="95%">
			<tr>
				<thead>
				<th style="width:10px">From</th>
				<th style="width:10px"><input type="text" name="from_date" value="{$from_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);"/></th>
				<th style="width:10px">To</th>
				<th style="width:10px"><input type="text" name="to_date" value="{$to_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);"/></th>
				<th><input type="submit" name="go" value="Go"/></th>
				<th style="width:300px">
					<a href="customizations.php?report_id={$report_id}&action=add">Create</a>
					| <a href="customizations.php?report_id={$report_id}&customization_id={$customization_id}&action=copy">Copy</a>
				{if $customization_name ne 'Default'}
					| <a href="customizations.php?report_id={$report_id}&customization_id={$customization_id}&action=edit">Edit</a>
					| <a href="customizations.php?report_id={$report_id}&customization_id={$customization_id}&action=del" onclick="return confirm('Delete ?');" >Delete</a>
				{/if}
					| <a href="filters.php?report_id={$report_id}&customization_id={$customization_id}">Filter</a>
				</th>
					<th style="width:25px">Customized Formats</th>	
					<th style="width:50px">
						<select id="customization_id" name="customization_id" onchange="{literal}if(this.value != 0){this.form.submit();}{/literal}">
							<option value="0">Select</option>
							{foreach from=$customization_array item="customization"}
								{if $customization.customization_id eq $customization_id}
									<option value="{$customization.customization_id}" selected>{$customization.customization_name}</option>
								{else}
									<option value="{$customization.customization_id}">{$customization.customization_name}</option>
								{/if}
							{/foreach}
						</select>
					</th>
					<th style="width:80px">
						<div align="right">
							<a target = "_blank" href="./report.php?report_id={$report_id}&customization_id={$customization_id}&action=print" >
								<img border="0" src="{$offset_path}/lib/img/print.png" title="Printable Format"/>
							</a>
							<a href="javascript:void(0);" onclick="window.open('{$offset_path}/php/pdf.php?report_id={$report_id}&customization_id={$customization_id}','_blank', 'width=500,height=500');" >
								<img border="0" src="{$offset_path}/lib/img/acrobat.gif" title="Pdf Format"/>
							</a>
							<a href="{$offset_path}/php/report.php?report_id={$report_id}&customization_id={$customization_id}&action=excel_report">
								<img border="0" src="{$offset_path}/lib/img/spreadsheet.jpeg" title="Export to Excel"/>
							</a>
						</div>
					</th>
				</thead>
			</tr>
		</table>
	</form>
{/if}
	{if !empty($message)}
		<table align="center" cellpadding="0" cellspacing="0">
			<tr><td class='error'>{$message}</td></tr>
		</table>
	{else}	
		<div class="report_data">
			<table align="center" width="95%">
			<thead><tr>
				{foreach from=$customization_data[0] key="column_name" item="customization"}
					<th>
						{$column_name}
					</th>
				{/foreach}
				</tr>
			</thead>
			<tbody>
			{foreach from=$customization_data item="customization"}
				<tr bgcolor="{cycle values="#ffffff,#F3F5EE"}">
					{foreach from=$customization key="column_name" item="data"}
						<td style = {$columns_style[$column_name]}>
							{$data}
						</td>
					{/foreach}
				</tr>
			{/foreach}
			</tbody>
		</table>
	{/if}
{literal}
<script language="Javascript">
function submitForm(obj)
{
	if(obj.value != '')
	{
		obj.submit();
	}
}
</script>
{/literal}