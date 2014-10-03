<?php /* Smarty version 2.6.20, created on 2009-05-07 12:30:47
         compiled from filters.tpl */ ?>
<form id="customization_form" name="customization_form" method="POST" action="filters.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
">
<?php if ($this->_tpl_vars['filters_array']): ?>
	<table align="center" cellpadding="0" cellspacing="0">
	<tr>
	<?php $_from = $this->_tpl_vars['filters_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['values']):
?>
		<td align="center" cellpadding="0" cellspacing="0">
			<table>
				<tr><th><?php echo $this->_tpl_vars['values']['display_name']; ?>
</th></tr>
				<tr><td>
					<select name="column_id[<?php echo $this->_tpl_vars['values']['column_id']; ?>
][]" style="width:150px;" size="4" multiple>
						<option value="" <?php echo $this->_tpl_vars['values']['all']; ?>
>All</option>
						<?php $_from = $this->_tpl_vars['values']['filter_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['options']):
?>
							<option <?php echo $this->_tpl_vars['options']['selected']; ?>
><?php echo $this->_tpl_vars['options']['value']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</tr></td>
			</table>
		</td>
	<?php endforeach; endif; unset($_from); ?>
	</tr>
	<tr>
		<td>
			<input type="submit" name="save" value="Save" />
		</td>
	</tr>
	</table>

<?php endif; ?>

</form>