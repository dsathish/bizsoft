<?php
 require('setup.php');

$common_obj->isLoggedIn();

$customization_id = $_REQUEST['customization_id'];
$report_id = $_REQUEST['report_id'];
$smarty->assign("customization_id", $customization_id);
$smarty->assign("report_id", $report_id);

$orientation_array = array("portrait"=>"Portrait", "landscape"=>"Landscape");
$smarty->assign("orientation_array", $orientation_array);
$orientation_default = $_REQUEST["orientation"]?$_REQUEST["orientation"]:"portrait";
$smarty->assign("orientation_default", $orientation_default);

$paper_size_array = array("EXECUTIVE"=>"EXECUTIVE","FOLIO"=>"FOLIO","LETTER"=>"LETTER","LEGAL"=>"LEGAL","A1"=>"A1","A2"=>"A2","A3"=>"A3","A4"=>"A4","B1"=>"B1","B2"=>"B2","B3"=>"B3","B4"=>"B4","C1"=>"C1","C2"=>"C2","C3"=>"C3","C4"=>"C4");
$smarty->assign("paper_size_array", $paper_size_array);
$paper_default = $_REQUEST["paper_size"]?$_REQUEST["paper_size"]:"A4";
$smarty->assign("paper_default", $paper_default);

$font_array = array("Courier.afm"=>"Courier","Courier-Bold.afm"=>"Courier-Bold","Courier-BoldOblique.afm"=>"Courier-BoldOblique","Courier-Oblique.afm"=>"Courier-Oblique","Helvetica.afm"=>"Helvetica","Helvetica-Bold.afm"=>"Helvetica-Bold","Helvetica-BoldOblique.afm"=>"Helvetica-BoldOblique","Helvetica-Oblique.afm"=>"Helvetica-Oblique","Times-Bold.afm"=>"Times-Bold","Times-BoldItalic.afm"=>"Times-BoldItalic","Times-Italic.afm"=>"Times-Italic","Times-Roman.afm"=>"Times-Roman");
$smarty->assign("font_array", $font_array);
$font_default = $_REQUEST["font_name"]?$_REQUEST["font_name"]:"Helvetica.afm";
$smarty->assign("font_default", $font_default);	

$font_size_array = array("8"=>"8 px","9"=>"9 px","10"=>"10 px","11"=>"11 px","12"=>"12 px","13"=>"13 px","14"=>"14 px","15"=>"15 px","16"=>"16 px","17"=>"17 px","18"=>"18 px");
$smarty->assign("font_size_array", $font_size_array);
$title_font_size_default = $_REQUEST["title_font_size"]?$_REQUEST["title_font_size"]:"14";
$smarty->assign("title_font_size_default", $title_font_size_default);

$font_size_default = $_REQUEST["font_size"]?$_REQUEST["font_size"]:"12";
$smarty->assign("font_size_default", $font_size_default);

if($_POST)
{
	$report_title = $_SESSION["report_name"][$report_id][$customization_id];
	$report_title = strip_tags(str_replace('<BR>',"\n",$report_title));

	foreach($_SESSION["report_array"][$report_id][$customization_id] as $key => $values)
	{
		foreach($values as $column_name => $data)
		{
			$report_details[$key][$column_name]=strip_tags(html_entity_decode($data));
		}
	}
	$options=array();
	if(!$_SESSION["justification"][$report_id][$customization_id])
	{
		$_SESSION["justification"][$report_id][$customization_id] = array();
	}

	foreach($_SESSION["justification"][$report_id][$customization_id] as $key => $value)
	{
		$options['cols'][$key]['justification'] = str_replace(";","",str_replace("text-align:","",$value));
	}

	include ('pdf_class/class.ezpdf.php');
	$pdf =& new Cezpdf($_POST["paper_size"],$_POST["orientation"]);
	$pdf->selectFont("../lib/pdf_class/fonts/$_POST[font_name]");
	//$pdf->ezText("<b>".strip_tags(str_replace('<BR>',"\n",$report_title))."</b>\n",'',array('justification'=>'center'));
	$pdf->ezTable($report_details,'',$report_title,$options,$_POST["font_size"],$_POST["title_font_size"]);
	//$pdf->ezTable($_SESSION["report"]["grand_total"],'','Grand Total');
	$pdf->ezStream();
}

$smarty->clear_cache('pdf.tpl');
$smarty->display('pdf.tpl');

?>
