{php}
require_once(_xajax_reg_path_.'xajax_register.php');
{/php}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr"> 
<title>{$_product_name_}</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<script language="javascript">
	var offset_path = '{$offset_path}';
</script>
{php}
	$xajax->printJavascript('xajax/');
{/php}
{if $action ne 'print'}
	<link rel="stylesheet" type="text/css" href="{$offset_path}/lib/css/jquerycssmenu.css" />
	<link rel="stylesheet" type="text/css" href="{$offset_path}/lib/css/main.css" />
	<script type="text/javascript" src="{$offset_path}/lib/js/jquery-latest.js"></script>
	<script type="text/javascript" src="{$offset_path}/lib/js/jquerycssmenu.js"></script>
	<script type="text/javascript" src="{$offset_path}/lib/js/script.js" ></script>
{elseif $action eq 'print'}
	<link rel="stylesheet" type="text/css" href="{$offset_path}/lib/css/print.css" />
{/if}

</head>
<body>
	{if $action ne 'print'}
		{include file="header.tpl"}
	{/if}
	<div id="main" align="center">
	<div align="center"><h4>{$_page_heading_}</h4></div>
	{$content}
	</div>
	{if $action ne 'print'}
		{include file="footer.tpl"}
	{/if}
</body>
</html>
