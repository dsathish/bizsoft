<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
// Created by G.M.Sundar										    //
//                                                                                             //
// on 24/10/2006											    //
//													    //	
//Purpose : Used to Export an array to excel(Spread sheet)Format
//                                                                                             //
/////////////////////////////////////////////////////////////////////////////////////////////////


require_once 'Writer.php';

class cSpreadsheet
{

var $file_name="";
var $worksheet_name="";
var $table_details_array="";
var $need_lines=TRUE;
var $word_title_wrap=TRUE;
var $word_data_wrap=FALSE;
///Param 1:associative array (values to be exported to excel)
///Param 2:String (Name of the file to be Exported)
///Param 3:String (Name of the workbook to be created on the given file name)

function createSpreadsheet($table_details,$file_name,$worksheet_name,$title="")

	{

	// Creating a workbook
	$workbook = new Spreadsheet_Excel_Writer();
	
	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet();
	$worksheet->protect("llink123");
	$worksheet->centerHorizontally(1);
	$worksheet->centerVertically(1);

	//Creating Styles
	//Title
	$format_title=& $workbook->addFormat();
	$format_title->setBold();
	$format_title->setAlign('merge');
	$format_title->setTextWrap();
	if($this->need_lines)
		$format_title->setBorder(1);

	//Column Header
	$format_column_header=& $workbook->addFormat();
	$format_column_header->setBold();
	$format_column_header->setFgColor(47);
	if($word_title_wrap)
		$format_column_header->setTextWrap();
	if($this->need_lines)
		$format_column_header->setBorder(1);
	//Alternate Rows



	//Normal Text
	$format_normal=& $workbook->addFormat();
	$format_normal->setBold(0);
	if($this->need_lines)
		$format_normal->setBorder(1);

	//Bold Text
	$format_bold=& $workbook->addFormat();
	$format_bold->setBold();
	if($this->need_lines)
		$format_bold->setBorder(1);

	$worksheet->freezePanes(array(5, 0));
	$worksheet->repeatRows(0,5);

	$worksheet->mergeCells(0,5,0,15);

	$title_array=explode('<BR>',strtoupper($title));
	
	$worksheet->write(0,5,strip_tags($title_array[0]),$format_title);
	$worksheet->write(0, 6, "", $format_title);
	$worksheet->write(0, 7, "", $format_title);
	$worksheet->write(0,8, "", $format_title);
	$worksheet->write(0, 9, "", $format_title);
	
	$worksheet->write(1,5,strip_tags($title_array[1]),$format_title);
	$worksheet->write(1, 6, "", $format_title);
	$worksheet->write(1, 7, "", $format_title);
	$worksheet->write(1,8, "", $format_title);
	$worksheet->write(1, 9, "", $format_title);

		if ($table_details)
			{
				$colcount=0; //for setting the footer colspan
				foreach($table_details[0] as $key=>$value)
					{
					$worksheet->write(3,$colcount ,$key,$format_column_header);
					$colcount++;
					}
			}
			$i=0;
			$cnt=count($table_details);
	
			for($j=0;$j<$cnt; $j++)
				{
	
				$cell=0;
				$keys=array_keys($table_details[$j]);
				$first_key=$keys[0];
				$setbold=FALSE;
				if(strip_tags($table_details[$j][$first_key])=='Total: ')
					{
					$setbold=TRUE;
					}
				

				foreach($table_details[$j] as $key=>$value)
					{
					if($setbold)
						$worksheet->write($j+5,$cell ,strip_tags($value),$format_bold);
					else
						$worksheet->write($j+5,$cell ,strip_tags($value),$format_normal);
					$cell++;
					}
					$setbold=FALSE;				
				}
// sending HTTP headers
	$workbook->send("$file_name".'.xls');
	$workbook->close();
	}
}
?>