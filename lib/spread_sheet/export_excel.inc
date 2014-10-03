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

class exportexcel
{

var $file_name="";
var $worksheet_name="";
var $table_details_array="";

///Param 1:associative array (values to be exported to excel)
///Param 2:String (Name of the file to be Exported)
///Param 3:String (Name of the workbook to be created on the given file name)

function exportDataToExcel($table_details,$file_name,$worksheet_name)

	{
	// Creating a workbook
	$workbook = new Spreadsheet_Excel_Writer();
	
	// sending HTTP headers
	$workbook->send("$file_name".'xls');
	
	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet("'$worksheet_name'");
	
	
		if ($table_details)
			{
				$colcount=0; //for setting the footer colspan
				foreach($table_details[0] as $key=>$value)
					{
					$worksheet->write(0,$colcount ,$key );
					$colcount++;
					}
			}
			$i=0;
			$cnt=count($table_details);
			
			for($j=0; $j<$cnt; $j++)
				{
	
				$cell=0;
				foreach($table_details[$j] as $key=>$value)
					{
	
					$worksheet->write($j+1,$cell ,$value );
					$cell++;
					}
	
				}
	$workbook->close();
	}
}
?>