<?php
 require('setup.php');
require('cls/class.report.php');

$common_obj->isLoggedIn();

$query = "SELECT customization_id, report_id FROM ".customizations_table." ORDER BY customization_id";
$result = $db->query($query);
if($result)
{
	while($row = $result->fetch(PDO::FETCH_ASSOC))
	{
		$report_obj = new report($row['report_id'],$row['customization_id']);
		$customization_arr['customization'] = $report_obj->report_details;
		$customization_arr['columns'] = $report_obj->getCustomDetail();
		$common_obj->generateXML($customization_arr, $row['customization_id']);
		unset($customization_arr);
	}
	echo "Re-created XML Files";
}

?>