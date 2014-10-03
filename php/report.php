<?php require('setup.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $smarty,$db,$common_obj,$condition_arr;
	require('cls/class.report.php');

	$report_id = $_REQUEST['report_id'];
	$customization_id = $_REQUEST['customization_id'];
	$action = $_REQUEST['action'];

	$report_obj = new report($report_id,$customization_id);
	$report_id = $report_id ? $report_id : $report_obj->report_details[0]['report_id'];
	$customization_id = $customization_id ? $customization_id : $report_obj->report_details[0]['customization_id'];

	if ($_GET["action"] == "excel_report")
	{
		include("spread_sheet/global_spreadsheet.inc");
		$spsheet= new cSpreadsheet();

		$date=date("ymd");
		$report_array = $_SESSION["report_array"][$report_id][$customization_id];

		$spreadsheet_name = $date ."_". $report_obj->report_details[0]['report_name'];
		$worksheet_name = "Sheet1";
		$spsheet->createSpreadsheet($report_array, $spreadsheet_name, $worksheet_name, $report_obj->report_details[0]['customization_name']);
		exit();
	}
	else if ($_GET["action"] == "pdf_report")
	{
		
	}

	if($report_id && $customization_id)
	{
		// for date condition
		$from_date = ($_POST['from_date'])?$_POST['from_date']:$report_obj->report_details[0]['from_date'];
		$to_date = ($_POST['to_date'])?$_POST['to_date']:$report_obj->report_details[0]['to_date'];

		$formatted_from_date = ($_POST['from_date'])?$common_obj->getDBFormatDate($_POST['from_date']):$report_obj->report_details[0]['formatted_from_date'];
		$formatted_to_date = ($_POST['to_date'])?$common_obj->getDBFormatDate($_POST['to_date']):$report_obj->report_details[0]['formatted_to_date'];

		//check for date column for above customization
		if($report_obj->report_details[0]['date_column'])
			$report_obj->setDateConstraint($report_obj->report_details[0]['date_column'],$formatted_from_date,$formatted_to_date);

		$smarty->assign("title","Demo");
		$smarty->assign("report_id",$report_id);
		$smarty->assign("customization_id",$customization_id);
		$smarty->assign("_page_heading_",$report_obj->report_details[0]['customization_name']);
		$smarty->assign("from_date", $from_date);
		$smarty->assign("to_date", $to_date);
		$smarty->assign("customization_name",$report_obj->report_details[0]['customization_name']);
		$smarty->assign("action",$action);
		// get report customizations
		$smarty->assign("customization_array",$report_obj->getReportCust());
	
	
		// to get grouped data array
		$data_array = $report_obj->reportCustomData();
		unset($_SESSION["report_name"][$report_id][$customization_id], $_SESSION["report_array"][$report_id][$customization_id], $_SESSION["justification"][$report_id][$customization_id]);
		if($data_array)
		{
			$report_obj->getGroupedData($data_array,$report_obj->customization_details['group_columns']);
	
			// to get grand total array
			if($report_obj->grand_total_array)
			{
				$grand_total_array = $report_obj->getColumnSum($report_obj->grand_total_array, $report_obj->customization_details['total_columns']);
				$report_obj->appendTotal($report_obj->final_array, $grand_total_array,'Grand Total');
			}
	
			// get number formatted array
			$formatted_array = $report_obj->formatArray($report_obj->final_array,$report_obj->customization_details['decimal_places']);
		
			$smarty->assign('customization_data',$formatted_array);
			// to apply styles for report date
			$smarty->assign('columns_style',$report_obj->customization_details['columns_style']);
			
			$_SESSION["report_name"][$report_id][$customization_id] = $report_obj->report_details[0]['report_name'];
			$_SESSION["report_array"][$report_id][$customization_id] = $formatted_array;
			$_SESSION["justification"][$report_id][$customization_id] = $report_obj->customization_details['columns_style'];
		}
		else
		{
			$message = 'No Records!';
			$smarty->assign("message",$message);
		}
	
		// to write customization detail to xml file
 		$customization_arr['customization'] = $report_obj->report_details;
 		$customization_arr['columns'] = $report_obj->getCustomDetail();
		$common_obj->generateXML($customization_arr, $customization_id);

		$smarty->clear_cache('report.tpl');
		return $smarty->fetch('report.tpl');
	}
}

?>