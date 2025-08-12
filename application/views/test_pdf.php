<?php


// create the number to words "manager" class

// build a new number transformer using the RFC 3066 language identifier


$pdf = new FPDF("P", "mm", "A5");
$pdf->AddPage();
// $pdf->SetAutoPageBreak(false);

$pdf->SetFont("Arial", "B", 15);
$pdf->Cell(62, 9, "TB HOBY", 0, 0, 'L');


$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(22, 9, utf8_decode("Facture N° : "), 0, 0, 'L');
$pdf->Cell(46, 9, $facture['facture']->Facture, 0, 1, 'L');

$pdf->Cell(62, 9, "", 0, 0, 'L');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(10, 7, "Date : ", 0, 0, 'L');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(48, 7, format_date($facture['facture']->dateVente), 0, 1, 'L');


$pdf->Cell(62, 7, "", 0, 0, 'L');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(10, 7, "Doit : ", 0, 0, 'L');
$pdf->SetFont("Arial", "", 8);

if (is_null($facture['facture']->clientDirect)  || $facture['facture']->clientDirect == "") {
    $pdf->MultiCell(57, 7, $facture['facture']->nomClient . " " . $facture['facture']->prenomClient, 0, 'L');
} else {
    $pdf->MultiCell(57, 7, $facture['facture']->clientDirect, 0, 'L');
}




// TABLEAU
$pdf->Ln(5);
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(58, 9, utf8_decode("Désignation"), 'T,L', 0, 'L');
$pdf->Cell(19, 9, utf8_decode("Quantité"), 'T,L', 0, 'L');
$pdf->Cell(21, 9, "Prix Unitaire", 'T,L', 0, 'L');
$pdf->Cell(30, 9, "Montant", 'T,L,R', 1, 'L');

$pdf->SetFont("Arial", "", 7);
foreach ($facture['panniers'] as $key => $pannier) {
    if ($key % 20 || $key == (count($facture['panniers']) - 1)) {
        $pdf->Cell(58, 7, utf8_decode($pannier->designation), 'T,L,B', 0, 'L');
        $pdf->Cell(19, 7, utf8_decode($pannier->qteProduit), 'T,L,B', 0, 'L');
        $pdf->Cell(21, 7, format_number($pannier->prixProduit), 'T,L,B', 0, 'L');
        $pdf->Cell(30, 7, format_number($pannier->montantTotal), 'T,L,R,B', 1, 'L');
    } else {
        $pdf->Cell(58, 7, utf8_decode($pannier->designation), 'T,L', 0, 'L');
        $pdf->Cell(19, 7, utf8_decode($pannier->qteProduit), 'T,L', 0, 'L');
        $pdf->Cell(21, 7, format_number($pannier->prixProduit), 'T,L', 0, 'L');
        $pdf->Cell(30, 7, format_number($pannier->montantTotal), 'T,L,R', 1, 'L');
    }
}


$total = 0;
foreach ($facture['panniers'] as $key => $pannier) {
    $total = $total + $pannier->montantTotal;
}


$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(77, 9, "", 0, 0, 'C');
$pdf->Cell(21, 8, "Total", 'L,B', 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(30, 8, format_number($total), 'L,B,R', 1, 'L');


$remise = ($facture['facture']->remise * $total) / 100;

$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(77, 9, "", 0, 0, 'C');
$pdf->Cell(21, 8, "Remise", 'L,B', 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(30, 8, format_number($remise), 'L,B,R', 1, 'L');


$total = ((100 - $facture['facture']->remise) * $total) / 100;
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(77, 9, "", 0, 0, 'C');
$pdf->Cell(21, 8, utf8_decode("Montant à payer"), 'L,B', 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(30, 8, format_number($total), 'L,B,R', 1, 'L');

$pdf->Ln(5);
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(30, 5, utf8_decode("Arrêté à la somme de :"), 0, 0, 'L');
$pdf->MultiCell(100, 5, utf8_decode(ucfirst(Utility::number_to_letter(number_format($total, 2, '.', '')))), 0, 1);


$pdf->Ln(2);
$pdf->Cell(30, 5, utf8_decode("Mode de paiement :"), 0, 0, 'L');
if (!is_null($facture['facture']->idMode)) {
    $pdf->Cell(100, 5, utf8_decode($facture['facture']->nomMode), 0, 1);
}
else
{
    $pdf->Cell(100, 5, utf8_decode('Espèce'), 0, 1);

}

// signature
$pdf->Ln(10);
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(72, 9, utf8_decode("Client"), 0, 0, 'L');
$pdf->Cell(65, 9, utf8_decode("Signature"), 0, 0, 'L');


$pdf->Output("D", 'facture' . $facture['facture']->Facture . '.pdf');
