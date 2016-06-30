<?php

include 'PDFMerger/PDFMerger.php';
echo $file="merge_".uniqid().".pdf";
$pdf = new PDFMerger;
foreach($_POST as $file){
  $pdf->addPDF($file, 'all'); 
}

$pdf->merge('file', 'uploads/pdf/'.$file);


?>