<?php
/**
 * Purpose of the file : order functions
 * @package Project Name or Module Name
 * @author Initials | Date
 * @todo Notes, If any
*/

class order
{
	public $order_id;
	public $order_type;
	public $order_status;

	/*
	* purpose of the function : constructor function
	* @todo Notes, if any
	*/
	function __construct($order_id=NULL)
	{
		if($order_id)
		{
			$this->order_id = $order_id;
		
			$order_details_array = $this->getOrderCommercial();
			$this->order_type = $order_details_array['order_type'];
			$this->order_status = $order_details_array['order_status'];
		}
	}

	/*
	* purpose of the function : return pending order of purchase order or sales order
	* @todo Notes, if any
	*/
	function getPendingOrders($order_type=null,$relationship_id=null)
	{
		global $db;

		$order_type_condition=($order_type)?' AND od.order_type =\''.$order_type.'\' ':null;

		$relationship_id_condition=($relationship_id)?' AND od.relationship_id = '.$relationship_id:null;

		$query='SELECT oi.item_id AS refer_id,od.order_ref||\' | \'||pr.product_desc||\' | Bal Qty:\'||round(abs(oi.order_quantity-oi.trans_quantity),3)||\' \'||uo.uom_code AS refer_desc
		FROM '.orders_table.' od 
		JOIN '.order_items_table.' oi USING(order_id)
		JOIN '.relationships_table.' re USING(relationship_id)
		JOIN '.products_table.' pr USING(product_id)
		JOIN '.uom_table.' uo USING(uom_id)
		WHERE (oi.order_quantity-oi.trans_quantity) > 0 '.$order_type_condition.$relationship_id_condition.'
        	ORDER BY re.relationship_name';
		$result = $db->query($query);
		
		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getPendingOrders, Class : order '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}	

		return $arr;
	}
	/*
	* purpose of the function : return order commercial details of given order
	* @todo Notes, if any
	*/
	function getOrderCommercial($fetch_column ='*')
	{
		global $db;
	
		$query = 'SELECT '.$fetch_column.' 
		FROM '.orders_table .' od 
		JOIN '.relationships_table.' r ON r.relationship_id=od.relationship_id
		WHERE od.order_id = '.$this->order_id;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getOrderCommercial, Class : order, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : return order item details of given order item
	* @todo Notes, if any
	*/
	function getOrderItems($fetch_column ='*',$item_id=NULL)
	{
		global $db;

		$item_id_condition = ($item_id)?' AND ri.item_id = '.$item_id:NULL;
		
		$query = 'SELECT '.$fetch_column.'  
		FROM '.order_items_table .' oi 
		JOIN '.products_table.' pr ON pr.product_id=oi.product_id 
		JOIN '.uom_table .' uo ON uo.uom_id=oi.uom_id 
		JOIN '.currency_table.' cu ON cu.currency_id=oi.currency_id 
		WHERE oi.order_id = '.$this->order_id.$item_id_condition ;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getOrderItems, Class : order, '.$err_info[2]);
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
	function deleteOrder()
	{
		global $db;

		$query = 'DELETE FROM '.orders_table.' WHERE order_id = '.$this->order_id;
		$db->query($query);
	}
	/*
	* purpose of the function : return whether given receipt id exists
	* @todo Notes, if any
	*/
	function isOrderExist()
	{
		global $db;

		$query = 'SELECT order_id FROM '.orders_table.' WHERE order_id = '.$this->order_id.' AND order_status = \'act\' ' ;
		$db->query($query);
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isOrderExist, Class : receipt, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$order_id = $arr['order_id'];
		}
		return $order_id;
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