<?php
/**
 * Purpose of the file : stock functions
 * @package Project stock
 * @author Initials | Date
 * @todo Notes, If any
*/

class handloan
{
	public $handln_id;
	public $handln_type;

	/*
	* purpose of the function : class constructor
	* @todo Notes, if any
	*/
	function __construct($handln_id=NULL)
	{
		if($handln_id)
		{
			$this->handln_id = $handln_id;
			$details_array = $this->getHandlnCommercial('td.trans_type');
			
			//set where transaction type of issue/receive for given handln_id
			if($details_array['trans_type'] == _TRANS_IN_TYPE)
			{
				$this->handln_type = 'receive';
			}
			elseif($details_array['trans_type'] == _TRANS_OUT_TYPE)
			{
				$this->handln_type = 'issue';
			}
		}
	}
	/*
	* purpose of the function : class constructor
	* @todo Notes, if any
	*/
	function isHandloanExist()
	{
		$array = $this->getHandlnCommercial('handln_id');

		if($array)
		{
			return $array['handln_id'];
		}
	}
	/*
	* purpose of the function : return pending handloan issue back/receive back details based on given handln type resp
	* @todo Notes, if any
	*/
	function getReferenceDetails($handln_type)
	{
		global $db;
		
		if($handln_type == 'issue')
		{
			//when handln is issued or issued back , check for handln receipts
			$trans_type_condition = ' AND td.trans_type = \''._TRANS_IN_TYPE.'\' ';
		}
		elseif($handln_type == 'receive')
		{
			//when handln is received or received back , check for handln issues
			$trans_type_condition = ' AND td.trans_type = \''._TRANS_OUT_TYPE.'\' ';
		}
		
		$query='SELECT hi.item_id AS refer_id,re.relationship_name||\' | \'||pr.product_desc||\' | Bal Qty:\'||round(abs(hi.issued_quantity-hi.received_quantity),3)::text||\' \'||uo.uom_code AS refer_desc
		FROM '.handloan_table.' hd
		JOIN '.handloan_items_table.' hi USING(handln_id)
		JOIN '.transaction_details_table.' td ON td.ref_id = hi.item_id
		JOIN '.relationships_table.' re USING(relationship_id)
		JOIN '.products_table.' pr USING(product_id)
 		JOIN '.uom_table.' uo ON uo.uom_id=hi.uom_id 
		WHERE td.trans_head = \''._TRANS_HDL_HEAD.'\' AND hi.ref_id IS NULL'.$trans_type_condition.'  
		AND abs(hi.issued_quantity-hi.received_quantity)>0
		GROUP BY hi.item_id,re.relationship_name,pr.product_desc,hi.issued_quantity,hi.received_quantity,uo.uom_code';
		$result = $db->query($query);
		
		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getReferenceDetails, Class : handln '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}	

		return $arr;
	}
	/*
	* purpose of the function : return handloan commercial details for given handloan id
	* @todo Notes, if any
	*/
	function getHandlnCommercial($fetch_column='*')
	{
		global $db;
		
		$query = 'SELECT '.$fetch_column.'
		FROM '.handloan_table.' hd
		JOIN '.handloan_items_table.' hi USING(handln_id)
		JOIN '.transaction_details_table.' td ON td.ref_id=hi.item_id
		JOIN '.relationships_table.' r USING(relationship_id)
		WHERE td.trans_head=\''._TRANS_HDL_HEAD.'\' AND hd.handln_id = '.$this->handln_id;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getHandlnCommercial, Class : handln '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		
		return $arr;
	}
	/*
	* purpose of the function : return handloan commercial details for given handloan id
	* @todo Notes, if any
	*/
	function getHandlnItems($fetch_column='*',$group_column=NULL,$item_id=NULL)
	{
		global $db;
		
		$item_id_condition = ($item_id)?' AND ri.item_id = '.$item_id:NULL;

		$group_condition = ($group_column)? ' GROUP BY '.$group_column:NULL;

		$query = 'SELECT '.$fetch_column.'
		FROM '.handloan_table.' hd
		JOIN '.handloan_items_table.' hi USING(handln_id)
		LEFT JOIN '.handloan_items_table.' hir ON hir.item_id=hi.ref_id
		LEFT JOIN '.handloan_table.' hdr ON hdr.handln_id=hir.handln_id
		JOIN '.relationships_table.' re ON re.relationship_id=hd.relationship_id
		JOIN '.products_table.' pr ON pr.product_id=hi.product_id
		JOIN '.uom_table .' uo ON uo.uom_id=hi.uom_id 
		JOIN '.currency_table.' cu ON cu.currency_id=hd.currency_id
		WHERE hd.handln_id = '.$this->handln_id.$item_id_condition.$group_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getHandlnItems, Class : handln '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
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