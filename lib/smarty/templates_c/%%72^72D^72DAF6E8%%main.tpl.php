<?php /* Smarty version 2.6.20, created on 2009-05-19 06:00:54
         compiled from main.tpl */ ?>
<?php 
require_once(_xajax_reg_path_.'xajax_register.php');
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr"> 
<title><?php echo $this->_tpl_vars['_product_name_']; ?>
</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<script language="javascript">
	var offset_path = '<?php echo $this->_tpl_vars['offset_path']; ?>
';
</script>
<?php 
	$xajax->printJavascript('xajax/');
 ?>
<?php if ($this->_tpl_vars['action'] != 'print'): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/css/jquerycssmenu.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/css/main.css" />
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquery-latest.js"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/jquerycssmenu.js"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/script.js" ></script>
<?php elseif ($this->_tpl_vars['action'] == 'print'): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/css/print.css" />
<?php endif; ?>

</head>
<body>
	<?php if ($this->_tpl_vars['action'] != 'print'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	<div id="main" align="center">
	<div align="center"><h4><?php echo $this->_tpl_vars['_page_heading_']; ?>
</h4></div>
	<?php echo $this->_tpl_vars['content']; ?>

	</div>
	<?php if ($this->_tpl_vars['action'] != 'print'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
</body>
</html>