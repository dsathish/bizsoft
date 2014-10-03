<?php require('setup.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	require('cls/class.payment.php');
	global $smarty,$db,$common_obj;
	
	$payment_obj = new payment();
	
	$action = $_REQUEST["action"];
	$relationship_id = $_POST["relationship_id"];
	
	if($_POST['save'])
	{
		// begin transaction
		$db->beginTransaction();

		if($_POST['payment_items'])
		{
			// payment commercial details
			$payment_array['relationship_id'] = $relationship_id;
			$payment_array['payment_mode_id'] = $_POST['payment_mode_id'];
			$payment_array['payment_ref'] = $_POST['payment_ref'];
			$payment_array['payment_date'] = $common_obj->getDBFormatDate($_POST['payment_date']);
			$payment_id = $common_obj->insertValues(payments_table, $payment_array, payments_payment_id_seq);
			unset($payment_array);

			// payment item details
			foreach($_POST['payment_items'] as $key => $value)
			{
				$payment_value = 0;
	
				$items_array['payment_id'] = $payment_id;
				$items_array['amount'] = $_POST['amount'][$key];
				$items_array['deductions'] = $_POST['deductions'][$key];
				
				if($action == 'sel')
				{
					$receipt_id = $_POST['payment_items'][$key];
					$items_array['receipt_id'] = $receipt_id;
					
					$common_obj->insertValues(receipt_payment_relation_table, $items_array);

					//update payment value for above receipt
					$condition_array['receipt_id'] = $receipt_id;

					$value_details_array['payment_value'] = $items_array['amount']-$items_array['deductions'];

					$common_obj->updateValues(receipt_table, $value_details_array,$condition_array,'rep');
				}
				elseif($action == 'buy')
				{
					$sales_id = $_POST['payment_items'][$key];
					$items_array['sales_id'] = $sales_id;
					
					$common_obj->insertValues(sales_payment_relation_table, $items_array);

					//update payment value for above sales
					$condition_array['sales_id'] = $sales_id;

					$value_details_array['payment_value'] = $items_array['amount']-$items_array['deductions'];

					$common_obj->updateValues(sales_table, $value_details_array,$condition_array,'rep');
				} 
				unset($items_array,$value_details_array,$condition_array);
			}

			// commit transaction
			$db->commit();
 			header('location:details.php?module=payment&payment_id='.$payment_id);
			exit;
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign("message",'No payment items!');
		}
	}

	$smarty->assign("title","Demo");
	$smarty->assign('action',$action);
	$smarty->assign('_post',$_POST);

	$smarty->assign('payment_date',date('d/m/Y'));
	$payment_mode_array = $common_obj->getPaymentMode();
	$smarty->assign("payment_mode_array",$payment_mode_array);
	$relationship_array = $common_obj->getRelationships(array($action));
	$smarty->assign('relationship_array',$relationship_array);
	
	if($relationship_id && ($action == 'sel'))
	{
		$payment_reference_array = $payment_obj->getPendingReceiptPayments($relationship_id);
		$smarty->assign('payment_reference_array',$payment_reference_array);
		$smarty->assign('js_refer_array',json_encode($payment_reference_array));
	}
	elseif($relationship_id && ($action == 'buy'))
	{
		$payment_reference_array = $payment_obj->getPendingSalesPayments($relationship_id);
		$smarty->assign('payment_reference_array',$payment_reference_array);
		$smarty->assign('js_refer_array',json_encode($payment_reference_array));
	}
	$smarty->clear_cache('payment.tpl');
	return $smarty->fetch('payment.tpl');
}

?>