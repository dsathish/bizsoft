<?php
/**
 * Purpose of the file : common functions
 * @package Project Name or Module Name
 * @author Initials | Date
 * @todo Notes, If any
*/

class common
{
	/*
	* purpose of the function : check login identity details
	* @todo Notes, if any
	*/
	function checkLogin($username='',$password='')
	{
		global $db;
		$query = "SELECT user_id FROM ".users_table." WHERE  user_name = '$username' AND password = '".base64_encode($password)."'" ;
		$result = $db->query($query);
		if (!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : checkLogin, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr =$result->fetch(PDO::FETCH_ASSOC);
			return $arr['user_id'];
		}
	}
	/*
	* purpose of the function : check whether login in exists
	* @todo Notes, if any
	*/
      	function isLoggedIn()
	{
		if (isset($_SESSION['user_id']))
		{
			return true;
		}
		else
		{
			header("location:"._offset_path_."/index.php");
			exit;
		}
	}
	/*
	* purpose of the function : fetch user details for given user id
	* @todo Notes, if any
	*/
      	function getUserInfo($user_id,$fetchColumn='*')
	{	
		global $db;
		
		$query='SELECT '.$fetchColumn.'
		FROM '.users_table.'
		WHERE user_id = '.$user_id;
		$result = $db->query($query);
		if (!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getUserInfo, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr =$result->fetch(PDO::FETCH_ASSOC);
			return $arr;
		}
	}
	/*
	* purpose of the function : get currency details
	* @todo Notes, if any
	*/
	function getCurrency($currency_id = NULL)
	{
		global $db;
		$currency_id_condition = '';
		if($currency_id)
		{
			$currency_id_condition = ' WHERE currency_id = '.$currency_id;
		}
		$query = 'SELECT * FROM '.currency_table.$currency_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getCurrency, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : get city details
	* @todo Notes, if any
	*/
	function getCity($city_id = NULL)
	{
		global $db;
		$city_id_condition = '';
		if($city_id)
		{
			$city_id_condition = ' WHERE city_id = '.$city_id;
		}
		$query = 'SELECT * FROM '.city_table.$city_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getCity, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : check whether city name is exist or not
	* @todo Notes, if any
	*/
	function isCityExist($city_name)
	{
		global $db;
   		$query = "SELECT city_id FROM ".city_table." WHERE city_name ILIKE '".trim($city_name)."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isCityExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$city_id = $arr['city_id'];
		}
		return $city_id;
	}
	/*
	* purpose of the function : get country details
	* @todo Notes, if any
	*/
	function getCountry($country_id = NULL)
	{
		global $db;
		$country_id_condition = '';
		if($country_id)
		{
			$country_id_condition = ' WHERE country_id = '.$country_id;
		}
		$query = 'SELECT * FROM '.country_table.$country_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getCountry, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : check whether country name exist or not
	* @todo Notes, if any
	*/
	function isCountryExist($country_name)
	{
		global $db;
   		$query = "SELECT country_id FROM ".country_table." WHERE country_name ILIKE '".trim($country_name)."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isCountryExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$country_id = $arr['country_id'];
		}
		return $country_id;
	}
	/*
	* purpose of the function : get relationship details
	* @todo Notes, if any
	*/
	function getRelationships($activity = array(), $relationship_id = NULL)
	{
		global $db;
		$condition_array = array();
		if($activity)
		{
			$condition_array[]= "activity IN ('".implode("','",$activity)."')";
		}
		if($relationship_id)
		{
			$condition_array[] = ' relationship_id = '.$relationship_id;
		}
		$condition_string = '';
		foreach($condition_array as $key => $value)
		{
			if($key == 0)
			{
				$condition_string = ' WHERE '.$value;
			}
			else
			{
				$condition_string .= ' AND '.$value;
			}
		}

		$query = 'SELECT * FROM '.relationships_table.$condition_string;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getRelationships, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : check whether relationship name exist or not
	* @todo Notes, if any
	*/
	function isRelationshipExist($activity, $relationship_name)
	{
		global $db;
   		$query = "SELECT relationship_id FROM ".relationships_table." WHERE activity = '".$activity."' AND relationship_name ILIKE '".trim($relationship_name)."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isRelationshipExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$relationship_id = $arr['relationship_id'];
		}
		return $relationship_id;
	}
	/*
	* purpose of the function : check whether relationship name exist or if not insert and return a new relationship_id
	* @todo Notes, if any
	*/
	function getRelationshipId($activity, $relationship_name)
	{
		global $db;

		$relationship_id = $this->isRelationshipExist($activity,$relationship_name);

		if(!$relationship_id)
		{
			$relationship_array['activity'] = 'buy';
			$relationship_array['relationship_name'] = $_POST['supplier'];
			$relationship_id = $this->insertValues(relationships_table, $relationship_array, relationships_relationship_id_seq);
			unset($relationship_array);
		}

		return $relationship_id;
	}
	/*
	* purpose of the function : check whether relationship name exist or not
	* @todo Notes, if any
	*/
	function isContactExist($relationship_id)
	{
		global $db;
   		$query = "SELECT relationship_id FROM ".contacts_table." WHERE relationship_id = '".$relationship_id."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isContactExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$relationship_id = $arr['relationship_id'];
		}
		return $relationship_id;
	}
	/*
	* purpose of the function : get tax details
	* @todo Notes, if any
	*/
	function getTax($tax_id = NULL)
	{
		global $db;
		$tax_id_condition = '';
		if($tax_id)
		{
			$tax_id_condition = ' WHERE tax_id = '.$tax_id;
		}
		$query = 'SELECT * FROM '.tax_table.$tax_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getTax, Class : common, '.$err_info[2]);
			exit;
		}
		elseif($tax_id)
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : check whether tax desc is exist or not
	* @todo Notes, if any
	*/
	function isTaxExist($tax_desc)
	{
		global $db;
   		$query = "SELECT tax_id FROM ".tax_table." WHERE tax_desc ILIKE '".trim($tax_desc)."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isTaxExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$tax_id = $arr['tax_id'];
		}
		return $tax_id;
	}
	/*
	* purpose of the function : return tax js array
	* @todo Notes, if any
	*/
	function setTaxJsArray()
	{
		global $db;
		$arr = $this->getTax();
		$js_string = '<SCRIPT TYPE="text/javascript">';
		$js_string .= "var tax_array = new Array();";
		if ($arr)
		{
			$js_string .= "tax_array = [";
			
			foreach($arr as $key => $value)
			{
				$values_array[] = "'".addslashes($value['tax_desc'])."'";
			}
			$js_string .= implode(',',$values_array);
			$js_string .= ']';
		}
		$js_string .= '</SCRIPT>';
		return $js_string;
	}
	/*
	* purpose of the function : return tax amount for receipt/sales based on entered tax array
	* @todo Notes, if any
	*/
	function getTaxValue($initial_value,$_POST)
	{
		global $db;
		
		$tax_value = 0;
		$total_tax_amount = 0;
		$initial_tax_value = $initial_value;

		foreach($_POST['tax_id'] as $key => $value)
		{
			$tax_type_array=$this->getTax($_POST['tax_id'][$key]);
			$tax_type_id = $tax_type_array['tax_type_id'];

			switch ($tax_type_id)
			{
				case 1:
					$tax_value=$_POST['tax_rate'][$key];
					$total_tax_amount+=$tax_value;
					$initial_tax_value+=$tax_value;
					break;
				case 2:
					$tax_value=$initial_tax_value*($_POST['tax_rate'][$key]/100);
					$total_tax_amount+=$tax_value;
					$initial_tax_value+=$tax_value;
					break;
				case 3:
					$tax_value=$_POST['tax_rate'][$key];
					$total_tax_amount-=$tax_value;
					$initial_tax_value-=$tax_value;
					break;
				case 4:
					$tax_value=$initial_tax_value*($_POST['tax_rate'][$key]/100);			
					$total_tax_amount-=$tax_value;
					$initial_tax_value-=$tax_value;
					break;		
			}
		}
		return $total_tax_amount;	
	}
	/*
	* purpose of the function : insert array of values into a table
	* @todo Notes, if any
	*/
	function insertValues($table_name, $values, $sequence_name = NULL)
	{
		global $db;
		
		foreach ($values as $column_name => $column_value)
		{
			$columns_array[] = $column_name;
			$values_array[] = ($column_value == "" && $column_value != "0") ? "NULL" : "'". trim($column_value) ."'";
		}

		$columns = implode (',', $columns_array);
		$values = implode (',', $values_array);
		$query = "INSERT INTO ".$table_name." (".$columns.") VALUES (".$values.")";
		$result = $db->query($query);
		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : insertValues, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			if($sequence_name)
			{
				$new_id = $db->lastInsertId($sequence_name);
			}
		}
		return $new_id;
	}
	/*
	* purpose of the function : update values in a table
	* @todo Notes, if any
	*/
	function updateValues($table_name, $values, $conditions=array() , $type='def')
        {
		global $db;

		foreach ($values as $column_name => $column_value)
		{
			$column_value = ($column_value == "" && $column_value != "0") ? "NULL" : "'". trim($column_value) ."'";

			if($type == 'def')
			{
				$values_array[$column_name] = " $column_name = $column_value";
			}
			elseif($type == 'rep')
			{
				$values_array[$column_name] = " $column_name = $column_name + $column_value";
			}
			
		}
		
		$values = implode (',', $values_array);
		
		if ($conditions)
		{
			foreach($conditions as $column_name => $column_value)
			{
				$column_value = ($column_value == "" && $column_value != "0") ? "NULL" : "'". trim($column_value) ."'";
				$conditions_array[$column_name] = " $column_name = $column_value";
			}
			$conditions = "WHERE ".implode(' AND ',$conditions_array);
		}
		$query = "UPDATE $table_name SET ".$values." $conditions";
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : updateValues, Class : common, '.$err_info[2]);
			exit;
		}
        }
	/*
	* purpose of the function : return relationships js array
	* @todo Notes, if any
	*/
	function setRelationshipJsArray($activity)
	{
		global $db;
		$arr = $this->getRelationships(array($activity));
		$js_string = '<SCRIPT TYPE="text/javascript">';
		$js_string .= "var ".$activity."_array = new Array();";
		if ($arr)
		{
			$js_string .= $activity. "_array = [";
			
			foreach($arr as $key => $value)
			{
				$values_array[] = "'".addslashes($value['relationship_name'])."'";
			}
			$js_string .= implode(',',$values_array);
			$js_string .= ']';
		}
		$js_string .= '</SCRIPT>';
		return $js_string;
	}
	/*
	* purpose of the function : get city details
	* @todo Notes, if any
	*/
	function getProduct($product_id = NULL,$fetchColumn='*')
	{
		global $db;

		$product_id_condition =($product_id)?' WHERE product_id = '.$product_id:NULL;
		
		$query = 'SELECT '.$fetchColumn.'
		FROM '.products_table.' pr 
		JOIN '.product_category_table.' pc USING(product_category_id) 
		LEFT JOIN '.currency_table.' cpc ON cpc.currency_id = pr.cp_currency_id 
		LEFT JOIN '.currency_table.' spc ON spc.currency_id = pr.sp_currency_id'.$product_id_condition.'
		ORDER BY pr.product_desc,pc.product_category_desc';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getProduct, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : check whether product desc is exist or not
	* @todo Notes, if any
	*/
	function isProductExist($product_desc)
	{
		global $db;
   		$query = "SELECT product_id FROM ".products_table." WHERE product_desc ILIKE '".trim($product_desc)."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isProductExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$product_id = $arr['product_id'];
		}
		return $product_id;
	}
	/*
	* purpose of the function : check whether product desc is exist or if not insert a return a new product_id
	* @todo Notes, if any
	*/
	function getProductId($product_desc)
	{
		global $db;

		$product_id = $this->isProductExist($product_desc);

		if(!$product_id)
		{
			$product_array['product_desc'] = $product_desc;
			$product_id = $this->insertValues(products_table, $product_array, products_product_id_seq);
			unset($product_array);			
		}
		
		return $product_id;
	}
	/*
	* purpose of the function : return products js array
	* @todo Notes, if any
	*/
	function setProductJsArray()
	{
		global $db;
		$arr = $this->getProduct();
		$js_string = '<SCRIPT TYPE="text/javascript">';
		$js_string .= "var product_array = new Array();";
		if ($arr)
		{
			$js_string .= "product_array = [";
			
			foreach($arr as $key => $value)
			{
				$values_array[] = "'".addslashes($value['product_desc'])."'";
			}
			$js_string .= implode(',',$values_array);
			$js_string .= ']';
		}
		$js_string .= '</SCRIPT>';
		return $js_string;
	}
	/*
	* purpose of the function : return db formatted date
	* @todo Notes, if any
	*/
	function getDBFormatDate($date)
	{
		list ($day, $month, $year) = explode ('/', $date);
		return $year.'-'.$month.'-'.$day;
	}

	function getContacts($activity, $relationship_id = NULL, $fetchColumn = '*')
	{
		global $db;
		
		$relationship_id_condition =($relationship_id)?' AND relationship_id = '.$relationship_id:NULL;

		$query = 'SELECT '.$fetchColumn.'
		FROM '.relationships_table.' re
		LEFT JOIN '.contacts_table.' co USING(relationship_id)
		WHERE activity = \''.$activity.'\''.$relationship_id_condition."
		ORDER BY re.relationship_name";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getContacts, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : get payment mode details
	* @todo Notes, if any
	*/
	function getPaymentMode($payment_mode_id = NULL)
	{
		global $db;

		if($payment_mode_id)
		{
			$payment_mode_id_condition = ' WHERE payment_mode_id = '.$payment_mode_id;
		}

		$query = 'SELECT * FROM '.payment_mode_table.$payment_mode_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getPaymentMode, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : get uom details
	* @todo Notes, if any
	*/
	function getUOM($uom_id = NULL)
	{
		global $db;
		$uom_id_condition = '';
		if($uom_id)
		{
			$uom_id_condition = ' WHERE uom_id = '.$uom_id;
		}
		$query = 'SELECT * FROM '.uom_table.$uom_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getUOM, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : get city details
	* @todo Notes, if any
	*/
	function getProductCategory($product_category_id = NULL)
	{
		global $db;

		$product_category_id_condition =($product_category_id)?' WHERE product_category_id = '.$product_category_id:NULL;

		$query = 'SELECT * FROM '.product_category_table.$product_category_id_condition;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getProductCategory, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : check whether product category desc is exist or not
	* @todo Notes, if any
	*/
	function isProductCategoryExist($product_category_desc)
	{
		global $db;
   		$query = "SELECT product_category_id FROM ".product_category_table." WHERE product_category_desc ILIKE '".trim($product_category_desc)."'";
		$result =  $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : isProductCategoryExist, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetch(PDO::FETCH_ASSOC);
			$product_category_id = $arr['product_category_id'];
		}
		return $product_category_id;
	}
	/*
	* purpose of the function : check whether product category desc is exist or if not insert a return a new product_category_id
	* @todo Notes, if any
	*/
	function getProductCategoryId($product_category_desc)
	{
		global $db;

		$product_category_id = $this->isProductCategoryExist($product_category_desc);

		if(!$product_category_id)
		{
			$product_category_array['product_category_desc'] = $product_category_desc;
			$product_category_id = $this->insertValues(product_category_table, $product_category_array, product_category_product_category_id_seq);
			unset($product_category_array);			
		}
		
		return $product_category_id;
	}
	/*
	* purpose of the function : get param details
	* @todo Notes, if any
	*/
	function getParamArray($param)
	{
		global $db;

		$query = 'SELECT * FROM '.$param;
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getParamArray, Class : common, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : return param js array
	* @todo Notes, if any
	*/
	function setParamJsArray($param)
	{
		global $db;
		$arr = $this->getParamArray($param);
		$js_string = '<SCRIPT TYPE="text/javascript">';
		$js_string .= "var ".$param."_array = new Array();";
		if ($arr)
		{
			$js_string .= $param."_array = [";
			
			foreach($arr as $key => $value)
			{
				$values_array[] = "'".addslashes($value[$param."_desc"])."'";
			}
			$js_string .= implode(',',$values_array);
			$js_string .= ']';
		}
		$js_string .= '</SCRIPT>';
		return $js_string;
	}
	/*
	* purpose of the function : return param js array
	* @todo Notes, if any
	*/
	function generateXML($arr, $file_name)
	{
		// Create empty scorecard xml file
		$doc = new DomDocument('1.0');
		$doc->encoding = 'UTF-8';
		$doc->formatOutput = true;
		// create root node
		$root = $doc->createElement('root');
		$doc->appendChild($root);

		if($arr)
		{
			foreach($arr['customization'] as $key => $values)
			{
				$j = $doc->createElement('customization');
				$root->appendChild($j);
				foreach($values as $node => $val)
				{
					$k = $doc->createElement($node);
					$cData = $doc->createCDATASection($val);
					$k->appendChild($cData);
					$j->appendChild($k);
				}
			}
			foreach($arr['columns'] as $key => $values)
			{
				$j = $doc->createElement('columns');
				$root->appendChild($j);
				foreach($values as $node => $val)
				{
					$k = $doc->createElement($node);
					$cData = $doc->createCDATASection($val);
					$k->appendChild($cData);
					$j->appendChild($k);
				}
			}
		}
		$doc->save(_xml_path_.$file_name.'.xml');
	}
	/*
	* purpose of the function : return param js array
	* @todo Notes, if any
	*/
	function isValidXML($xml_file)
	{
		if (file_exists($xml_file))
		{
			////////// file validation check - starts
			$xmlfile_content =  file_get_contents($xml_file);
			$parser = xml_parser_create ();
			if (!xml_parse ($parser, $xmlfile_content, true))
			{
				xml_parser_free ($parser);
				return false;
			}
			xml_parser_free ($parser);
			////////// file validation check - ends
			return true;
		}
		return false;
	}

	/**
	 * xml2array() will convert the given XML text to an array in the XML structure.
	 * Link: http://www.bin-co.com/php/scripts/xml2array/
	 * Arguments : $contents - The XML text
	 *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
	 * Return: The parsed XML in an array form.
	 */
	function xml2array($contents, $get_attributes=0) 
	{
		if(!$contents) return array();

		if(!function_exists('xml_parser_create')) {
			//print "'xml_parser_create()' function not found!";
			return array();
		}
		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create();
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_parse_into_struct( $parser, $contents, $xml_values );
		xml_parser_free( $parser );

		if(!$xml_values) return;//Hmm...

		//Initializations
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();

		$current = &$xml_array;

		//Go through the tags.
		foreach($xml_values as $data) {
			unset($attributes,$value);//Remove existing values, or there will be trouble

			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data);//We could use the array by itself, but this cooler.

			$result = '';
			if($get_attributes) {//The second argument of the function decides this.
				$result = array();
				if(isset($value)) $result['value'] = $value;

				//Set the attributes too.
				if(isset($attributes)) {
					foreach($attributes as $attr => $val) {
						if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
						/*  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
					}
				}
			} elseif(isset($value)) {
				$result = $value;
			}

			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level-1] = &$current;

				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					$current = &$current[$tag];

				} else { //There was another element with the same tag name
					if(isset($current[$tag][0])) {
						array_push($current[$tag], $result);
					} else {
						$current[$tag] = array($current[$tag],$result);
					}
					$last = count($current[$tag]) - 1;
					$current = &$current[$tag][$last];
				}

			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;

				} else { //If taken, put all things inside a list(array)
					if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
							or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
						array_push($current[$tag],$result); // ...push the new element into that array.
					} else { //If it is not an array...
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
					}
				}

			} elseif($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level-1];
			}
		}

		return($xml_array);
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