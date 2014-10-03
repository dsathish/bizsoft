<?php
/**
 * Purpose of the file : receipt functions
 * @package Project Name or Module Name
 * @author Initials | Date
 * @todo Notes, If any
*/

class receipt
{
	public $receipt_id;

	/*
	* purpose of the function : constructor function
	* @todo Notes, if any
	*/
	function __construct($receipt_id)
	{
		$this->receipt_id = $receipt_id;
	}

	/*
	* purpose of the function : return commercial details of given receipt
	* @todo Notes, if any
	*/
	function getReceiptCommercial()
	{
		global $db;
		
		$query = 'SELECT re.receipt_ref AS "Receipt Ref", r.relationship_name AS "Supplier", to_char(receipt_date,\'dd/mm/yyyy\') AS "Receipt Date" 
		FROM '.receipt_table .' re 
		JOIN '.relationships_table.' r ON r.relationship_id=re.supplier_id 
		WHERE re.receipt_id = '.$this->receipt_id;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getReceiptCommercial, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : return receipt item details of given receipt item
	* @todo Notes, if any
	*/
	function getReceiptItems($item_id=NULL)
	{
		global $db;

		$item_id_condition = ($item_id)?' AND ri.item_id = '.$item_id:NULL;
		
		$query = 'SELECT pr.product_desc AS "Product", ri.quantity AS "Quantity", uo.uom_code AS "Uom", ri.price AS "Price", ri.price * ri.quantity AS "Value", cu.currency_code AS "Currency" 
		FROM '.receipt_items_table .' ri 
		JOIN '.products_table.' pr ON pr.product_id=ri.product_id 
		JOIN '.uom_table .' uo ON uo.uom_id=ri.uom_id 
		JOIN '.currency_table.' cu ON cu.currency_id=ri.currency_id 
		WHERE ri.receipt_id = '.$this->receipt_id.$item_id_condition ;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getReceiptItems, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : return tax details for given receipt_id
	* @todo Notes, if any
	*/
	function getReceiptTax()
	{
		global $db;

		$query = 'SELECT t.tax_desc AS "Tax", rt.tax_rate AS "Tax Rate", rt.tax_value AS "Tax Value"
		FROM '.receipt_tax_table.' rt JOIN '.tax_table.' t USING (tax_id) 
		WHERE rt.receipt_id = '.$this->receipt_id.' 
		ORDER BY rt.tax_sequence';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getReceiptTax, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : delete a given receipt_id
	* @todo Notes, if any
	*/
	function deleteReceipt()
	{
		global $db;

		$query = 'DELETE FROM '.receipt_table.' WHERE receipt_id = '.$this->receipt_id;
		$db->query($query);
	}
	/*
	* purpose of the function : return whether given receipt id exists
	* @todo Notes, if any
	*/
	function isReceiptExist()
	{
		global $db;

		$query = 'SELECT receipt_id FROM '.receipt_table.' WHERE receipt_id = '.$this->receipt_id;
		$db->query($query);
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isReceiptExist, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$receipt_id = $arr['receipt_id'];
		}
		return $receipt_id;
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