<?php /* Smarty version 2.6.20, created on 2009-05-28 17:04:24
         compiled from customizations.tpl */ ?>
<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquery.validate.js"></script>
<?php echo '
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
'; ?>


<form id="customization_form" name="customization_form" method="POST" action="customizations.php">
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>Customization Name</td>
		<td>
			<?php if ($this->_tpl_vars['action'] == 'edit'): ?>
				<input type="text" id="customization_name" name="customization_name" value="<?php echo $this->_tpl_vars['customization_array']['customization_name']; ?>
" size="30" />
				<input type="hidden" name="customization_id" value="<?php echo $this->_tpl_vars['customization_array']['customization_id']; ?>
" size="5" />
			<?php else: ?>
				<input type="text" id="customization_name" name="customization_name" size="30" />
			<?php endif; ?>
			<input type="hidden" name="report_id" value="<?php echo $this->_tpl_vars['customization_array']['report_id']; ?>
" size="5" />
			<input type="hidden" name="date_column" value="<?php echo $this->_tpl_vars['customization_array']['date_column']; ?>
" size="5" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<?php if ($this->_tpl_vars['action'] == 'edit' || $this->_tpl_vars['action'] == 'copy'): ?>
				Default : <input type="checkbox" name="is_default" value="1" <?php if ($this->_tpl_vars['customization_array']['is_default'] == 1): ?>checked<?php endif; ?> />&nbsp;&nbsp;
				Sub Total : <input type="checkbox" name="sub_total" value="1" <?php if ($this->_tpl_vars['customization_array']['sub_total']): ?>checked<?php endif; ?> />&nbsp;&nbsp;
				Grand Total : <input type="checkbox" name="grand_total" value="1" <?php if ($this->_tpl_vars['customization_array']['grand_total']): ?>checked<?php endif; ?> />&nbsp;&nbsp;
			<?php else: ?>
				Default : <input type="checkbox" name="is_default" value="1" />&nbsp;&nbsp;
				Sub Total : <input type="checkbox" name="sub_total" value="1" />&nbsp;&nbsp;
				Grand Total : <input type="checkbox" name="grand_total" value="1" />&nbsp;&nbsp;
			<?php endif; ?>
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
	<?php $_from = $this->_tpl_vars['columns_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['columns']):
?>
	<tr>
		<td>
			<?php echo $this->_tpl_vars['columns']['column_name']; ?>

		</td>
		<td>
			<?php echo $this->_tpl_vars['columns']['default_display_name']; ?>

			<input type="hidden" name="column_id[]" value="<?php echo $this->_tpl_vars['columns']['column_id']; ?>
" size="5" />
		</td>
		<td>
			<input type="text" id="display_name[<?php echo $this->_tpl_vars['key']; ?>
]" name="display_name[<?php echo $this->_tpl_vars['key']; ?>
]" value="<?php echo $this->_tpl_vars['columns']['display_name']; ?>
" class="required" />
		</td>
		<td>
			<?php if ($this->_tpl_vars['columns']['default_is_group'] != ''): ?>
				<input type="checkbox" name="is_group[<?php echo $this->_tpl_vars['columns']['column_id']; ?>
]" value="1" <?php if ($this->_tpl_vars['columns']['is_group']): ?>checked<?php endif; ?> />
			<?php endif; ?>
		</td>
		<td>
			<?php if ($this->_tpl_vars['columns']['default_display_total'] != ''): ?>
				<input type="checkbox" name="display_total[<?php echo $this->_tpl_vars['columns']['column_id']; ?>
]" value="1" <?php if ($this->_tpl_vars['columns']['display_total']): ?>checked<?php endif; ?> />
			<?php endif; ?>
		</td>
		<td>
			<?php if ($this->_tpl_vars['columns']['default_is_filter'] != ''): ?>
				<input type="checkbox" name="is_filter[<?php echo $this->_tpl_vars['columns']['column_id']; ?>
]" value="1" <?php if ($this->_tpl_vars['columns']['is_filter']): ?>checked<?php endif; ?> />
			<?php endif; ?>
		</td>
		<td>
			<input type="text" id="display_order[<?php echo $this->_tpl_vars['key']; ?>
]" name="display_order[<?php echo $this->_tpl_vars['key']; ?>
]" value="<?php if ($this->_tpl_vars['columns']['display_order']): ?><?php echo $this->_tpl_vars['columns']['display_order']; ?>
<?php endif; ?>" size="5" maxlength="4" class="number" />

			<input type="hidden" name="sort_order[]" value="<?php echo $this->_tpl_vars['columns']['sort_order']; ?>
" />
			<input type="hidden" name="decimal_places[]" value="<?php echo $this->_tpl_vars['columns']['decimal_places']; ?>
" />
			<input type="hidden" name="date_format[]" value="<?php echo $this->_tpl_vars['columns']['date_format']; ?>
" />
			<input type="hidden" name="style[]" value="<?php echo $this->_tpl_vars['columns']['style']; ?>
" />
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<td colspan="6" align="center">
			<input type="submit" name="save" value="Save" />
		</td>
	</tr>
</table>
</form>