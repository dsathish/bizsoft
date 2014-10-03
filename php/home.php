<?php
 require('setup.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $smarty;
	require('cls/class.stock.php');
	$stock_obj = new stock();
	require('cls/class.sales.php');
	$sales_obj = new sales();
	require('cls/class.payment.php');
	$payment_obj = new payment();

	$smarty->assign("title","Demo");

	$sales_array = $sales_obj->getSalesDetails();

	$reorder_array = $stock_obj->getReorderDetails();
	$smarty->assign("reorder_array",$reorder_array);
	
	$ageing_array = $stock_obj->getAgeingDetails();
	$smarty->assign("ageing_array",$ageing_array);

	$smarty->clear_cache('home.tpl');
	return $smarty->fetch('home.tpl');
}

?>
