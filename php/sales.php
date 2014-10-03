<?php
 require('setup.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	require('cls/class.order.php');
	require('cls/class.stock.php');
	global $smarty,$db,$common_obj;

	$order_obj = new order();
	$stock_obj = new stock();
	
	if($_POST)
	{
		// begin transaction
		$db->beginTransaction();

		if($_POST['product_desc'])
		{
			// sales commercial details
			$sales_value = 0;
			$buyer_id = $common_obj->isRelationshipExist('buy', $_POST['buyer']);

			if(!$buyer_id)
			{
				$buyer_array['activity'] = 'buy';
				$buyer_array['relationship_name'] = $_POST['buyer'];
				$buyer_id = $common_obj->insertValues(relationships_table, $buyer_array, relationships_relationship_id_seq);
				unset($buyer_array);
			}
			$sales_array['buyer_id'] = $buyer_id;
			$sales_array['sales_ref'] = $_POST['sales_ref'];
			$sales_array['sales_date'] = $common_obj->getDBFormatDate($_POST['sales_date']);
			$sales_id = $common_obj->insertValues(sales_table, $sales_array, sales_sales_id_seq);
			unset($sales_array);
	
			foreach($_POST['product_desc'] as $key => $value)
			{
				$product_id = $common_obj->isProductExist($_POST['product_desc'][$key]);
				$requested_qty = $_POST['quantity'][$key];
				$bal_qty = $stock_obj->getProductBalance($product_id);
	
				if($bal_qty < $requested_qty)
				{
					$message = 'Not enough quantity!';
					break;
				}
			}
			
			if(!$message && $_POST['product_desc'])
			{
				// sales item details
				$sales_item_value = 0;

				foreach($_POST['product_desc'] as $key => $value)
				{
					$product_id = $common_obj->isProductExist($_POST['product_desc'][$key]);
					
					$items_array['sales_id'] = $sales_id;
					$items_array['ref_id'] = $_POST['refer_id'][$key];
					$items_array['product_id'] = $product_id;
					$items_array['quantity'] = $_POST['quantity'][$key];
					$items_array['uom_id'] = $_POST['uom_id'][$key];
					$items_array['price'] = $_POST['price'][$key];
					$items_array['currency_id'] = $_POST['currency_id'];
					$items_array['conversion_rate'] = $_POST['conversion_rate'];
					$item_id = $common_obj->insertValues(sales_items_table, $items_array, sales_items_item_id_seq);
					unset($items_array);
					
					$sales_item_value = ($_POST['quantity'][$key]*$_POST['price'][$key]*$_POST['conversion_rate']);
					$sales_value += $sales_item_value;

			
					// transaction details
					$balance_stock_array = $stock_obj->getProductStock($product_id);
				
					if($balance_stock_array)
					{
						$sales_requested_qty = $_POST['quantity'][$key];
		
						$transaction_array['trans_date'] = $common_obj->getDBFormatDate($_POST['sales_date']);
						$transaction_array['trans_type'] = 'o';
						$transaction_array['trans_head'] = 'sal';
						$transaction_array['ref_id'] = $item_id;
						$transaction_array['uom_id'] = $_POST['uom_id'][$key];
				
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
			
								$transaction_array['quantity'] = $issue_stock_qty;
								$transaction_array['value'] = 1;
								$transaction_array['parent_id'] = $stock_array['trans_id'];
								$transaction_id = $common_obj->insertValues(transaction_details_table, $transaction_array, transaction_details_trans_id_seq);
							}
							unset($transaction_array,$balance_stock_array);
						}
					}
				}
		
				if($_POST['tax_id'])
				{
					$i = 1;
					$sales_tax_value = $common_obj->getTaxValue($sales_value,$_POST);

					// sales tax details
					foreach($_POST['tax_id'] as $key => $value)
					{
						$tax_details_array['sales_id'] = $sales_id;
						$tax_details_array['tax_id'] = $_POST['tax_id'][$key];
						$tax_details_array['tax_rate'] = $_POST['tax_rate'][$key];
						$tax_details_array['tax_sequence'] = $i;
						$common_obj->insertValues(sales_tax_table, $tax_details_array);
						unset($tax_details_array);
						$i++;
					}
				}

				$condition_array['sales_id']=$sales_id;
		
				$value_details_array['sales_value']=$sales_value;
				$value_details_array['taxed_value']=$sales_tax_value;
				
				$common_obj->updateValues(sales_table, $value_details_array,$condition_array);
				unset($value_details_array,$condition_array);

				// commit transaction
				$db->commit();
				header('location:details.php?module=sales&sales_id='.$sales_id);
				exit;
			}
			else
			{
				// rollback transaction
				$db->rollBack();
				$smarty->assign("message",$message);
			}
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign("message",'No sales items!');
		}
	}

	$smarty->assign("title","Demo");

	$smarty->assign('sales_date',date('d/m/Y'));
	$currency_array = $common_obj->getCurrency();
	$smarty->assign("currency_array",$currency_array);
	$buyer_js_array = $common_obj->setRelationshipJsArray('buy');
	$smarty->assign('buyer_js_array',$buyer_js_array);
	$product_js_array = $common_obj->setProductJsArray();
	$smarty->assign('product_js_array',$product_js_array);

	$refer_array = $order_obj->getPendingOrders(_ORDER_SO_TYPE);
	if($refer_array)
	{
		$smarty->assign('refer_array',json_encode($refer_array));
	}

	$tax_array = $common_obj->getTax();
	$smarty->assign('tax_array',json_encode($tax_array));

	$uom_array = $common_obj->getUOM();
	$smarty->assign('uom_array',json_encode($uom_array));
	
	$smarty->clear_cache('sales.tpl');
	return $smarty->fetch('sales.tpl');

}

?>