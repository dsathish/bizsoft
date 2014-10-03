<?php
 require('setup.php');

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $smarty,$db,$common_obj;
	$action = $_REQUEST['action'];
	$product_id = $_REQUEST['product_id'];
	if($_POST)
	{

		// begin transaction
		$db->beginTransaction();

		$product_id = ($product_id )?$product_id:$common_obj->isProductExist($_POST['product_desc']);
		
		$product_array['product_desc'] = $_POST['product_desc'];
		$product_array['product_category_id'] = $common_obj->getProductCategoryId($_POST['product_category']);
		$product_array['product_liter'] = $_POST['product_liter'];
		$product_array['cost_price'] = $_POST['cost_price'];
		$product_array['cp_currency_id'] = $_POST['cp_currency_id'];
		$product_array['selling_price'] = $_POST['selling_price'];
		$product_array['sp_currency_id'] = $_POST['sp_currency_id'];
		$product_array['reorder'] = $_POST['reorder'];
			
		if($product_id && ($action=='edit'))
		{
				
			$condition_array['product_id'] = $product_id;
			
			$product_id = $common_obj->updateValues(products_table, $product_array,$condition_array);
				
		}							
		elseif(!$product_id)
		{
			$product_id = $common_obj->insertValues(products_table, $product_array, products_product_id_seq);
		}
		else
		{
			$message= 'Product already exists!';
		}

		unset($product_array);
		if(!$message)
		{
			// commit transaction
			$db->commit();
			header('location:product.php');
			exit;
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign('message',$message);
		}	
		
	}

	if( $product_id && $action )
	{
		$product_details = $common_obj->getProduct($product_id);
		$smarty->assign("product_id",$product_details[0]['product_id']);
		$smarty->assign("product_desc",$product_details[0]['product_desc']);
		$smarty->assign("product_category",$product_details[0]['product_category_desc']);
		$smarty->assign("cost_price",$product_details[0]['cost_price']);
		$smarty->assign("selling_price",$product_details[0]['selling_price']);
		$smarty->assign("reorder",$product_details[0]['reorder']);
		
		unset($product_details);
	}
	
	$smarty->assign('_page_heading_','Master Product Entry');
	$smarty->assign('action',$action);
	
	$product_js_array = $common_obj->setProductJsArray();
	$smarty->assign('product_js_array',$product_js_array);

	$product_category_js_array = $common_obj->setParamJsArray('product_category');
	$smarty->assign('product_category_js_array',$product_category_js_array);

	$currency_array = $common_obj->getCurrency();
	$smarty->assign("currency_array",$currency_array);

	$fetchColumn = ' pr.product_desc AS "Product",pc.product_category_desc AS "Category" , cpc.currency_code||\' \'||round(pr.cost_price,2)::text as "Supplier Price",spc.currency_code||\' \'||round(selling_price,2)::text as "Buyer Price",reorder as "Reorder",\'<a href=product.php?action=edit&product_id=\'||product_id::text||\'>Edit</a>\' AS "Action" ';

	$product_table_details = $common_obj->getProduct(NULL,$fetchColumn);
	$smarty->assign("product_details",$product_table_details);
	
	$smarty->clear_cache('product.tpl');
	return $smarty->fetch('product.tpl');
}

?>