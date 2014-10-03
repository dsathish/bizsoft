<?php
/**
 * Initializes all objects and includes all required common files.
 * @todo Notes, If any
*/
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();

require('conf/const.inc.php');
require('smarty/Smarty.class.php');

$smarty = new smarty();
$smarty->template_dir = _tpl_path_;
$smarty->compile_dir = _smarty_path_."templates_c";
$smarty->config_dir = _smarty_path_."configs";
$smarty->cache_dir = _smarty_path_."cache";
$smarty->security = false;
$smarty->php_handling = "SMARTY_PHP_QUOTE";

$smarty->assign('offset_path',_offset_path_);

$smarty->assign('_product_name_',_product_name_);
$smarty->assign('_company_name_',_company_name_);
$smarty->assign('_user_name_',$_SESSION['user_name']);

try
{
	$db = new PDO(DB_ENGINE.':host=localhost;dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

	$db->query('SET SEARCH_PATH TO '.DB_SCHEMA);
	//print_r($db->errorInfo());
}
catch (PDOException $e)
{
	echo 'Connection failed: ' . $e->getMessage();
}

require('cls/class.common.php');
$common_obj = new common();

// for indian style money format
setlocale(LC_MONETARY, 'en_IN');

?>