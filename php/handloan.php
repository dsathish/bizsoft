<?php
 require('setup.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	include('cls/class.handloan.php');
	global $smarty,$db,$common_obj;

	$handln_obj = new handloan();

	$handln_type = $_REQUEST['handln_type'];
	
	if($_POST['product_desc'])
	{
		$handln_date =  $common_obj->getDBFormatDate($_POST['handln_date']);

		// begin transaction
		$db->beginTransaction();
		
		foreach($_POST['product_desc'] as $key => $value)
		{
			$_POST['product_id'][$key] = $common_obj->getProductId($_POST['product_desc'][$key]);
			
			$requested_qty = $_POST['quantity'][$key];
			
			if($handln_type == 'issue')
			{
				require('cls/class.stock.php');
				$stock_obj = new stock();
			
				$bal_qty = $stock_obj->getProductBalance($_POST['product_id'][$key]);

				if($bal_qty < $requested_qty)
				{
					$message = 'Not enough quantity!';
					break;
				}
			}
		}		
		
		if(!$message)
		{
			$supplier_id = $common_obj->getRelationshipId('buy', $_POST['supplier']);

			$handln_array['relationship_id'] = $supplier_id;
			$handln_array['handln_ref'] = $_POST['handln_ref'];
			$handln_array['handln_date'] = $handln_date;
			$handln_array['currency_id'] = 1;
			$handln_id = $common_obj->insertValues(handloan_table, $handln_array,handloan_handln_id_seq);
			unset($handln_array);

			foreach($_POST['product_desc'] as $key => $value)
			{
				$items_array['handln_id'] = $handln_id;
				$items_array['ref_id'] = $_POST['refer_id'][$key];
				$items_array['product_id'] = $_POST['product_id'][$key];
				
				if($handln_type == 'issue')
				{
					$items_array['issued_quantity'] = $_POST['quantity'][$key];
				}
				elseif($handln_type == 'receive')
				{
					$items_array['received_quantity'] = $_POST['quantity'][$key];
				}

				$items_array['uom_id'] = $_POST['uom_id'][$key];
				$items_array['price'] = $_POST['price'][$key];
				$items_array['conversion_rate'] = '1';
				$item_id = $common_obj->insertValues(handloan_items_table, $items_array, handloan_items_item_id_seq);
				unset($items_array);

				// transaction details
				$transaction_array['trans_date'] = $handln_date;
				$transaction_array['trans_head'] = _TRANS_HDL_HEAD;
				$transaction_array['uom_id'] = $_POST['uom_id'][$key];
				$transaction_array['ref_id'] = $item_id;
				
				if($handln_type == 'issue')
				{
					$balance_stock_array = $stock_obj->getProductStock($_POST['product_id'][$key]);
					
					$update_details_array['issued_quantity'] = $_POST['quantity'][$key];
					$sales_requested_qty = $_POST['quantity'][$key];
		
					foreach($balance_stock_array as $stock_array)
					{
						while($sales_requested_qty > 0)
						{
							if($sales_requested_qty <= $stock_array['bal_qty'])
							{
								$issue_stock_qty = $sales_requested_qty;
								$sales_requested_qty = 0;
							}
							elseif($sales_requested_qty > $stock_array['bal_qty'])
							{
								$issue_stock_qty = $stock_array['bal_qty'];
								$sales_requested_qty = $sales_requested_qty - $stock_array['bal_qty'];
							}
		
							$transaction_array['trans_type'] = _TRANS_OUT_TYPE;
							$transaction_array['quantity'] = $issue_stock_qty;
							$transaction_array['value'] = 1;
							$transaction_array['parent_id'] = $stock_array['trans_id'];
							$transaction_id = $common_obj->insertValues(transaction_details_table, $transaction_array, transaction_details_trans_id_seq);
						}
					}
				}
				elseif($handln_type == 'receive')
				{
					$update_details_array['received_quantity'] = $_POST['quantity'][$key];
					
					$transaction_array['trans_type'] = _TRANS_IN_TYPE;
					$transaction_array['quantity'] = $_POST['quantity'][$key];
					$transaction_array['value'] = $_POST['quantity'][$key] * $_POST['price'][$key];
					$transaction_id = $common_obj->insertValues(transaction_details_table, $transaction_array, transaction_details_trans_id_seq);
				}

				if($_POST['refer_id'][$key])
				{
					$condition_array['item_id'] = $_POST['refer_id'][$key];

					$common_obj->updateValues(handloan_items_table, $update_details_array,$condition_array,'rep');
				}
			
				unset($transaction_array,$balance_stock_array);
			}

			// commit transaction
			$db->commit();
			header('location:details.php?module=handloan&handln_id='.$handln_id);
			exit;
		}
		else
		{
			// rollback transaction
			$db->rollBack();
		}
	}
	elseif($_POST)
	{
	$message ='No handloan items';
	}


	$smarty->assign("title","Demo");
	$smarty->assign('handln_type',$handln_type);
	$smarty->assign("message",$message);

	$refer_array = $handln_obj->getReferenceDetails($handln_type);
	if($refer_array)
	{
		$smarty->assign('refer_array',json_encode($refer_array));
	}

	$smarty->assign('handln_date',date('d/m/Y'));
	$currency_array = $common_obj->getCurrency();
	$smarty->assign("currency_array",$currency_array);
	$supplier_js_array = $common_obj->setRelationshipJsArray('buy');
	$smarty->assign('supplier_js_array',$supplier_js_array);
	$product_js_array = $common_obj->setProductJsArray();
	$smarty->assign('product_js_array',$product_js_array);

	$uom_array = $common_obj->getUOM();
	$smarty->assign('uom_array',json_encode($uom_array));

	$smarty->clear_cache('handloan.tpl');
	return $smarty->fetch('handloan.tpl');
}

?>