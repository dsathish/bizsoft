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
	$("#payment_form").validate({
		rules: {
			payment_ref: {
				required: true
			},
			relationship:"required",
			payment_mode_id:"required",
			payment_date: {
				required: true,
				dateIN: true
			}
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
<form id="payment_form" name="payment_form" method="POST" action="payment.php" class="yform">
	<fieldset>
	<legend>Commercial Details</legend>
		<table class="type-input" width="100%" align="center">
		<tr>
			<td class="tHead">
				{if $action eq 'sel'}
					Supplier
				{elseif $action eq 'buy'}
					Buyer
				{/if}
			</td>
			<td>
				<select name="relationship_id" style="width:200px;" onchange="{literal}if(this.value>0){this.form.submit();}{/literal}">
					<option value="">Select</option>
					{foreach from=$relationship_array item="relationship"}
						{if $_post.relationship_id eq $relationship.relationship_id}			
							<option value="{$relationship.relationship_id}" selected>{$relationship.relationship_name}</option>
						{else}
							<option value="{$relationship.relationship_id}">{$relationship.relationship_name}</option>
						{/if}
					{/foreach}
				</select>
			</td>
			<td class="tHead">
				Payment Mode
			</td>
			<td>
				<select name="payment_mode_id">
					<option value="">Select</option>
					{foreach from=$payment_mode_array item="payment_mode"}
						<option value="{$payment_mode.payment_mode_id}" >{$payment_mode.payment_mode_name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="tHead">
				Payment Reference
			</td>
			<td>
				<input type="text" id="payment_ref" name="payment_ref" value="" />
			</td>
			<td class="tHead">
				Payment Date
			</td>
			<td>
				<input type="text" id="payment_date" name="payment_date" value="{$payment_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br />
	{if $payment_reference_array[0].refer_id}
		<fieldset>

		{if $action eq 'sel'}
			<legend>Supplier Item Details</legend>
		{elseif $action eq 'buy'}
			<legend>Buyer Item Details</legend>
		{/if}
		
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<table id="payment_items" width="80%" align="center" cellpadding="0" cellspacing="0">
					{if $action eq 'sel'}
						<tr>	
							<th width="30%">Receipt Reference</th>
							<th width="20%">Amount</th>
							<th width="20%">Deductions</th>
							<th>Action</th>
						</tr>
					{elseif $action eq 'buy'}
						<tr>	
							<th width="30%">Sales Reference</th>
							<th width="10%">Amount</th>
							<th width="10%">Deductions</th>
							<th width="10%">Action</th>
						</tr>
					{/if}
					<tbody></tbody>
					<tr>
					<td colspan="4">
						<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('payment_items','payment');document.getElementById('payment_items['+(row_id - 1)+']').focus();" />
					</td></tr>
				</table>
			</tr>
		</table>
		</fieldset>
		<div class="type-button" align="center">
		<input type="submit" value="Save" name="save" />
		</div>

	{elseif $_post}
		<blink class="error" text-align="center">No Pending Payments!!!</blink>
	{/if}
		
	<input type="hidden" name="action" value="{$action}"/>
</form>

{literal}
<script type="text/javascript">
	var refer_arr = new Array();
	refer_arr = {/literal}{$js_refer_array}{literal};
{/literal}
	{if  $payment_reference_array[0].refer_id}
		{literal} addRow('payment_items','payment'); {/literal}
	{/if}
{literal}
</script>
{/literal}