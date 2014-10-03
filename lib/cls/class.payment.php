<?php
/**
 * Purpose of the file : payment functions
 * @package Project Name or Module Name
 * @author Initials | Date
 * @todo Notes, If any
*/

class payment
{
	public $payment_id;
	public $payee_activity_id;

	/*
	* purpose of the function : constructor function
	* @todo Notes, if any
	*/
	function __construct($payment_id=NULL)
	{
		if($payment_id)
		{
			$this->payment_id = $payment_id;
			$this->payee_activity_id = $this->payeeActivity($payment_id);
		}
	}

	/*
	* purpose of the function : return pending payments for the receipts of given supplier
	* @todo Notes, if any
	*/
	function getPendingReceiptPayments($supplier_id)
	{
		global $db;

		$query = 'SELECT re.receipt_id AS refer_id, re.receipt_ref||\'-\'||to_char(receipt_date,\'dd/mm/yyyy\')||\'-Bal:\'||round((re.receipt_value+COALESCE(re.taxed_value,0)-COALESCE(re.payment_value,0)),2)::varchar AS refer_desc
		FROM '.receipt_table .' re 
		WHERE re.supplier_id = '.$supplier_id.' AND (re.receipt_value+COALESCE(re.taxed_value,0)-COALESCE(re.payment_value,0))>0;';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getPendingReceiptsPayments, Class : payments, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}	

		return $arr;
	}
	/*
	* purpose of the function : return pending payments for the Sales of given buyer
	* @todo Notes, if any
	*/
	function getPendingSalesPayments($buyer_id)
	{
		global $db;

		$query = 'SELECT sa.sales_id AS refer_id, sa.sales_ref||\'-\'||to_char(sales_date,\'dd/mm/yyyy\')||\'-Bal:\'||round((sa.sales_value+COALESCE(sa.taxed_value,0)-COALESCE(sa.payment_value,0)),2)::varchar AS refer_desc
		FROM '.sales_table .' sa 
		WHERE sa.buyer_id = '.$buyer_id.' AND (sa.sales_value+COALESCE(sa.taxed_value,0)-COALESCE(sa.payment_value,0))>0;';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getPendingReceiptsPayments, Class : payments, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}	

		return $arr;
	}
	/*
	* purpose of the function : return commercial details of given payment
	* @todo Notes, if any
	*/
	function getPaymentCommercial($payment_id,$fetchColumn = '*')
	{
		global $db;
		
		$payment_id = ($payment_id)?$payment_id:$this->payment_id;

		$query = 'SELECT '.$fetchColumn.'
		FROM '.payments_table .' pa 
		JOIN '.payment_mode_table.' pm USING(payment_mode_id) 
		JOIN '.relationships_table.' r USING(relationship_id)
		WHERE pa.payment_id = '.$payment_id;

		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getPaymentCommercial, Class : Payment, '.$err_info[2]);
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
	function getPaymentItems($payment_id,$item_id=NULL)
	{
		global $db;
			
		$payment_id = ($payment_id)?$payment_id:$this->payment_id;
		$item_id_condition = ($item_id)?' AND rpr.receipt_id = '.$item_id:NULL;

		if($this->payee_activity_id == 'sel')
		{ 
			$query = 'SELECT receipt_ref AS "Receipt Reference",to_char(receipt_date,\'dd/mm/yyyy\') AS "Receipt Date",round(rpr.amount,2) AS "Amount",round(rpr.deductions,2) AS "Deductions",round((rpr.amount-rpr.deductions),2) AS "Value"
			FROM '.receipt_payment_relation_table .' rpr 
			JOIN '.receipt_table.' re USING(receipt_id)
			WHERE rpr.payment_id = '.$payment_id.$item_id_condition;
		}
		elseif($this->payee_activity_id == 'buy')
		{	
			$query = 'SELECT sales_ref AS "Sales Reference",to_char(sales_date,\'dd/mm/yyyy\') AS "Sales Date",round(spr.amount,2) AS "Amount",round(spr.deductions,2) AS "Deductions",round((spr.amount-spr.deductions),2) AS "Value"
			FROM '.sales_payment_relation_table .' spr
			JOIN '.sales_table.' sa USING(sales_id)
			WHERE spr.payment_id = '.$payment_id.$item_id_condition;
		}
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getPaymentItems, Class : Payment, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : return whether given payment id exists
	* @todo Notes, if any
	*/
	function isPaymentExist($payment_id = NULL)
	{
		global $db;
		
		$payment_id = ($payment_id)?$payment_id:$this->payment_id;

		$payment_detail_array=$this->getPaymentCommercial($payment_id,'status');
		
		if($payment_detail_array['status'] == 'act')
			return $payment_id;
		else
			return NULL;
	}
	/*
	* purpose of the function : return payment relationship activity for given payment_id
	* @todo Notes, if any
	*/
	function payeeActivity($payment_id = NULL)
	{
		global $db;

		$payment_id = ($payment_id)?$payment_id:$this->payment_id;

		$query='SELECT r.activity 
		FROM '.payments_table.' pa 
		JOIN '.relationships_table.' r USING(relationship_id)
		WHERE payment_id = '.$payment_id;

		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : payeeActivity, Class : Payment, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			return $arr['activity'];
		}

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