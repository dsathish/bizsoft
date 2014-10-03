<?php
require('conf/setup.inc.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	require('cls/class.order.php');
	global $smarty,$db,$common_obj;

	$order_obj = new order();

	if($_POST)
	{
		// begin transaction
		$db->beginTransaction();

		if($_POST['product_desc'])
		{
			// receipt commercial details
			$receipt_value = 0;
			$supplier_id = $common_obj->isRelationshipExist('sel', $_POST['supplier']);

			if(!$supplier_id)
			{
				$supplier_array['activity'] = 'sel';
				$supplier_array['relationship_name'] = $_POST['supplier'];
				$supplier_id = $common_obj->insertValues(relationships_table, $supplier_array, relationships_relationship_id_seq);
				unset($supplier_array);
			}
			$receipt_array['supplier_id'] = $supplier_id;
			$receipt_array['receipt_ref'] = $_POST['receipt_ref'];
			$receipt_array['receipt_date'] = $common_obj->getDBFormatDate($_POST['receipt_date']);
			$receipt_id = $common_obj->insertValues(receipt_table, $receipt_array, receipt_receipt_id_seq);
			unset($receipt_array);

			// receipt item details
			foreach($_POST['product_desc'] as $key => $value)
			{
				$receipt_item_value = 0; 

				$product_id = $common_obj->isProductExist($_POST['product_desc'][$key]);
				if(!$product_id)
				{
					$product_array['product_desc'] = $_POST['product_desc'][$key];
					$product_id = $common_obj->insertValues(products_table, $product_array, products_product_id_seq);
					unset($product_array);
				}
				$receipt_item_value = ($_POST['quantity'][$key]*$_POST['price'][$key]*$_POST['conversion_rate']);
				$receipt_value += $receipt_item_value;

				$items_array['receipt_id'] = $receipt_id;
				$items_array['ref_id'] = $_POST['refer_id'][$key];
				$items_array['product_id'] = $product_id;
				$items_array['quantity'] = $_POST['quantity'][$key];
				$items_array['uom_id']	= $_POST['uom_id'][$key];
				$items_array['price'] = $_POST['price'][$key];
				$items_array['currency_id'] = $_POST['currency_id'];
				$items_array['conversion_rate'] = $_POST['conversion_rate'];
				$item_id = $common_obj->insertValues(receipt_items_table, $items_array, receipt_items_item_id_seq);
				unset($items_array);
		
				// transaction details
				$transaction_array['trans_date'] = $common_obj->getDBFormatDate($_POST['receipt_date']);
				$transaction_array['trans_type'] = 'i';
				$transaction_array['trans_head'] = 'pur';
				$transaction_array['quantity'] = $_POST['quantity'][$key];
				$transaction_array['uom_id'] = $_POST['uom_id'][$key];
				$transaction_array['value'] = $receipt_item_value;
				$transaction_array['ref_id'] = $item_id;
				$transaction_id = $common_obj->insertValues(transaction_details_table, $transaction_array, transaction_details_trans_id_seq);
				unset($transaction_array);
			}

			// receipt tax details
			if($_POST['tax_id'])
			{
				$i = 1;
				$receipt_tax_value = $common_obj->getTaxValue($receipt_value,$_POST);

				foreach($_POST['tax_id'] as $key => $value)
				{
					$tax_details_array['receipt_id'] = $receipt_id;
					$tax_details_array['tax_id'] = $_POST['tax_id'][$key];
					$tax_details_array['tax_rate'] = $_POST['tax_rate'][$key];
					$tax_details_array['tax_sequence'] = $i;
					$common_obj->insertValues(receipt_tax_table, $tax_details_array);
					unset($tax_details_array);
					$i++;
				}
			}

			$condition_array['receipt_id']=$receipt_id;

			$value_details_array['receipt_value']=$receipt_value;
			$value_details_array['taxed_value']=$receipt_tax_value;
			
			$common_obj->updateValues(receipt_table, $value_details_array,$condition_array);
			unset($value_details_array,$condition_array);

			// commit transaction
			$db->commit();
			header('location:details.php?module=receipt&receipt_id='.$receipt_id);
			exit;
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign("message",'No receipt items!');
		}
	}

	$smarty->assign("title","Demo");
	$smarty->assign('receipt_date',date('d/m/Y'));
	$currency_array = $common_obj->getCurrency();
	$smarty->assign("currency_array",$currency_array);
	$supplier_js_array = $common_obj->setRelationshipJsArray('sel');
	$smarty->assign('supplier_js_array',$supplier_js_array);
	$product_js_array = $common_obj->setProductJsArray();
	$smarty->assign('product_js_array',$product_js_array);

	$refer_array = $order_obj->getPendingOrders(_ORDER_PO_TYPE);
	if($refer_array)
	{
		$smarty->assign('refer_array',json_encode($refer_array));
	}
	
	$tax_array = $common_obj->getTax();
	$smarty->assign('tax_array',json_encode($tax_array));
	
	$uom_array = $common_obj->getUOM();
	$smarty->assign('uom_array',json_encode($uom_array));

	$smarty->clear_cache('receipt.tpl');
	return $smarty->fetch('receipt.tpl');
}

?>