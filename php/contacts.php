<?php
require('conf/setup.inc.php');

$smarty->assign("content", requestAction());
$smarty->clear_cache("main.tpl");
$smarty->display("main.tpl");

function requestAction()
{
	global $db,$smarty,$common_obj;

	$activity = $_REQUEST['activity'];
	$action	  = $_REQUEST['action'];
	$relationship_id = $_REQUEST['relationship_id'];

	if($_POST)
	{
		// begin transaction
		$db->beginTransaction();

		$relationship_id = ($relationship_id)?$relationship_id:$common_obj->isRelationshipExist($activity,$_POST['relationship_name']);

		$relation_array['relationship_name'] = $_POST['relationship_name'];
		$relation_array['activity'] = $activity;

		$contacts_array['tax_detail'] = $_POST['tax_detail'];
		$contacts_array['payment_term'] = $_POST['payment_term'];
		$contacts_array['address1'] = $_POST['address1'];
		$contacts_array['address2'] = $_POST['address2'];
		$contacts_array['credit_days'] = $_POST['credit_days'];
		$contacts_array['phone_no'] = $_POST['phone_no'];
		$contacts_array['email'] = $_POST['email'];

		if($relationship_id && ($action == 'edit'))
		{
			$condition_array['relationship_id'] = $relationship_id;

			$common_obj->updateValues(relationships_table, $relation_array, $condition_array);
			unset($relation_array);
		}
		elseif(!$relationship_id)
		{
			$relationship_id = $common_obj->insertValues(relationships_table, $relation_array, relationships_relationship_id_seq );
			unset($relation_array);
		}
		else
		{
			$message = ' Entered relation Already Exists!';
		}
		
		if( ($action == 'edit') && ($common_obj->isContactExist($relationship_id)))
		{
			$condition_array['relationship_id'] = $relationship_id;
			
			$contact_id = $common_obj->updateValues(contacts_table, $contacts_array,$condition_array);
		}
		else
		{
			$contacts_array['relationship_id'] = $relationship_id;
			
			$contact_id = $common_obj->insertValues(contacts_table, $contacts_array, contacts_contact_id_seq);
		}
		unset($contacts_array);

		if(!$message)
		{
			// commit transaction
			$db->commit();
			header('location:contacts.php?activity='.$activity);
			exit;
		}
		else
		{
			// rollback transaction
			$db->rollBack();
			$smarty->assign('message',$message);
		}	
	}

	$smarty->assign("title","Demo");
	if($activity == 'sel')
	{
		$relation_label = 'Supplier';
	}
	elseif($activity == 'buy')
	{
		$relation_label = 'Buyer';
	}

	$smarty->assign("_page_heading_",$relation_label.' Contact Details');
	$smarty->assign("relation_label",$relation_label);
	$smarty->assign("action",$action);
	$smarty->assign("activity",$activity);

	if($relationship_id && $action)
	{
		$edit_contact_details = $common_obj->getContacts($activity,$relationship_id);
		
		$smarty->assign("relationship_id",$relationship_id);
		$smarty->assign("relationship_name",$edit_contact_details[0]['relationship_name']);
		$smarty->assign("tax_detail",$edit_contact_details[0]['tax_detail']);
		$smarty->assign("payment_term",$edit_contact_details[0]['payment_term']);
		$smarty->assign("address1",$edit_contact_details[0]['address1']);
		$smarty->assign("address2",$edit_contact_details[0]['address2']);
		$smarty->assign("phone_no",$edit_contact_details[0]['phone_no']);
		$smarty->assign("email",$edit_contact_details[0]['email']);
		$smarty->assign("credit_days",$edit_contact_details[0]['credit_days']);
		unset($edit_contact_details);
		
	}
	
	$fetchColumn = 're.relationship_name AS "'.$relation_label.'", tax_detail AS "TIN No.",payment_term AS "Payment Term",address1 AS "Registered Address", address2 AS "Delivery Address", credit_days AS "Credit Days", phone_no AS "Phone No", email AS "E Mail", \'<a href=contacts.php?activity='.$activity.'&action=edit&relationship_id=\'||re.relationship_id::text||\'>Edit</a>\' AS "Action" ';
	$contact_details = $common_obj->getContacts($activity,NULL,$fetchColumn);
	$smarty->assign("contact_details",$contact_details);

	$smarty->clear_cache('contacts.tpl');
	return $smarty->fetch('contacts.tpl');
}

?>