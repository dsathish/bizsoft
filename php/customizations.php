<?php require('setup.php');$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $smarty, $common_obj;
	include('cls/class.report.php');

	$report_id = $_REQUEST['report_id'];
	$customization_id = $_REQUEST['customization_id'];
	$action = $_REQUEST['action'];

	$report_obj = new report($report_id, $customization_id);
	if($action == 'del')
	{
		$report_obj->deleteCustomization();
		
		header('location:report.php?report_id='.$report_id);
		exit;
	}

	if($_POST)
	{
		if($_POST['is_default'])
		{
			$common_obj->updateValues(customizations_table, array('is_default'=>'0'), array('report_id'=>$report_id,'is_default'=>'1'));
		}

		if($customization_id)
		{
			$customization_array['customization_name'] = $_POST['customization_name'];
			$customization_array['sub_total'] = $_POST['sub_total'];
			$customization_array['grand_total'] = $_POST['grand_total'];
			if($_POST['is_default'])
			{
				$customization_array['is_default'] = $_POST['is_default'];
			}
			$common_obj->updateValues(customizations_table, $customization_array, array('customization_id'=>$customization_id));
			unset($customization_array);
	
			foreach($_POST['column_id'] as $key => $value)
			{
				$column_id = $_POST['column_id'][$key];
	
				$columns_array['display_name'] = $_POST['display_name'][$key];
				$columns_array['is_group'] = $_POST['is_group'][$column_id];
				$columns_array['is_filter'] = $_POST['is_filter'][$column_id];
				$columns_array['sort_order'] = $_POST['sort_order'][$key];
				$columns_array['display_total'] = $_POST['display_total'][$column_id];
				$columns_array['display_order'] = $_POST['display_order'][$key];
				$columns_array['decimal_places'] = $_POST['decimal_places'][$key];
				$columns_array['date_format'] = $_POST['date_format'][$key];
				$columns_array['style'] = $_POST['style'][$key];
	
				$condition_array['customization_id'] = $customization_id;
				$condition_array['column_id'] = $column_id;
	
				$common_obj->updateValues(customization_columns_table, $columns_array, $condition_array);
				unset($columns_array);
			}
		}
		else
		{
			$customization_array['customization_name'] = $_POST['customization_name'];
			$customization_array['report_id'] = $_POST['report_id'];
			$customization_array['sub_total'] = $_POST['sub_total'];
			$customization_array['grand_total'] = $_POST['grand_total'];
			$customization_array['date_condition[1]'] = $_POST['date_column'];
			if($_POST['is_default'])
			{
				$customization_array['is_default'] = $_POST['is_default'];
			}
			$customization_id = $common_obj->insertValues(customizations_table, $customization_array, customizations_customization_id_seq);
			unset($customization_array);
	
			foreach($_POST['column_id'] as $key => $value)
			{
				$column_id = $_POST['column_id'][$key];
	
				$columns_array['customization_id'] = $customization_id;
				$columns_array['column_id'] = $column_id;
				$columns_array['display_name'] = $_POST['display_name'][$key];
				$columns_array['is_group'] = $_POST['is_group'][$column_id];
				$columns_array['is_filter'] = $_POST['is_filter'][$column_id];
				$columns_array['sort_order'] = $_POST['sort_order'][$key];
				$columns_array['display_total'] = $_POST['display_total'][$column_id];
				$columns_array['display_order'] = $_POST['display_order'][$key];
				$columns_array['decimal_places'] = $_POST['decimal_places'][$key];
				$columns_array['date_format'] = $_POST['date_format'][$key];
				$columns_array['style'] = $_POST['style'][$key];
	
				$common_obj->insertValues(customization_columns_table, $columns_array);
				unset($columns_array);
			}
		}

		header('location:report.php?customization_id='.$customization_id);
		exit;
	}

	if($action == 'add')
	{
		$customization_id = $report_obj->report_details[0]['default_id'];
	}

	$file_name = _xml_path_.$customization_id.'.xml';
	if($common_obj->isValidXML($file_name))
	{
		$contents = file_get_contents($file_name);
		$xml_array = $common_obj->xml2array($contents);
	}

	$smarty->assign("title","Demo");
	$smarty->assign("action",$action);
	$smarty->assign("customization_array",$xml_array['root']['customization']);
	$smarty->assign("columns_array",$xml_array['root']['columns']);

	$smarty->clear_cache('customizations.tpl');
	return $smarty->fetch('customizations.tpl');
}

?>