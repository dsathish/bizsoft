<?php
require('conf/setup.inc.php');

if($_REQUEST['err'])
{
	$smarty->assign("errors",$_REQUEST['err']);
}
$smarty->clear_cache("login.tpl");
$smarty->display("login.tpl");

?>
