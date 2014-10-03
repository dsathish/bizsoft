<?
/***********************************************************************************************
Created by : G.M.Sundar 
[On : 02/28/2007, 04:48:00 PM]
 Description:

/***********************************************************************************************/


include("global_files/global_spreadsheet.inc");
$spsheet= new cSpreadsheet();

// print_r($_SESSION);
$spsheet->createSpreadsheet($_SESSION["cache"]["data"],$report_id,'test');



?>