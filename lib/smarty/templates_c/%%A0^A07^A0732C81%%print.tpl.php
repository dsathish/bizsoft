<?php /* Smarty version 2.6.20, created on 2009-05-02 22:59:56
         compiled from print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'print.tpl', 9, false),)), $this); ?>
<center><h3><?php echo $this->_tpl_vars['_company_name_']; ?>
</h3></center>
<table width="50%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%"><?php echo $this->_tpl_vars['commercial_details']['relationship_name']; ?>
</td>
		<td align="right">S No. <?php echo $this->_tpl_vars['commercial_details']['sales_ref']; ?>
</td>
	</tr>
	<tr>
		<td width="50%"><?php echo $this->_tpl_vars['contact_details']['0']['address1']; ?>
</td>
		<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['commercial_details']['sales_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
</td>
	</tr>
	<tr>
		<td><?php echo $this->_tpl_vars['contact_details']['0']['address2']; ?>
</td>
	</tr>
	<tr>
		<td>Ph: <?php echo $this->_tpl_vars['contact_details']['0']['phone_no']; ?>
</td>
	</tr>
	<tr>
		<td><?php echo $this->_tpl_vars['contact_details']['0']['email']; ?>
</td>
	</tr>
	<tr>
		<td><?php echo $this->_tpl_vars['contact_details']['0']['city_name']; ?>
 - <?php echo $this->_tpl_vars['contact_details']['0']['pincode']; ?>
</td>
	</tr>
</table>
<br />
<table width="60%" align="center" cellpadding="0" cellspacing="0">
	<caption>Product Details</caption>
	<?php $_from = $this->_tpl_vars['item_details'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
		<th>
			<?php echo $this->_tpl_vars['column_name']; ?>

		</th>
	<?php endforeach; endif; unset($_from); ?>
	<?php $_from = $this->_tpl_vars['item_details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item_array']):
?>
		<tr>
			<?php $_from = $this->_tpl_vars['item_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
				<td style = <?php echo $this->_tpl_vars['item_columns_style'][$this->_tpl_vars['column_name']]; ?>
>
					<?php echo $this->_tpl_vars['data']; ?>

				</td>
			<?php endforeach; endif; unset($_from); ?>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>