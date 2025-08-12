<?php

$pdf = new FPDF("P", "mm", "A5");
$pdf->AddPage();


// EN-TETE
$pdf->SetFont("Arial", "B", 10);
// $pdf->Cell(40, 8, "SAMKOCWA", 'B', 0, 'L');
// $pdf->SetFont("", "B", 10);
$pdf->Cell(39, 8, "Liste des Produits le :", 'B', 0, 'L');
$pdf->SetFont("", "UB", 10);
$pdf->Cell(90, 8, date("d-m-Y"), 'B', 1, 'L');
$pdf->Ln(5);


//TABLEAU
$pdf->SetFont("Arial", "B", 9);
$pdf->Cell(43, 6, utf8_decode('Désignation'), 1, 0, 'C');
$pdf->Cell(43, 6, utf8_decode('Déscription'), 1, 0, 'C');
$pdf->Cell(43, 6, "Prix", 1, 1, 'C');
// $pdf->Cell(33, 6, "Image", 1, 1, 'C');

$pdf->SetFont("", "", 7.5);
$im = 30;
for ($i = 0; $i < count($data); $i++) {
    $pdf->Cell(43, 8, utf8_decode($data[$i]->refProduit), 1, 0, 'C');
    $pdf->Cell(43, 8, utf8_decode($data[$i]->designation), 1, 0, 'C');
    $pdf->Cell(43, 8, number_three($data[$i]->prix), 1, 1, 'C');
    // $pdf->Image(base_url('public/') . $data[$i]->photo, 115, $im, 15, 10);
    // $pdf->Cell(33, 12, "", 1, 1, 'C');

    // $im = $im + 12;
}

$pdf->Output();
