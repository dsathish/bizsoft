<?php /* Smarty version 2.6.20, created on 2009-05-28 16:49:35
         compiled from report.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'report.tpl', 80, false),)), $this); ?>
<?php if ($this->_tpl_vars['action'] != 'print'): ?>
	<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/epochprime_classes.js"></script>
	<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquery.validate.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/css/epochprime_styles.css" />
	<?php echo '
	<script type="text/javascript">
	window.onload = function()
	{
		var xml = \'<configs><initcfg><mode>popup</mode></initcfg></configs>\';
		popup = new EpochPrime(\'\',xml);
	};
	</script>
	'; ?>

	<form class="yform" name="filter-items" method="POST" action="">
		<table class="type-input" width="95%">
			<tr>
				<thead>
				<th style="width:10px">From</th>
				<th style="width:10px"><input type="text" name="from_date" value="<?php echo $this->_tpl_vars['from_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);"/></th>
				<th style="width:10px">To</th>
				<th style="width:10px"><input type="text" name="to_date" value="<?php echo $this->_tpl_vars['to_date']; ?>
" size="12" maxlength="10" onfocus="popup.setTarget(this);"/></th>
				<th><input type="submit" name="go" value="Go"/></th>
				<th style="width:300px">
					<a href="customizations.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&action=add">Create</a>
					| <a href="customizations.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
&action=copy">Copy</a>
				<?php if ($this->_tpl_vars['customization_name'] != 'Default'): ?>
					| <a href="customizations.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
&action=edit">Edit</a>
					| <a href="customizations.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
&action=del" onclick="return confirm('Delete ?');" >Delete</a>
				<?php endif; ?>
					| <a href="filters.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
">Filter</a>
				</th>
					<th style="width:25px">Customized Formats</th>	
					<th style="width:50px">
						<select id="customization_id" name="customization_id" onchange="<?php echo 'if(this.value != 0){this.form.submit();}'; ?>
">
							<option value="0">Select</option>
							<?php $_from = $this->_tpl_vars['customization_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['customization']):
?>
								<?php if ($this->_tpl_vars['customization']['customization_id'] == $this->_tpl_vars['customization_id']): ?>
									<option value="<?php echo $this->_tpl_vars['customization']['customization_id']; ?>
" selected><?php echo $this->_tpl_vars['customization']['customization_name']; ?>
</option>
								<?php else: ?>
									<option value="<?php echo $this->_tpl_vars['customization']['customization_id']; ?>
"><?php echo $this->_tpl_vars['customization']['customization_name']; ?>
</option>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						</select>
					</th>
					<th style="width:80px">
						<div align="right">
							<a target = "_blank" href="./report.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
&action=print" >
								<img border="0" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/img/print.png" title="Printable Format"/>
							</a>
							<a href="javascript:void(0);" onclick="window.open('<?php echo $this->_tpl_vars['offset_path']; ?>
/php/pdf.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
','_blank', 'width=500,height=500');" >
								<img border="0" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/img/acrobat.gif" title="Pdf Format"/>
							</a>
							<a href="<?php echo $this->_tpl_vars['offset_path']; ?>
/php/report.php?report_id=<?php echo $this->_tpl_vars['report_id']; ?>
&customization_id=<?php echo $this->_tpl_vars['customization_id']; ?>
&action=excel_report">
								<img border="0" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/img/spreadsheet.jpeg" title="Export to Excel"/>
							</a>
						</div>
					</th>
				</thead>
			</tr>
		</table>
	</form>
<?php endif; ?>
	<?php if (! empty ( $this->_tpl_vars['message'] )): ?>
		<table align="center" cellpadding="0" cellspacing="0">
			<tr><td class='error'><?php echo $this->_tpl_vars['message']; ?>
</td></tr>
		</table>
	<?php else: ?>	
		<div class="report_data">
			<table align="center" width="95%">
			<thead><tr>
				<?php $_from = $this->_tpl_vars['customization_data'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['customization']):
?>
					<th>
						<?php echo $this->_tpl_vars['column_name']; ?>

					</th>
				<?php endforeach; endif; unset($_from); ?>
				</tr>
			</thead>
			<tbody>
			<?php $_from = $this->_tpl_vars['customization_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['customization']):
?>
				<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#ffffff,#F3F5EE"), $this);?>
">
					<?php $_from = $this->_tpl_vars['customization']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
						<td style = <?php echo $this->_tpl_vars['columns_style'][$this->_tpl_vars['column_name']]; ?>
>
							<?php echo $this->_tpl_vars['data']; ?>

						</td>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php echo '
<script language="Javascript">
function submitForm(obj)
{
	if(obj.value != \'\')
	{
		obj.submit();
	}
}
</script>
'; ?>