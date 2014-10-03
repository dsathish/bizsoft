<script src="{$offset_path}/lib/js/jquery.validate.js"></script>
{literal}
<script type="text/javascript">
$().ready(function() {
	// validate the comment form when it is submitted
//	$("#receipt_form").validate();
	
	// validate signup form on keyup and submit
	$("#contacts_form").validate({
		rules: {
			relationship_name: {
				required: true
			},
			phone_no: {
				digits: true
			},
			email: {
				email: true
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
		<tr><td class='error'>{$message|replace:"relation":$relation_label}</td></tr>
	</table>
{/if}
<form id="contacts_form" name="contacts_form" method="POST" action="contacts.php?activity={$activity}" class="yform">
	<fieldset>
	<legend>Commercial Details<legend>
	<table class="type-input" width="100%" align="center" >
		<tr>
			<td>
				{$relation_label}
			</td>
			<td>
				<input type="text" id="relationship_name" name="relationship_name" value = "{$relationship_name}" {$readonly_action}/>
			</td>	
			<td>
				TIN No.
			</td>
			<td>
				<input type="text" id="tax_detail" name="tax_detail" value="{$tax_detail}" />
			</td>
		</tr>
		<tr>
			<td>
				Payment Term
			</td>
			<td>
				<input type="text" id="payment_term" name="payment_term" value="{$payment_term}"/>
			</td>
			<td>
				Credit Days
			</td>
			<td>
				<input type="text" id="credit_days" name="credit_days" size="8" value="{$credit_days}"/>
			</td>
		</tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Contact Details<legend>
	<table class="type-input" width="100%" align="center" >
		<tr>
			<td>
				Regd. Address
			</td>
			<td>
				<textarea style="overflow:hidden;" name="address1" rows="3" cols="25">
	   		    	{$address1}
				</textarea>
			</td>
			<td>
				Del. Add.
			</td>
			<td>
				<textarea style="overflow:hidden;" name="address2" rows="3" cols="25">
				{$address2}
				</textarea>	
			</td>
		</tr>
		<tr>
			<td>
				Phone No
			</td>
			<td>
				<input type="text" id="phone_no" name="phone_no" value="{$phone_no}"/>
			</td>
			<td>
				E Mail
			</td>
			<td>
				<input type="text" id="email" name="email" value="{$email}"/>
			</td>
		</tr>
	</table>
	</fieldset>
	<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
	</div>



	<input type="hidden" id="action" name="action" value = "{$action}" />
	<input type="hidden" id="activity" name="activity" value = "{$activity}" />
	<input type="hidden" id="relationship_id" name="relationship_id" value = "{$relationship_id}" />

</form>

{if $contact_details}
<br/>
	<table id="report_data" align="center" width="80%">
		<caption>Contact Details</caption>
		{foreach from=$contact_details[0] key="column_name" item="data"}
			<th>
				{$column_name}
			</th>
		{/foreach}
		{foreach from=$contact_details item="contact_array"}
			<tr bgcolor="{cycle values="#ffffff,#F3F5EE"}">
				{foreach from=$contact_array key="column_name" item="data"}
					<td>
						{$data}
					</td>
				{/foreach}
			</tr>
		{/foreach}
	</table>
{/if}
