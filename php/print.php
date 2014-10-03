<?php
 require('setup.php');

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $db,$smarty,$common_obj;

	require('cls/class.report.php');
	$report_obj = new report();
	$module = $_GET['module'];
	$action = $_GET['action'];
	$smarty->assign("action",$action);
	switch($module)
	{
		case 'receipt':
			break;
		case 'sales':
			require('cls/class.sales.php');
			$sales_id = $_GET['sales_id'];
			$sales_obj = new sales($sales_id);

			$commercial_details = $sales_obj->getSalesCommercial();
			$contact_details = $common_obj->getContacts('buy',$commercial_details['relationship_id']);

			// to format receipt item details array
			$item_details = $sales_obj->getSalesItems();
			$report_obj->getGroupedData($item_details);
			$item_total_array = $report_obj->getColumnSum($item_details, array("Quantity","Value"));
			$report_obj->appendTotal($report_obj->final_array, $item_total_array,'Total');
			$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Quantity'=>0,'Price'=>2,"Value"=>2));
			$smarty->assign('item_columns_style',array('Quantity'=>'text-align:right;','Price'=>'text-align:right;','Value'=>'text-align:right;','Currency'=>'text-align:center;','Uom'=>'text-align:center;'));

			break;
	}

	$smarty->assign("title","Demo");
	$smarty->assign("module",$module);
	$smarty->assign("commercial_details",$commercial_details);
	$smarty->assign("contact_details",$contact_details);
	$smarty->assign("item_details",$item_final_array);

	$smarty->clear_cache('print.tpl');
	return $smarty->fetch('print.tpl');
}

?>