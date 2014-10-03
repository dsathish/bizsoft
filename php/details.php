<?php
require('conf/setup.inc.php');

$common_obj->isLoggedIn();

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $db,$smarty,$common_obj;

	require('cls/class.report.php');
	$report_obj = new report();
	$module = $_GET['module'];
	$action = $_GET['action'];
	$smarty->assign("action",$action);
	switch($module)
	{
		case 'indent':
		case 'order':
			require('cls/class.order.php');
			$order_id = $_GET['order_id'];
			$order_obj = new order($order_id);
			
			$values_exist = $order_obj->isOrderExist();
			if($values_exist)
			{
				if($action == 'delete')
				{
					$receipt_obj->deleteOrder();
					$message = 'The Order has been deleted!';
				}
				else
				{
					$query_string[0] = array('href'=>'javascript:void(0);','title'=>'Delete','onclick'=>'javascript:confirm_del(\'details.php?module='.$module.'&order_id='.$order_id.'&action=delete\');');
					
					if($module == 'indent')
					{
						$fetch_column = ' r.relationship_name AS "Supplier",od.order_ref AS "Reference", to_char(od.order_date,\'dd/mm/yyyy\') AS " Date" ';

						$items_fetch_column = ' pr.product_desc AS "Product", oi.order_quantity AS "Indent Quantity",oi.trans_quantity AS "Received Quantity", uo.uom_code AS "Uom", oi.price AS "Price", oi.price * oi.order_quantity AS "Value", cu.currency_code AS "Currency" ';
					}
					elseif($module == 'order')
					{
						$fetch_column = ' r.relationship_name AS "Buyer",od.order_ref AS "Reference", to_char(od.order_date,\'dd/mm/yyyy\') AS "Date" ';

						$items_fetch_column = ' pr.product_desc AS "Product", oi.order_quantity AS "Order Quantity",oi.trans_quantity AS "Supplier Quantity",uo.uom_code AS "Uom", oi.price AS "Price", oi.price * oi.order_quantity AS "Value", cu.currency_code AS "Currency" ';
					}
					$commercial_details = $order_obj->getOrderCommercial($fetch_column);
					
					$item_details = $order_obj->getOrderItems($items_fetch_column);
					
					// to format receipt item details array
					$report_obj->getGroupedData($item_details);
					$item_total_array = $report_obj->getColumnSum($item_details, array("Quantity","Value"));
					$report_obj->appendTotal($report_obj->final_array, $item_total_array,'Total');
					$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Quantity'=>0,'Price'=>2,"Value"=>2));
					$smarty->assign('item_columns_style',array('Quantity'=>'text-align:right;','Price'=>'text-align:right;','Value'=>'text-align:right;'));

				}
			}
			break;
		case 'receipt':
			require('cls/class.receipt.php');
			$receipt_id = $_GET['receipt_id'];
			$receipt_obj = new receipt($receipt_id);
			
			$values_exist = $receipt_obj->isReceiptExist();
			if($values_exist)
			{
				if($action == 'delete')
				{
					$receipt_obj->deleteReceipt();
					$message = 'The receipt has been deleted!';
				}
				else
				{
					$query_string[0] = array('href'=>'javascript:void(0);','title'=>'Delete','onclick'=>'javascript:confirm_del(\'details.php?module='.$module.'&receipt_id='.$receipt_id.'&action=delete\');');

					$commercial_details = $receipt_obj->getReceiptCommercial();
					$item_details = $receipt_obj->getReceiptItems($fetch_column);

					// to format receipt item details array
					$report_obj->getGroupedData($item_details);
					$item_total_array = $report_obj->getColumnSum($item_details, array("Quantity","Value"));
					$report_obj->appendTotal($report_obj->final_array, $item_total_array,'Total');
					$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Quantity'=>0,'Price'=>2,"Value"=>2));
					$smarty->assign('item_columns_style',array('Quantity'=>'text-align:right;','Price'=>'text-align:right;','Value'=>'text-align:right;'));
		
					$report_obj->final_array = array();
					// to format tax details array
					$tax_details = $receipt_obj->getReceiptTax();
					if($tax_details)
					{
						$report_obj->getGroupedData($tax_details);
						$tax_total_array = $report_obj->getColumnSum($tax_details, array("Tax Value"));
						$report_obj->appendTotal($report_obj->final_array, $tax_total_array,'Total');
						$tax_final_array = $report_obj->formatArray($report_obj->final_array,array('Tax Rate'=>0,'Tax Value'=>2));
						$smarty->assign('tax_columns_style',array('Tax Rate'=>'text-align:right;','Tax Value'=>'text-align:right;'));
					}
				}
			}
			break;
		case 'sales':
			require('cls/class.sales.php');
			$sales_id = $_GET['sales_id'];
			$sales_obj = new sales($sales_id);

			$values_exist = $sales_obj->isSalesExist();
			if($values_exist)
			{
				if($action == 'delete')
				{
					$sales_obj->deleteSales();
					$message = 'The sales has been deleted!';
				}
				else
				{
					$query_string[0] = array('href'=>'print.php?module='.$module.'&sales_id='.$sales_id.'&action=print','title'=>'Print');
					$query_string[1] = array('href'=>'javascript:void(0);','title'=>'Delete','onclick'=>'javascript:confirm_del(\'details.php?module='.$module.'&sales_id='.$sales_id.'&action=delete\');');

					$fetchColumn = 're.sales_ref AS "Sales Ref", r.relationship_name AS "Buyer", to_char(sales_date,\'dd/mm/yyyy\') AS "Sales Date"';
					$commercial_details = $sales_obj->getSalesCommercial($fetchColumn);
	
					// to format sales item details array
					$item_details = $sales_obj->getSalesItems();
					$report_obj->getGroupedData($item_details);
					$item_total_array = $report_obj->getColumnSum($item_details, array("Quantity","Value"));
					$report_obj->appendTotal($report_obj->final_array, $item_total_array,'Total');
					$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Quantity'=>0,'Price'=>2,"Value"=>2));
					$smarty->assign('item_columns_style',array('Quantity'=>'text-align:right;','Price'=>'text-align:right;','Value'=>'text-align:right;'));
	
					$report_obj->final_array = array();
					// to format tax details array
					$tax_details = $sales_obj->getSalesTax();
					if($tax_details)
					{
						$report_obj->getGroupedData($tax_details);
						$tax_total_array = $report_obj->getColumnSum($tax_details, array("Tax Value"));
						$report_obj->appendTotal($report_obj->final_array, $tax_total_array,'Total');
						$tax_final_array = $report_obj->formatArray($report_obj->final_array,array('Tax Rate'=>0,'Tax Value'=>2));
						$smarty->assign('tax_columns_style',array('Tax Rate'=>'text-align:right;','Tax Value'=>'text-align:right;'));
					}
				}
			}
			break;
		case 'stock':
			require('cls/class.stock.php');
			$trans_id = $_GET['trans_id'];
			$stock_obj = new stock($trans_id);
			
			$values_exist = $stock_obj->isStockExist($trans_id);
			if($values_exist)
			{
				//get transaction product commercials
				$commercial_details = $stock_obj->getStockCommercial();
	
				// to format stock transactions array
				$item_details = $stock_obj->getStockTransactions($trans_id);
				$report_obj->getGroupedData($item_details,array('Date','Type','Supplier/Buyer'));
				$item_total_array = $report_obj->getColumnSum($item_details, array('In Qty','Out Qty'));

				$report_obj->setColumnCummulative($report_obj->final_array,array('Balance'=>array('In Qty','Out Qty')));

				$report_obj->appendTotal($report_obj->final_array,$item_total_array,'Transaction Total');
				$cnt = count($report_obj->final_array);
				$report_obj->final_array[$cnt-1]['Balance'] = $report_obj->final_array[$cnt-1]['In Qty'] - $report_obj->final_array[$cnt-1]['Out Qty'];

				$report_obj->unsetColumn($report_obj->final_array,array('trans_id'));

				$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Quantity'=>0));
				$smarty->assign('item_columns_style',array('In Qty'=>'text-align:right;','Out Qty'=>'text-align:right;','Balance'=>'text-align:right;'));

			}
			break;
		case 'payment':
			require('cls/class.payment.php');
			$payment_id = $_GET['payment_id'];
			$payment_obj = new payment($payment_id);

			$values_exist = $payment_obj->isPaymentExist();
			if($values_exist)
			{
				if($action == 'delete')
				{
					$sales_obj->deletePayment();
					$message = 'The payment has been deleted!';
				}
				else
				{
					$query_string[0] = array('href'=>'print.php?module='.$module.'&payment_id='.$payment_id.'&action=print','title'=>'Print');
					$query_string[1] = array('href'=>'javascript:void(0);','title'=>'Delete','onclick'=>'javascript:confirm_del(\'details.php?module='.$module.'&payment_id='.$payment_id.'&action=delete\');');

					if($payment_obj->payee_activity_id == 'sel')
						$relationship_name = 'Supplier';
					elseif($payment_obj->payee_activity_id == 'buy')
						$relationship_name = 'Buyer';
					else	
						$relationship_name = 'Relationship Name';

					$fetchColumn = 'r.relationship_name AS "'.$relationship_name.'",pa.payment_ref AS "Payment Ref", to_char(pa.payment_date,\'dd/mm/yyyy\') AS "Payment Date", pm.payment_mode_name AS "Payment Mode" ';
					$commercial_details = $payment_obj->getPaymentCommercial($payment_id,$fetchColumn);

					// to format payment item details array
					$item_details = $payment_obj->getPaymentItems($payment_id);
					$report_obj->getGroupedData($item_details);
					$item_total_array = $report_obj->getColumnSum($item_details, array("Amount","Deductions","Value"));
					$report_obj->appendTotal($report_obj->final_array, $item_total_array,'Total');
					$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Amount'=>2,'Deductions'=>2,"Value"=>2));
					$smarty->assign('item_columns_style',array('Amount'=>'text-align:right;','Deductions'=>'text-align:right;','Value'=>'text-align:right;'));
				}
			}
			break;
		case 'handloan':
			require('cls/class.handloan.php');
			$handln_id = $_GET['handln_id'];
			$item_id = $_GET['item_id'];
			$handln_obj = new handloan($handln_id);
			
			$values_exist = $handln_obj->isHandloanExist();
			if($values_exist)
			{
				if($action == 'delete')
				{
					$receipt_obj->deleteOrder();
					$message = 'The Handloan has been deleted!';
				}
				else
				{
					$query_string[0] = array('href'=>'javascript:void(0);','title'=>'Delete','onclick'=>'javascript:confirm_del(\'details.php?module='.$module.'&handln_id='.$handln_id.'&action=delete\');');
					
					if($handln_obj->handln_type == 'issue')
					{
						$fetch_column = ' r.relationship_name AS "Issue to",hd.handln_ref AS "Reference", to_char(hd.handln_date,\'dd/mm/yyyy\') AS "Date" ';
					
						$items_fetch_column = ' CASE WHEN hi.ref_id IS NULL THEN \'NA\'::text ELSE \'<a href="./details.php?module=handloan&handln_id=\'||hir.handln_id||\' ">\'||hdr.handln_ref||\'</a>\' END AS "Receipt Reference" , pr.product_desc AS "Product", hi.issued_quantity AS "Issued Quantity", CASE WHEN hir.handln_id IS NULL THEN hi.received_quantity::text ELSE \'NA\'::text END AS "Received Quantity",uo.uom_code AS "Uom",hi.price AS "Price",round(hi.issued_quantity*hi.price,2) AS "Issued Value" , cu.currency_code AS "Currency" ';

						$items_group_column = 'hi.ref_id,hir.handln_id,hdr.handln_ref,pr.product_desc,hi.issued_quantity,hi.received_quantity,hi.price,cu.currency_code,uo.uom_code ';
					}
					elseif($handln_obj->handln_type == 'receive')
					{	
						$fetch_column = ' r.relationship_name AS "Received From",hd.handln_ref AS "Reference", to_char(hd.handln_date,\'dd/mm/yyyy\') AS "Date" ';

						$items_fetch_column = ' CASE WHEN hi.ref_id IS NULL THEN \'NA\'::text ELSE \'<a href="./details.php?module=handloan&handln_id=\'||hir.handln_id||\' ">\'||hdr.handln_ref||\'</a>\' END AS "Issued Reference" , pr.product_desc AS "Product",hi.received_quantity AS "Received Quantity",hi.issued_quantity AS "Issued Quantity",uo.uom_code AS "Uom",hi.price AS "Price",round(hi.received_quantity*hi.price,2) AS "Received Value" , cu.currency_code AS "Currency" ';
					}
					$commercial_details = $handln_obj->getHandlnCommercial($fetch_column);

					$item_details = $handln_obj->getHandlnItems($items_fetch_column,$items_group_column);
					
					// to format receipt item details array
					$report_obj->getGroupedData($item_details);
					$item_final_array = $report_obj->formatArray($report_obj->final_array,array('Quantity'=>0,'Price'=>2,"Value"=>2));
					$smarty->assign('item_columns_style',array('Issued Quantity'=>'text-align:right;','Received Quantity'=>'text-align:right;','Issued Back Quantity'=>'text-align:right;','Received Back Quantity'=>'text-align:right;','Price'=>'text-align:right;','Issued Value'=>'text-align:right;','Received Value'=>'text-align:right;'));

				}
			}
			break;
	}

	$smarty->assign("title","Demo");
	$smarty->assign("module",$module);
	if($values_exist && $action != 'delete')
	{
		$smarty->assign("query_string",$query_string);
		if($commercial_details)
		{
			$smarty->assign("commercial_details",$commercial_details);
		}
		$smarty->assign("item_details",$item_final_array);
		if($tax_final_array)
		{
			$smarty->assign("tax_details",$tax_final_array);
		}
	}
	else
	{
		$message = ($message) ? $message : 'No Details Available!';
		$smarty->assign("message",$message);
	}

	$smarty->clear_cache('details.tpl');
	return $smarty->fetch('details.tpl');
}

?>