<?php
require('conf/setup.inc.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $db,$smarty,$common_obj;

	$activity = $_REQUEST["activity"];
	$relationship_id = $_POST["relationship_id"];
	
	if($_POST)
	{
		// begin transaction
		$db->beginTransaction();

		if($_POST['product_desc'])
		{
			// sales commercial details
			$order_date = $common_obj->getDBFormatDate($_POST['order_date']);
			$relationship_id = $common_obj->isRelationshipExist($activity, $_POST['relationship']);

			if(!$relationship_id)
			{
				$relationship_array['activity'] = $activity;
				$relationship_array['relationship_name'] = $_POST['relationship'];
				$relationship_id = $common_obj->insertValues(relationships_table, $relationship_array, relationships_relationship_id_seq);
				unset($relationship_array);
			}
			$order_array['relationship_id'] = $relationship_id;
			$order_array['order_ref'] = $_POST['order_ref'];
			$order_array['order_date'] = $order_date;
			
			if($activity == 'sel' )
			{
				$order_array['order_type'] = _ORDER_PO_TYPE;
			}
			elseif	($activity == 'buy' )
			{
				$order_array['order_type'] = _ORDER_SO_TYPE;
			}

			$order_id = $common_obj->insertValues(orders_table, $order_array, orders_order_id_seq);
			unset($order_array);

			foreach($_POST['product_desc'] as $key => $value)
			{
				// order item details

				$product_id = $common_obj->isProductExist($_POST['product_desc'][$key]);
				if(!$product_id)
				{
					$product_array['product_desc'] = $_POST['product_desc'][$key];
					$product_id = $common_obj->insertValues(products_table, $product_array, products_product_id_seq);
					unset($product_array);
				}

				$items_array['order_id'] = $order_id;
				$items_array['product_id'] = $product_id;
				$items_array['order_quantity'] = $_POST['quantity'][$key];
				$items_array['uom_id'] = $_POST['uom_id'][$key];
				$items_array['price'] = $_POST['price'][$key];
				$items_array['currency_id'] = $_POST['currency_id'];
				$items_array['conversion_rate'] = $_POST['conversion_rate'];
				$item_id = $common_obj->insertValues(order_items_table, $items_array, order_items_item_id_seq);
				unset($items_array);
				
			}

			// commit transaction
			$db->commit();

			if($activity == 'sel')
				$module = 'indent';
			elseif($activity == 'buy')
				$module = 'order';

			header('location:details.php?module='.$module.'&order_id='.$order_id);
			exit;
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign("message",'No order items!');
		}
	}

	$smarty->assign("title","Demo");
	$smarty->assign('activity',$activity);

	$smarty->assign('order_date',date('d/m/Y'));
	$currency_array = $common_obj->getCurrency();
	$smarty->assign("currency_array",$currency_array);
	$relationship_js_array = $common_obj->setRelationshipJsArray($activity);
	$smarty->assign('relationship_js_array',$relationship_js_array);
	$product_js_array = $common_obj->setProductJsArray();
	$smarty->assign('product_js_array',$product_js_array);

	$tax_array = $common_obj->getTax();
	$smarty->assign('tax_array',json_encode($tax_array));

	$uom_array = $common_obj->getUOM();
	$smarty->assign('uom_array',json_encode($uom_array));
	
	$smarty->clear_cache('order.tpl');
	return $smarty->fetch('order.tpl');

}

?>