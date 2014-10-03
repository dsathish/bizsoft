<?php /* Smarty version 2.6.20, created on 2009-05-03 18:22:22
         compiled from login.tpl */ ?>
<html>
<head>
    <title>Bizsoft</title>
	<script language="javascript" src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/js/script.js"></script>
	<?php echo '
	<style>
		BODY {font-family: arial; font-size:12px; }
		.OrangeButton { margin:0; border:green 1px solid; background-color:#EACD91; cursor:pointer; padding-left:3px; padding-right:3px; }
		.LoginBox { border:#ff9933 1px solid; padding:15px; width:340px; }
		.OrangeTextBox { margin:0; border:#ff9933 1px solid; height:20px; vertical-align:middle; color:#000; }
		.ErrorMessage {font-size:11px; color:#ff3300; padding:3px 10px;}

	</style>

	<script language = "javascript">
	function isChangeformValid() 
	{
		if(nullCheck("username","Enter the Username") && nullCheck("password","Enter the password") && checkLen(4,"username","Username Should contain minimum 4 characters"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	</script>
	'; ?>

</head>

<body>
    <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" valign="middle">
                				<form name="form1" method="POST" action="<?php echo $this->_tpl_vars['offset_path']; ?>
/php/login.php" onsubmit = "return isChangeformValid();">
				<div class="LoginBox">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
						<?php if ($this->_tpl_vars['errors']): ?>
						<tr>
							<td colspan="3" align="center" class="ErrorMessage"><?php echo $this->_tpl_vars['errors']; ?>
</td>
						</tr>
						<?php endif; ?>
						<tr>
							<td rowspan=5><img src="<?php echo $this->_tpl_vars['offset_path']; ?>
/lib/img/login_key.jpg"></td>
							<td width="80">Username:</td><td style="padding:5px 5px 5px 0px"><input type="text" name="username"  id="username" value="<?php echo $this->_tpl_vars['username']; ?>
" maxlength="50" size="20" class="OrangeTextBox"></td>
						</tr>
						<tr>
							<td>Password:</td><td style="padding:5px 5px 5px 0px"><input type="password" name="password" id="password" value="<?php echo $this->_tpl_vars['password']; ?>
" maxlength="50" size="20"  class="OrangeTextBox"></td>
						</tr>
						<tr>
							<td>&nbsp;</td><td style="padding:5px 5px 5px 0px"><input class="OrangeButton" type="submit" value="   Login   "><input type="hidden" name="login" value="login"></td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
					</table>
				</div>
				</form>
            </td>
        </tr>
    </table>
</body>
</html>
