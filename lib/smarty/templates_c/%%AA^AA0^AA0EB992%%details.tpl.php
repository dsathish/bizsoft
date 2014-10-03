<?php /* Smarty version 2.6.20, created on 2009-05-19 06:07:47
         compiled from details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'capitalize', 'details.tpl', 19, false),array('function', 'cycle', 'details.tpl', 42, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['message'] )): ?>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'><?php echo $this->_tpl_vars['message']; ?>
</td></tr>
	</table>
<?php else: ?>
	<table align="right" cellpadding="0" cellspacing="0">
	<tr>
		<?php $_from = $this->_tpl_vars['query_string']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['querystr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['querystr']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['link']):
        $this->_foreach['querystr']['iteration']++;
?>
			<td>
				<a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" onclick="<?php echo $this->_tpl_vars['link']['onclick']; ?>
" title="<?php echo $this->_tpl_vars['link']['title']; ?>
"><?php echo $this->_tpl_vars['link']['title']; ?>
</a>
				<?php if (! ($this->_foreach['querystr']['iteration'] == $this->_foreach['querystr']['total'])): ?> | <?php endif; ?>
			</td>
		<?php endforeach; endif; unset($_from); ?>
	</tr>
	</table>
	<br /><br />
	<?php if ($this->_tpl_vars['commercial_details']): ?>
		<table class="details_data" cellspacing="0" cellpadding="0" border="0">
			<thead><tr><th colspan="2" ><?php echo ((is_array($_tmp=$this->_tpl_vars['module'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
 Details</th></tr></thead>
			<tbody>				
			<?php $_from = $this->_tpl_vars['commercial_details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
				<tr>
					<th class="sub">	
						<?php echo $this->_tpl_vars['column_name']; ?>
 
					</th>
					<td>	
						<?php echo $this->_tpl_vars['data']; ?>
 
					</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
		<br />
		<table id="report_data" width="75%" align="center" >
			<caption>Item Details</caption>
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
				<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#ffffff,#F3F5EE"), $this);?>
">
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
	<?php endif; ?>
	<?php if ($this->_tpl_vars['tax_details']): ?>
		<br />
		<table id="report_data" width="50%" cellpadding="0.3em" cellspacing="0.3em">
			<caption>Tax Details</caption>
			<?php $_from = $this->_tpl_vars['tax_details'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
				<th>
					<?php echo $this->_tpl_vars['column_name']; ?>

				</th>
			<?php endforeach; endif; unset($_from); ?>
			<?php $_from = $this->_tpl_vars['tax_details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_array']):
?>
				<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#ffffff,#F3F5EE"), $this);?>
">
					<?php $_from = $this->_tpl_vars['tax_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
						<td style = <?php echo $this->_tpl_vars['tax_columns_style'][$this->_tpl_vars['column_name']]; ?>
>
							<?php echo $this->_tpl_vars['data']; ?>

						</td>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		</table>
	<?php endif; ?>
<?php endif; ?>