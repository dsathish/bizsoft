{$supplier_js_array}
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
	$("#handln_form").validate({
		rules: {
			handln_ref: {
				required: true
				},
			supplier:"required",
			handln_date: {
				required: true,
				dateIN: true
				}
			},
		messages:{
			handln_ref: {
				required: "Please enter handloan ref"
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
<div id="results"></div>
<form id="handln_form" name="handln_form" method="POST" action="handloan.php" class="yform">
	<fieldset>
		<legend>
			Handloan Details
		</legend>
		<table class = "type-input" width="100%" align="center">
		<tr>
			<td>
				Take From/Given To
			</td>
			<td>
				<input type="text" id="supplier" name="supplier" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_supplier.show_options(event);" onblur="javascript:eventobj_supplier.hide_options();" />
			</td>
		</tr>
		<tr>
			<td>
				 Reference
			</td>
			<td>
				<input type="text" id="handln_ref" name="handln_ref" value="" />
			</td>
			
		</tr>
		<tr>
			<td>
				Date
			</td>
			<td>
				<input type="text" id="handln_date" name="handln_date" value="{$handln_date}" size="12" maxlength="10" onfocus="popup.setTarget(this);" />
			</td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset >
		<legend>Item Details<legend>
		<table id="handln_items" width="75%" align="center" cellpadding="0" cellspacing="0">
			<tr>{if $refer_array}<th width="15%">Reference (if any)</th>{/if}<th width="15%">Product</th><th width="10%">Quantity</th><th width="10%">UOM</th><th width="10%">Price(Rs)</th><th width="10%">Action</th></tr>
			<tbody></tbody>
			<tr><td colspan="4">
				<input type="button" name="add_item" id="add_item" value="Add" onclick="addRow('handln_items');document.getElementById('product_desc['+(row_id - 1)+']').focus();" />
			</td></tr>
		</table>
	</fieldset>		
	<div class="type-button" align="center">
		<input type="submit" value="Save" name="save" />
	</div>
	<input type="hidden" name="handln_type" value="{$handln_type}"/>

</form>

{literal}
<script type="text/javascript">
	var uom_arr = new Array();
	uom_arr = {/literal}{$uom_array}{literal};

	{/literal}
	{if $refer_array}
		{literal}
		var refer_arr = new Array();
		refer_arr = {/literal}{$refer_array};
	{/if}
	{literal}	
	
	addRow('handln_items');

	eventobj_supplier = new AutoComplete(document.getElementById('supplier'), '1', buy_array, 'eventobj_supplier');
</script>
{/literal}