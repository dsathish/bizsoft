<?php
/**
 * Purpose of the file : report functions
 * @package Project Name or Module Name
 * @author Initials | Date
 * @todo Notes, If any
*/

class report
{
	public $date_constraint = NULL;
	public $report_details = array();
	public $final_array = array();
	public $grand_total_array = array();
	public $customization_details = array();

	/*
	* purpose of the function : constructor function
	* @todo Notes, if any
	*/
	function __construct ($report_id=NULL,$customization_id=NULL)
	{
		global $db;
		
		$query = '';
		if($report_id && !$customization_id)
		{
			$query='SELECT re.report_id,re.report_code,re.report_name ,re.view_name ,ct.customization_id ,ct.customization_name, ct.sub_total, ct.grand_total, ct.is_default, ct.date_column ,ct.from_date AS formatted_from_date ,ct.to_date AS formatted_to_date,to_char(from_date,\'dd/mm/yyyy\') AS from_date ,to_char(to_date,\'dd/mm/yyyy\') AS to_date  FROM '.reports_table.' re JOIN '.customizations_table.' ct USING(report_id) WHERE re.report_id = '.$report_id.' AND ct.is_default = 1 ';
		}
		elseif($customization_id)
		{
			$query='SELECT re.report_id,re.report_code,re.report_name ,re.view_name ,ct.customization_id ,ct.customization_name, ct.sub_total, ct.grand_total, ct.is_default, ct.date_column ,ct.from_date AS formatted_from_date ,ct.to_date AS formatted_to_date,to_char(from_date,\'dd/mm/yyyy\') AS from_date ,to_char(to_date,\'dd/mm/yyyy\') AS to_date FROM '.reports_table.' re JOIN '.customizations_table.' ct USING(report_id) WHERE ct.customization_id = '.$customization_id;
		}
		
		if($query)
		{
			$result = $db->query($query);
		
			if(!$result)
			{
				$err_info = $db->errorInfo();
				echo $err_info[2];
				error_log('Function : reportConst, Class : report, '.$err_info[2]);
				exit;
			}
			else
			{
				$this->report_details = $result->fetchAll(PDO::FETCH_ASSOC);
				$this->customColumnDetail();
				
				unset($result);

				$query='SELECT customization_id FROM '.customizations_table.' WHERE report_id = '.$this->report_details[0]['report_id'].' AND is_default = 2';
				$result = $db->query($query);
				$def_arr = $result->fetch(PDO::FETCH_ASSOC);
				$this->report_details[0]['default_id'] = $def_arr['customization_id'];
			}
		}
	}

	/*
	* purpose of the function : return customization details for given report id
	* @todo Notes, if any
	*/
	function getReportCust()
	{
		global $db;
		
		$query = 'SELECT * FROM '.customizations_table.' WHERE report_id = '. $this->report_details[0]['report_id'].' AND is_default != 2 AND is_active = 1
		ORDER BY customization_name';
		$result = $db->query($query);

		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getReportCust, Class : report, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return $arr;
	}
	/*
	* purpose of the function : return a customized array for given report customization
	* @todo Notes, if any
	*/
	function customColumnDetail()
	{
		global $db, $common_obj;

		$query = 'SELECT co.column_id, co.column_name ,co.data_type, cc.display_name , cc.display_order, cc.sort_order , cc.is_group , cc.is_filter , cc.default_value , cc.style , cc.decimal_places, cc.date_format, cc.display_total
		FROM '.customization_columns_table.' cc 
		JOIN '.columns_table .' co ON co.column_id=cc.column_id 
		WHERE cc.customization_id = '.$this->report_details[0]['customization_id'].'
		ORDER BY display_order NULLS LAST';
		$result = $db->query($query);
		
		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : customColumnDetail, Class : report, '.$err_info[2]);
			exit;
		}
		else
		{
			$arr = $result->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($arr AS $key=>$values)
			{
				if($values['display_order'])
				{
					if($values['data_type'] == 'date' && $values['date_format'])
					{
						$this->customization_details['fetch_column'][] = "to_char(".$values['column_name'].",'".$values['date_format']."') AS \"".$values['display_name']."\"";
					}
					else
					{
						$this->customization_details['fetch_column'][] = $values['column_name']." AS \"".$values['display_name']."\"";
					}
				}
				if($values['is_group'])
				{
					$this->customization_details['group_columns'][] = $values['display_name'];
				}
				if($values['is_filter'])
				{
					$this->customization_details['default_value'][$values['column_name']] = $values['default_value'];
				}
				if($values['sort_order'])
				{
					$this->customization_details['sort_columns'][] = $values['column_name']."::".$values['data_type'].' '.$values['sort_order'];
				}
				//if($values['decimal_places'])
				//{
					$this->customization_details['decimal_places'][$values['display_name']] = $values['decimal_places'];
				//}
				if($values['display_total'])
				{
					$this->customization_details['total_columns'][] = $values['display_name'];
				}
				if($values['style'])
				{
					$this->customization_details['columns_style'][$values['display_name']] = $values['style'];
				}
			}
		}
	}

	/*
	* purpose of the function : to get customization details
	* @todo Notes, if any
	*/
	function getCustomDetail()
	{
		global $db;

		$query = 'SELECT co.column_id, co.column_name ,co.data_type, cc.display_name , cc.display_order, cc.sort_order , cc.is_group , cc.is_filter , cc.default_value , cc.style , cc.decimal_places, cc.date_format, cc.display_total, cc1.display_name AS default_display_name, cc1.is_group AS default_is_group, cc1.display_total AS default_display_total, cc1.is_filter AS default_is_filter
		FROM '.customization_columns_table.' cc
		JOIN '.customization_columns_table.' cc1 ON cc1.column_id = cc.column_id AND cc1.customization_id ='.$this->report_details[0]['default_id'].'
		JOIN '.columns_table .' co ON co.column_id=cc.column_id 
		WHERE cc.customization_id = '.$this->report_details[0]['customization_id'].'
		ORDER BY cc.display_order NULLS LAST';
		$result = $db->query($query);
		$arr = $result->fetchAll(PDO::FETCH_ASSOC);
		
		return $arr;
	}
	/*
	* purpose of the function : return a customized report data
	* @todo Notes, if any
	*/
	function reportCustomData()
	{
		global $db,$condition_arr;

		if($this->customization_details['fetch_column'] && $this->report_details[0]['view_name'])
		{
			if($this->customization_details['default_value'])
			{
				foreach($this->customization_details['default_value'] as $column_name => $default_value)
				{
					$default_value = str_replace('~#~','\',\'',$default_value);
					if($default_value)
					{
						$column_name = str_replace('\'\'','',strip_tags($column_name));
						$column_name = str_replace('||','',$column_name);
	
						$condition_arr[] = trim($column_name).' IN (\''.$default_value.'\')';
					}
				}
			}

			//push into fetch query for date constraint id exsists for given report customization
			if($this->date_constraint)
			{
				$condition_arr[] = $this->date_constraint;
			}
			
			if($condition_arr)
			{
				$condition_str = ' WHERE '.implode(' AND ', $condition_arr);
			}				

			if($this->customization_details['sort_columns'])
			{
				$orderby_columns = ' ORDER BY '.implode(',',$this->customization_details['sort_columns']);
			}
			$query = 'SELECT '.implode(',',$this->customization_details['fetch_column']) .' 
			FROM ' .$this->report_details[0]['view_name'] . $condition_str . $orderby_columns;
			$result = $db->query($query);
		
			if(!$result)
			{
				$err_info = $db->errorInfo();
				echo $err_info[2];
				error_log('Function : reportCustomData, Class : report, '.$err_info[2]);
				exit;
			}
			else
			{
				$arr = $result->fetchAll(PDO::FETCH_ASSOC);
			}
		}
		return $arr;
	}

	/*
	* purpose of the function : get report customization filters
	* @todo Notes, if any
	*/
	function getFilters()
	{
		global $db;

		$query = "SELECT c.column_id, cc.display_name, c.filter_query, cc.default_value
		FROM ".customization_columns_table." cc
		JOIN ".columns_table." c USING (column_id)
		WHERE cc.customization_id = ".$this->report_details[0]['customization_id']." AND cc.is_filter = 1
		ORDER BY cc.display_name";
		$result = $db->query($query);
		
		if(!$result)
		{
			$err_info = $db->errorInfo();
			echo $err_info[2];
			error_log('Function : getFilters, Class : report, '.$err_info[2]);
			exit;
		}
		else
		{
			$i = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['column_id'] = $row['column_id'];
				$arr[$i]['display_name'] = $row['display_name'];
 				$flag = TRUE;
 				if($row['filter_query'])
 				{
 					$query = $row['filter_query'];
 					$result1 = $db->query($query);
 					$filter_values_arr = $result1->fetchAll(PDO::FETCH_ASSOC);
					unset($result1);
 					$default_value_arr = explode('~#~',$row['default_value']);
 
 					if($filter_values_arr)
					{
						$j = 0;
						foreach($filter_values_arr as $key => $values)
						{
							$new_filter_values_arr[$j]['value'] = $values['value'];
							if(in_array($values['value'], $default_value_arr))
							{
								$new_filter_values_arr[$j]['selected'] = 'selected';
								$flag = FALSE;
							}
							$j++;
						}
					}
					$arr[$i]['filter_values'] = $new_filter_values_arr;
					unset($filter_values_arr, $new_filter_values_arr, $default_value_arr);
 				}
 				$arr[$i]['all'] = $flag ? 'selected' : '';

				$i++;
			}
		}

		return $arr;
	}

	/*
	* purpose of the function : delete report customization
	* @todo Notes, if any
	*/
	function deleteCustomization()
	{
		global $common_obj;

		if($this->report_details[0]['is_default'])
		{
			$common_obj->updateValues(customizations_table, array('is_default'=>'1'), array('report_id'=>$this->report_details[0]['report_id'],'customization_name'=>'Default'));
		}

		$common_obj->updateValues(customizations_table, array('is_active'=>'0'), array('customization_id'=>$this->report_details[0]['customization_id']));
	}
	
	/*
	* purpose of the function : return single dimensional grouped array
	* @todo Notes, if any
	*/
	function getGroupedArray($arr, $group_column)
	{
                foreach($arr as $key => $values)
		{
			$group_arr[$values[$group_column]][]=$arr[$key];
		}
                return $group_arr;
	}
	/*
	* purpose of the function : return array after grouping for given columns
	* @todo Notes, if any
	*/
	function getGroupedData($data_array, $group_columns=array(), $grouping=array())
	{
		if(count($group_columns))
		{
			$group_column = current($group_columns);
			$grouped_array = $this->getGroupedArray($data_array, $group_column);
			array_shift($group_columns);
			foreach($grouped_array as $key => $values)
			{
				$grouping[$group_column] = $key;
				$this->getGroupedData($values, $group_columns, $grouping);
			}
		}
		else
		{
			foreach($data_array as $dk => $dv)
			{
				if($grouping)
				{
					foreach($grouping as $gk => $gv)
					{
						$data_array[$dk][$gk]=($dk==0)?$data_array[$dk][$gk]:"";
					}
				}
			}
			$total_array = $this->getColumnSum($data_array, $this->customization_details['total_columns']);
			if($this->report_details[0]['sub_total'])
			{
				$this->appendTotal($data_array, $total_array);
			}
			if($this->report_details[0]['grand_total'])
			{
				$this->grand_total_array[] = array_merge($this->grand_total_array, $total_array);
			}
			$this->final_array = array_merge($this->final_array, $data_array);
		}
	}
	/*
	* purpose of the function : return total array for given columns names
	* @todo Notes, if any
	*/
	function getColumnSum($arr, $columns)
	{
		$total_array = array();
		if($columns)
		{
			foreach($columns as $column_name)
			{
				for($i=0,$c=count($arr); $i<$c; $i++)
				{
					$total_array[$column_name] += $arr[$i][$column_name];
				}
			}
		}
		return $total_array;
	}
	/*
	* purpose of the function : add a given total array to existing array
	* @todo Notes, if any
	*/
	function appendTotal(&$arr, $total_array, $total_str='Sub Total')
	{
		$cnt = count($arr);
		$first_column = true;
		foreach($arr[0] as $key => $value)
		{
			if($first_column == true)
			{
				$arr[$cnt][$key] = $total_str;
				$first_column = false;
			}
			else
			{
				$arr[$cnt][$key] = $total_array[$key];
			}
		}
	}
	/*
	* purpose of the function : return a number formatted array for given columns
	* @todo Notes, if any
	*/
	function formatArray($arr, $columns)
	{
		foreach($columns as $column_name => $decimal_places)
		{
			for($i=0,$c=count($arr); $i<$c; $i++)
			{
				if(is_numeric($arr[$i][$column_name]))
				{
					$arr[$i][$column_name] = money_format('%!.'.$decimal_places.'i',$arr[$i][$column_name]);
				}
			}
		}
		return $arr;
	}
	/*
	* purpose of the function : unset a column in given single dim associative array
	* @todo Notes, if any
	*/
	function unsetColumn(&$arr , $unset_columns)
	{
		if($unset_columns)
		{
			for($i=0,$c=count($arr); $i<$c; $i++)
			{
				foreach($unset_columns as $column_name)
				{
					unset($arr[$i][$column_name]);
				}
			}
		}	
	}
	/*
	* purpose of the function : set cummulative column value
	* @todo Notes, if any
	*/
	function setColumnCummulative(&$arr ,$cummulative_column)
	{
		if($cummulative_column)
		{
			foreach($cummulative_column as $column_name=>$formula)
			{
				for($i=0,$c=count($arr); $i<$c; $i++)
				{
					$arr[$i][$column_name]=$arr[$i-1][$column_name]+($arr[$i][$formula[0]]-$arr[$i][$formula[1]]);
				}
			}
		}
	}
	/*
	* purpose of the function : set customization date condition value
	* @todo Notes, if any
	*/
	function setDateConstraint($date_column,$from_date=NULL,$to_date=NULL)
	{
		if($from_date && $to_date)
		{
			$this->date_constraint = '  '.$date_column.' BETWEEN \''.$from_date.'\' AND \''.$to_date.'\' '; 
		}
		elseif($from_date)
		{
			$this->date_constraint = '  '.$date_column.' >= \''.$from_date.'\'  ';
		}
		elseif($to_date)
		{
			$this->date_constraint = '  '.$date_column.' <= \''.$to_date.'\'  ';		
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