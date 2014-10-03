<?php
//register the xajax functions
require_once('xajax/xajax_core/xajax.inc.php');
$xajax = new xajax();

function alertOrdersInfo($activity,$relationship_name)
{
	global $common_obj;

	require_once('cls/class.stock.php');
	$order_obj = new order();	
	$objResponse = new xajaxResponse();

	$alert_message = null;
	
	$relationship_id = $common_obj->isRelationshipExist($activity,$relationship_name);
	
	if($relationship_id)
	{
		if($activity == 'sel')
			$order_type = 'po';
		elseif($activity == 'buy')
			$order_type = 'so';

		$order_array = $order_obj->getPendingOrders($order_type,$relationship_id);
		
		if($order_array)
		{
			$alert_message.='<b>Pending Orders for '.$relationship_name.' :</b>';
			foreach($order_array as $key=>$value)
			{
				$alert_message.=$value['refer_desc'].' .,';
			}
		}
		else
		{
			$alert_message.='<b>No Pending Orders for '.$relationship_name.' currently</b>';
		}		
	}
	
	$objResponse->assign("_alert_message","innerHTML", "<marquee>$alert_message</marquee>");
	return $objResponse;
}

$xajax->register(XAJAX_FUNCTION,"alertOrdersInfo");
$xajax->processRequest();
?>
