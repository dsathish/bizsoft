<?php
include ('class.ezpdf.php');
$pdf =& new Cezpdf();
$pdf->selectFont('./fonts/Helvetica.afm');
$pdf->ezText('Hello World!',50);
$x=array(array("0"=>"Subbu","1"=>"Raja"),array("0"=>"Subbu","1"=>"Raja"),array("0"=>"Subbu","1"=>"Raja"),array("0"=>"Subbu","1"=>"Raja"));
$pdf->ezTable($x);
$pdf->ezStream();
?>