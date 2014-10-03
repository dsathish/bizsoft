<?php
/**
 * Purpose of the file : sales functions
 * @package Project Name or Module Name
 * @author Initials | Date
 * @todo Notes, If any
*/

class sales
{
	public $sales_id;

	/*
	* purpose of the function : constructor function
	* @todo Notes, if any
	*/
	function __construct($sales_id)
	{
		$this->sales_id = $sales_id;
	}
	/*
	* purpose of the function : return commercial details of given sales
	* @todo Notes, if any
	*/
	function getSalesCommercial($fetchColumn = '*')
	{
		global $db;
		
		$query = 'SELECT '.$fetchColumn.'
		FROM '.sales_table .' re 
		JOIN '.relationships_table.' r ON r.relationship_id=re.buyer_id 
		WHERE re.sales_id = '.$this->sales_id;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getSalesCommercial, Class : sales, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $arr;	
	}
	/*
	* purpose of the function : return sales item details of given sales item
	* @todo Notes, if any
	*/
	function getSalesItems($item_id=NULL)
	{
		global $db;
			
		$item_id_condition = ($item_id)?' AND si.item_id = '.$item_id:NULL;
		
		$query = 'SELECT pr.product_desc AS "Product", si.quantity AS "Quantity", uo.uom_code AS "Uom", si.price AS "Price", si.price * si.quantity AS "Value",cu.currency_code AS "Currency" 
		FROM '.sales_items_table .' si 
		JOIN '.products_table.' pr ON pr.product_id=si.product_id 
		JOIN '.uom_table .' uo ON uo.uom_id=si.uom_id 
		JOIN '.currency_table.' cu ON cu.currency_id=si.currency_id 
		WHERE si.sales_id = '.$this->sales_id.$item_id_condition ;
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
	* purpose of the function : return tax details for given sales_id
	* @todo Notes, if any
	*/
	function getSalesTax()
	{
		global $db;

		$query = 'SELECT t.tax_desc AS "Tax", st.tax_rate AS "Tax Rate", st.tax_value AS "Tax Value"
		FROM '.sales_tax_table.' st JOIN '.tax_table.' t USING (tax_id)
		WHERE st.sales_id = '.$this->sales_id.'
		ORDER BY st.tax_sequence';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getSalesTax, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : delete a given sales_id
	* @todo Notes, if any
	*/
	function deleteSales()
	{
		global $db;

		$query = 'DELETE FROM '.sales_table.' WHERE sales_id = '.$this->sales_id;
		$db->query($query);
	}
	/*
	* purpose of the function : return whether given sales id exists
	* @todo Notes, if any
	*/
	function isSalesExist()
	{
		global $db;

		$query = 'SELECT sales_id FROM '.sales_table.' WHERE sales_id = '.$this->sales_id;
		$db->query($query);
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isSalesExist, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$sales_id = $arr['sales_id'];
		}
		return $sales_id;
	}
	/*
	* purpose of the function : return week sales details
	* @todo Notes, if any
	*/
	function getSalesDetails()
	{
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