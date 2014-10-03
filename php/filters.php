<?php
 require('setup.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $smarty, $common_obj;
	include('cls/class.report.php');

	$report_id = $_REQUEST['report_id'];
	$customization_id = $_REQUEST['customization_id'];
	$report_obj = new report($report_id, $customization_id);

	if($_POST)
	{
		foreach($_POST['column_id'] as $column_id => $values)
		{
			$default_value = implode('~#~', $values);
			
			$common_obj->updateValues(customization_columns_table, array("default_value"=>$default_value), array("customization_id"=>$customization_id, "column_id"=>$column_id));
		}

		header('location:report.php?report_id='.$report_id.'&customization_id='.$customization_id);
		exit;
	}

	$smarty->assign("title","Demo");
	$smarty->assign("customization_id",$customization_id);
	$smarty->assign("report_id",$report_id);

	$filters_array = $report_obj->getFilters();
	$smarty->assign("filters_array", $filters_array);

	$smarty->clear_cache('filters.tpl');
	return $smarty->fetch('filters.tpl');
}

?>