<?php
/**
 * Initialize and configure all paths
 */
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$root_path = realpath(dirname(__FILE__) . '/');
$include_paths = get_include_path();
set_include_path($include_paths.PATH_SEPARATOR."$root_path/lib");

require('conf/setup.inc.php');

if($_REQUEST['err'])
{
	$smarty->assign("errors",$_REQUEST['err']);
}
$smarty->clear_cache("login.tpl");
$smarty->display("login.tpl");

?>
