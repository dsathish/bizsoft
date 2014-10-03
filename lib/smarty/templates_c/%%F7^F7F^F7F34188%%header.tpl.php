<?php /* Smarty version 2.6.20, created on 2009-05-19 06:00:54
         compiled from header.tpl */ ?>
<div id="header">
	<span class="navwel">
		Welcome <?php echo $this->_tpl_vars['_user_name_']; ?>

	</span>
	<span class="navlinks">
		<a href="<?php echo $this->_tpl_vars['offset_path']; ?>
/php/login.php?logout=1">Log Out</a>
	</span>
	<div class="page">
			<h1><?php echo $this->_tpl_vars['_company_name_']; ?>
</h1>
	</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>