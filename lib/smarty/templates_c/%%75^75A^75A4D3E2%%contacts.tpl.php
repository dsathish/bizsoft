<?php /* Smarty version 2.6.20, created on 2009-05-28 10:22:27
         compiled from contacts.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'contacts.tpl', 30, false),array('function', 'cycle', 'contacts.tpl', 125, false),)), $this); ?>
<script src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquery.validate.js"></script>
<?php echo '
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
'; ?>

<?php if (! empty ( $this->_tpl_vars['message'] )): ?>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr><td class='error'><?php echo ((is_array($_tmp=$this->_tpl_vars['message'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'relation', $this->_tpl_vars['relation_label']) : smarty_modifier_replace($_tmp, 'relation', $this->_tpl_vars['relation_label'])); ?>
</td></tr>
	</table>
<?php endif; ?>
<form id="contacts_form" name="contacts_form" method="POST" action="contacts.php?activity=<?php echo $this->_tpl_vars['activity']; ?>
" class="yform">
	<fieldset>
	<legend>Commercial Details<legend>
	<table class="type-input" width="100%" align="center" >
		<tr>
			<td>
				<?php echo $this->_tpl_vars['relation_label']; ?>

			</td>
			<td>
				<input type="text" id="relationship_name" name="relationship_name" value = "<?php echo $this->_tpl_vars['relationship_name']; ?>
" <?php echo $this->_tpl_vars['readonly_action']; ?>
/>
			</td>	
			<td>
				TIN No.
			</td>
			<td>
				<input type="text" id="tax_detail" name="tax_detail" value="<?php echo $this->_tpl_vars['tax_detail']; ?>
" />
			</td>
		</tr>
		<tr>
			<td>
				Payment Term
			</td>
			<td>
				<input type="text" id="payment_term" name="payment_term" value="<?php echo $this->_tpl_vars['payment_term']; ?>
"/>
			</td>
			<td>
				Credit Days
			</td>
			<td>
				<input type="text" id="credit_days" name="credit_days" size="8" value="<?php echo $this->_tpl_vars['credit_days']; ?>
"/>
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
	   		    	<?php echo $this->_tpl_vars['address1']; ?>

				</textarea>
			</td>
			<td>
				Del. Add.
			</td>
			<td>
				<textarea style="overflow:hidden;" name="address2" rows="3" cols="25">
				<?php echo $this->_tpl_vars['address2']; ?>

				</textarea>	
			</td>
		</tr>
		<tr>
			<td>
				Phone No
			</td>
			<td>
				<input type="text" id="phone_no" name="phone_no" value="<?php echo $this->_tpl_vars['phone_no']; ?>
"/>
			</td>
			<td>
				E Mail
			</td>
			<td>
				<input type="text" id="email" name="email" value="<?php echo $this->_tpl_vars['email']; ?>
"/>
			</td>
		</tr>
	</table>
	</fieldset>
	<div class="type-button" align="center">
	<input type="submit" value="Save" name="save" />
	</div>



	<input type="hidden" id="action" name="action" value = "<?php echo $this->_tpl_vars['action']; ?>
" />
	<input type="hidden" id="relationship_id" name="relationship_id" value = "<?php echo $this->_tpl_vars['relationship_id']; ?>
" />

</form>

<?php if ($this->_tpl_vars['contact_details']): ?>
<br/>
	<table id="report_data" align="center" width="80%">
		<caption>Contact Details</caption>
		<?php $_from = $this->_tpl_vars['contact_details'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
			<th>
				<?php echo $this->_tpl_vars['column_name']; ?>

			</th>
		<?php endforeach; endif; unset($_from); ?>
		<?php $_from = $this->_tpl_vars['contact_details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['contact_array']):
?>
			<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#ffffff,#F3F5EE"), $this);?>
">
				<?php $_from = $this->_tpl_vars['contact_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column_name'] => $this->_tpl_vars['data']):
?>
					<td>
						<?php echo $this->_tpl_vars['data']; ?>

					</td>
				<?php endforeach; endif; unset($_from); ?>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
<?php endif; ?>