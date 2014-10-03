<html>
<head>
    <title>Bizsoft</title>
	<script language="javascript" src="{$offset_path}/lib/js/script.js"></script>
	{literal}
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
	{/literal}
</head>

<body>
    <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" valign="middle">
                {*<img src="{$offset_path}/images/logo.jpg">*}
				<form name="form1" method="POST" action="{$offset_path}/php/login.php" onsubmit = "return isChangeformValid();">
				<div class="LoginBox">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
						{if $errors}
						<tr>
							<td colspan="3" align="center" class="ErrorMessage">{$errors}</td>
						</tr>
						{/if}
						<tr>
							<td rowspan=5><img src="{$offset_path}/lib/img/login_key.jpg"></td>
							<td width="80">Username:</td><td style="padding:5px 5px 5px 0px"><input type="text" name="username"  id="username" value="{$username}" maxlength="50" size="20" class="OrangeTextBox"></td>
						</tr>
						<tr>
							<td>Password:</td><td style="padding:5px 5px 5px 0px"><input type="password" name="password" id="password" value="{$password}" maxlength="50" size="20"  class="OrangeTextBox"></td>
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

