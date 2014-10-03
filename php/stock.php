<?php
require('conf/setup.inc.php');

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	require('cls/class.stock.php');
	global $smarty,$db,$common_obj;
	
	$stock_obj = new stock();
	
	if($_POST['save'])
	{
		// begin transaction
		$db->beginTransaction();

		if($_POST['product_desc'])
		{
			$receipt_value = $sales_value = 0;
			$receipt_item_value = $sales_item_value = 0;
			$transaction_date = $common_obj->getDBFormatDate($_POST['transaction_date']);
			// stock item details
			foreach($_POST['product_desc'] as $key => $value)
			{
				$product_id = $common_obj->isProductExist($_POST['product_desc'][$key]);
				if(!$product_id)
				{
					$product_array['product_desc'] = $_POST['product_desc'][$key];
					$product_id = $common_obj->insertValues(products_table, $product_array, products_product_id_seq);
					unset($product_array);
				}
				$bal_qty = $stock_obj->getProductBalance($product_id);

				if(($bal_qty==0)  OR (($_POST['quantity'][$key]-$bal_qty)>0))
				{
					$adj_requested_qty = $_POST['quantity'][$key]-$bal_qty;	

					if(!$receipt_id)
					{
						$receipt_array['supplier_id'] = 1;
						$receipt_array['receipt_ref'] = $_POST['stock_ref'];
						$receipt_array['receipt_date'] = $transaction_date;
						$receipt_id = $common_obj->insertValues(receipt_table, $receipt_array, receipt_receipt_id_seq);
						unset($receipt_array);	
					}
					
					$items_array['receipt_id'] = $receipt_id;
					$items_array['product_id'] = $product_id;
					$items_array['quantity'] = $adj_requested_qty;
					$items_array['uom_id']	= $_POST['uom_id'][$key];
					$items_array['price'] = $_POST['price'][$key];
					$items_array['currency_id'] = $_POST['currency_id'];
					$items_array['conversion_rate'] = $_POST['conversion_rate'];
					$item_id = $common_obj->insertValues(receipt_items_table, $items_array, receipt_items_item_id_seq);
					unset($items_array);
			
					$receipt_item_value = ($adj_requested_qty*$_POST['price'][$key]*$_POST['conversion_rate']);
					$receipt_value += $receipt_item_value;

					// transaction details
					$transaction_array['trans_date'] = $transaction_date;
					$transaction_array['trans_type'] = 'i';
					$transaction_array['trans_head'] = 'adj';
					$transaction_array['quantity'] = $adj_requested_qty;
					$transaction_array['uom_id'] = $_POST['uom_id'][$key];
					$transaction_array['value'] = $receipt_item_value;
					$transaction_array['ref_id'] = $item_id;
					$transaction_id = $common_obj->insertValues(transaction_details_table, $transaction_array, transaction_details_trans_id_seq);

					unset($transaction_array,$adj_requested_qty,$bal_qty,$product_id,$receipt_item_value);
				}
				elseif($bal_qty>0)
				{
					// transaction details
					$balance_stock_array = $stock_obj->getProductStock($product_id);
					$adj_requested_qty = $bal_qty-$_POST['quantity'][$key];	
				
					if(!$sales_id)
					{
						$sales_array['buyer_id'] = 1;
						$sales_array['sales_ref'] = $_POST['stock_ref'];
						$sales_array['sales_date'] = $transaction_date;
						$sales_id = $common_obj->insertValues(sales_table, $sales_array, sales_sales_id_seq);
						unset($sales_array);
					}

					$items_array['sales_id'] = $sales_id;
					$items_array['product_id'] = $product_id;
					$items_array['quantity'] = $adj_requested_qty;
					$items_array['uom_id'] = $_POST['uom_id'][$key];
					$items_array['price'] = $_POST['price'][$key];
					$items_array['currency_id'] = $_POST['currency_id'];
					$items_array['conversion_rate'] = $_POST['conversion_rate'];
					$item_id = $common_obj->insertValues(sales_items_table, $items_array, sales_items_item_id_seq);
					unset($items_array);
					
					$sales_item_value = ($adj_requested_qty*$_POST['price'][$key]*$_POST['conversion_rate']);
					$sales_value += $sales_item_value;

					if($balance_stock_array)
					{
						$transaction_array['trans_date'] = $transaction_date;
						$transaction_array['trans_type'] = 'o';
						$transaction_array['trans_head'] = 'adj';
						$transaction_array['ref_id'] = $item_id;
						$transaction_array['uom_id'] = $_POST['uom_id'][$key];
				
						foreach($balance_stock_array as $stock_array)
						{
							while($adj_requested_qty > 0)
							{
								if($adj_requested_qty <= $stock_array['bal_qty'])
								{
									$issue_stock_qty = $adj_requested_qty;
									$adj_requested_qty = 0;
								}
								elseif($adj_requested_qty > $stock_array['bal_qty'])
								{
									$issue_stock_qty = $stock_array['bal_qty'];
									$adj_requested_qty = $adj_requested_qty - $stock_array['bal_qty'];
								}
			
								$transaction_array['quantity'] = $issue_stock_qty;
								$transaction_array['value'] = 1;
								$transaction_array['parent_id'] = $stock_array['trans_id'];
								$transaction_id = $common_obj->insertValues(transaction_details_table, $transaction_array, transaction_details_trans_id_seq);
							}
						}
					}
					unset($transaction_array,$balance_stock_array,$adj_requested_qty,$product_id,$sales_item_value);
				}
			}
			
			if($receipt_value > 0)
			{
				$condition_array['receipt_id']=$receipt_id;
	
				$value_details_array['receipt_value']=$receipt_value;
				$value_details_array['taxed_value']=0;
				
				$common_obj->updateValues(receipt_table, $value_details_array,$condition_array);
				unset($value_details_array,$condition_array);
			}
	
			if($sales_value > 0)
			{
				$condition_array['sales_id']=$sales_id;
		
				$value_details_array['sales_value']=$sales_value;
				$value_details_array['taxed_value']=$sales_tax_value;
				
				$common_obj->updateValues(sales_table,$value_details_array,$condition_array);
				unset($value_details_array,$condition_array);

			}
			
		
			// commit transaction
			$db->commit();
 			echo "<script language='javascript'>alert('Successfully Adjusted Stocks');</script>";
			
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign("message",'No Stock Items!');
		}
	}

	$smarty->assign("title","Demo");
	$smarty->assign('action',$action);
	$smarty->assign('_post',$_POST);

	$smarty->assign('transaction_date',date('d/m/Y'));
	$currency_array = $common_obj->getCurrency();
	$smarty->assign("currency_array",$currency_array);

	$product_js_array = $common_obj->setProductJsArray();
	$smarty->assign('product_js_array',$product_js_array);
	
	$uom_array = $common_obj->getUOM();
	$smarty->assign('uom_array',json_encode($uom_array));

	$smarty->clear_cache('stock.tpl');
	return $smarty->fetch('stock.tpl');
}

?>