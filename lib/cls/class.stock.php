<?php
/**
 * Purpose of the file : stock functions
 * @package Project stock
 * @author Initials | Date
 * @todo Notes, If any
*/

class stock
{
	
	public $trans_id;

	/*
	* purpose of the function : class constructor
	* @todo Notes, if any
	*/
	function __construct($trans_id=NULL)
	{
		$this->trans_id = $trans_id;
	}

	/*
	* purpose of the function : return product purchase trans_id ,corresponding balance stock qty
	* @todo Notes, if any
	*/
	function getProductStock($product_id)
	{
		global $db;

		$query = 'SELECT trans_id , bal_qty 
		FROM '.transaction_details_table.' td 
		JOIN '.transaction_balance_table.' tb USING (trans_id) 
		JOIN '.receipt_items_table.' ri ON ri.item_id=td.ref_id 
		WHERE ri.product_id = '.$product_id.' AND td.trans_head in (\'pur\',\'adj\') AND tb.bal_qty > 0
		UNION
		SELECT trans_id , bal_qty 
		FROM '.transaction_details_table.' td 
		JOIN '.transaction_balance_table.' tb USING (trans_id) 
		JOIN '.handloan_items_table.' hi ON hi.item_id=td.ref_id 
		WHERE hi.product_id = '.$product_id.' AND td.trans_head in (\'hdl\') AND tb.bal_qty > 0';
		$result =  $db->query($query);
	
		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getProductStock, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;	
		
	}

	/*
	* purpose of the function : return product total balance stock qty
	* @todo Notes, if any
	*/
	function getProductBalance($product_id)
	{
		$bal_qty_array = $this->getProductStock($product_id);

		$bal_qty = 0;
		if($bal_qty_array)
		{
			foreach($bal_qty_array as $value)
			{
				$bal_qty+= $value['bal_qty'];
			}
		}

		return $bal_qty;
	}

	/*
	* purpose of the function : return whether stock exist for given trans_id
	* @todo Notes, if any
	*/	
	function isStockExist($trans_id=NULL)
	{
		global $db;

		$trans_id=($trans_id)?$trans_id:$this->trans_id;

		$query = 'SELECT trans_id FROM '.transaction_details_table.' WHERE trans_id = '.$trans_id;
		$db->query($query);
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isStockExist, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$trans_id = $arr['trans_id'];
		}
		return $trans_id;
	}

	/*
	* purpose of the function : return stock transactions array for given trans_id
	* @todo Notes, if any
	*/
	function getStockTransactions($trans_id=NULL)
	{
		global $db;

		$trans_id=($trans_id)?$trans_id:$this->trans_id;

		$query = 'SELECT  to_char(td.trans_date,\'dd/mm/yyyy\') AS "Date", td.trans_head AS "Type", re.relationship_name AS "Supplier/Buyer", td.quantity AS "In Qty", 0::int AS "Out Qty", 0::int AS "Balance" , \'<a href="details.php?module=receipt&receipt_id=\'||rc.receipt_id||\'">\'||rc.receipt_ref||\'<\a>\' AS "Refer",td.trans_id
		FROM '.transaction_details_table.' td
		JOIN '.receipt_items_table.' ri ON ri.item_id = td.ref_id
		JOIN '.receipt_table.' rc ON rc.receipt_id = ri.receipt_id
		JOIN '.relationships_table.' re ON re.relationship_id = rc.supplier_id
		WHERE trans_type = \''._TRANS_IN_TYPE.'\' 
		AND trans_head IN (\''._TRANS_PUR_HEAD.'\',\''._TRANS_ADJ_HEAD.'\',\''._TRANS_HDL_HEAD.'\')
		AND trans_id = '.$trans_id.'
		UNION
		SELECT to_char(td.trans_date,\'dd/mm/yyyy\') AS "Date", td.trans_head AS "Type", re.relationship_name AS "Supplier/Buyer", 0::int AS "In Qty", td.quantity AS "Out Qty", 0::int AS "Balance" , \'<a href="details.php?module=sales&sales_id=\'||sa.sales_id||\'">\'||sa.sales_ref||\'<\a>\' AS "Refer", td.trans_id
		FROM '.transaction_details_table.' td
		JOIN '.sales_items_table.' si ON si.item_id = td.ref_id
		JOIN '.sales_table.' sa ON sa.sales_id = si.sales_id
		JOIN '.relationships_table.' re ON re.relationship_id = sa.buyer_id
		WHERE trans_type = \''._TRANS_OUT_TYPE.'\' 
		AND trans_head  IN (\''._TRANS_SAL_HEAD.'\',\''._TRANS_ADJ_HEAD.'\',\''._TRANS_HDL_HEAD.'\') 
		AND parent_id = '.$trans_id.'
		ORDER BY trans_id';
		$db->query($query);
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getStockTransactions, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}

	/*
	* purpose of the function : return stock commercial details for given trans_id
	* @todo Notes, if any
	*/
	function getStockCommercial($trans_id=NULL)
	{
		global $db;

		$trans_id=($trans_id)?$trans_id:$this->trans_id;

		$query = 'SELECT product_desc AS "Product Desc"
		FROM '.transaction_details_table.' td
		JOIN '.receipt_items_table.' ri ON ri.item_id = td.ref_id
		JOIN '.products_table.' pr ON pr.product_id = ri.product_id
		WHERE td.trans_type = \''._TRANS_IN_TYPE.'\' 
		AND td.trans_head IN (\''._TRANS_PUR_HEAD.'\',\''._TRANS_ADJ_HEAD.'\',\''._TRANS_HDL_HEAD.'\')
		AND td.trans_id = '.$trans_id;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getStockCommercials, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $arr;
	}

	/*
	* purpose of the function : return stock commercial details for given trans_id
	* @todo Notes, if any
	*/
	function getStockAdjustDetails($trans_date)
	{
		global $db;

		$query = 'SELECT receipt_ref AS "Transaction Reference",trans_date AS "Transaction Date"
		FROM '.transaction_details_table.' td
		JOIN '.receipt_items_table.' ri ON ri.item_id = td.ref_id
		JOIN '.receipt_table.' re ON re.receipt_id = ri.receipt_id
		WHERE td.trans_type = \''._TRANS_IN_TYPE.'\' 
		AND td.trans_head = \''._TRANS_ADJ_HEAD.'\' 
		AND td.trans_date = \''.$trans_date.'\' 
		UNION 
		SELECT sales_ref AS "Transaction Reference",trans_date AS "Transaction Date"
		FROM '.transaction_details_table.' td
		JOIN '.sales_items_table.' si ON si.item_id = td.ref_id
		JOIN '.sales_table.' sa ON sa.sales_id = si.sales_id
		WHERE td.trans_type = \''._TRANS_IN_TYPE.'\' 
		AND td.trans_head = \''._TRANS_ADJ_HEAD.'\' 
		AND td.trans_date = \''.$trans_date.'\'
		;';

		$db->query($query);
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getAdjustDetails, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $arr;


	}
	/*
	* purpose of the function : return stock reorder details
	* @todo Notes, if any
	*/
	function getReorderDetails()
	{
		global $db;

		$query = 'SELECT pr.product_desc , CASE WHEN sum(tb.bal_qty) < pr.reorder THEN \'<font color="red">\'||round(sum(tb.bal_qty),0)||\'</font> \' WHEN sum(tb.bal_qty) = pr.reorder THEN \'<font color="yellow">\'||round(sum(tb.bal_qty),0)||\'</font> \'||uo.uom_code END AS stock_qty
		FROM '.products_table.' pr 
		JOIN '.receipt_items_table. ' ri ON ri.product_id = pr.product_id
		JOIN '.transaction_details_table. ' td ON td.ref_id = ri.item_id AND td.trans_type = \'i\'
		JOIN '.transaction_balance_table. ' tb ON tb.trans_id = td.trans_id
		JOIN '.uom_table. ' uo ON uo.uom_id = td.uom_id
		GROUP BY pr.product_desc , pr.reorder , uo.uom_code
		HAVING sum(tb.bal_qty) <= pr.reorder ';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getReorderDetails, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchALL(PDO::FETCH_ASSOC);
		}
		return $arr;	
	}
	/*
	* purpose of the function : return stock ageing details
	* @todo Notes, if any
	*/
	function getAgeingDetails()
	{
		global $db;

		$query='SELECT product_desc ,max(age) , avg(age), CASE WHEN max(age) >= avg(age) THEN \'<font color="red">\'||max(age)||\'</font>\' WHEN max(age) = avg(age) THEN \'<font color="yellow">\'||max(age)||\'</font>\' END AS age FROM (SELECT pr.product_desc, current_date-td.trans_date AS age
		FROM '.products_table.' pr 
		JOIN '.receipt_items_table. ' ri ON ri.product_id = pr.product_id
		JOIN '.transaction_details_table. ' td ON td.ref_id = ri.item_id AND td.trans_type = \'i\'
		JOIN '.transaction_balance_table. ' tb ON tb.trans_id = td.trans_id
		WHERE tb.bal_qty > 0 GROUP BY pr.product_desc,td.trans_date HAVING current_date-td.trans_date > 0) R 
		GROUP BY product_desc ORDER BY age DESC LIMIT 10';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getAgeingDetails, Class : stock, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchALL(PDO::FETCH_ASSOC);
		}
		return $arr;	
	}
	/*
	* purpose of the function : destructor function
	* @todo Notes, if any
	*/
	function __destruct()	
	{
	}
}

?>