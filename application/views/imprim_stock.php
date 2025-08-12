<?php

$pdf = new FPDF("P", "mm", "A4");
$pdf->AddPage();


// EN-TETE
$pdf->SetFont("Arial", "B", 10);
// $pdf->Cell(40, 8, "", 'B', 0, 'L');
// $pdf->SetFont("", "B", 10);
$pdf->Cell(33, 8, "Liste des Stock le :", 'B', 0, 'L');
$pdf->SetFont("", "UB", 10);
$pdf->Cell(154, 8, date("d-m-Y"), 'B', 1, 'L');
$pdf->Ln(5);


//TABLEAU
$pdf->SetFont("Arial", "B", 9);
$pdf->Cell(32, 6, utf8_decode('Désignation'), 1, 0, 'C');
$pdf->Cell(32, 6, utf8_decode('Déscription'), 1, 0, 'C');
$pdf->Cell(90, 6, utf8_decode('Quantité'), 1, 0, 'C');
$pdf->Cell(33, 6, "  Dépôt ou point de vente", 1, 1, 'C');

for ($i = 0; $i < count($data); $i++) {
    $pdf->SetFont("", "B", 7.5);
    $pdf->Cell(32, 6, utf8_decode($data[$i]->refProduit), 1, 0, 'L');
    $pdf->Cell(32, 6, utf8_decode($data[$i]->designation), 1, 0, 'L');
    $pdf->Cell(90, 6, utf8_decode($data[$i]->quantite_texte), 1, 0, 'L');
    $pdf->Cell(33, 6, utf8_decode($data[$i]->denomination_pv), 1, 1, 'L');

    $pdf->SetFont("", "", 7.5);
}

$pdf->Output();
