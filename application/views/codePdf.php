<?php

$pdf = new FPDF("P", "mm", "A5");
$pdf->AddPage();

$code = $this->session->userdata('code_data');
// var_dump(base_url('infoCode/'. $code));die;

// $pdf->EAN13(10, 10, '123456', 5, 0, 5, 9);

if ($code) {
    $pdf->Image(base_url('infoCode/') . $code, 10, 10, 50, 30, 'PNG');
}

$pdf->Output();
?>



function Header()
{
// Ajoutez la filigrane (image) ici
$this->SetAlpha(0.5); // Opacité de l'image
$this->Image(base_url('public/images/filigrane1.png'), 0, 0, $this->GetPageWidth(), $this->GetPageHeight(), '', '', '', true);
$this->SetAlpha(1); // Réinitialiser l'opacité à 1

// Continuez avec le contenu de l'en-tête
$this->SetFont("Arial", "B", 15);

// Code d'en-tête existant ici...

// Ajoutez la filigrane (image) ici
$this->SetAlpha(0.5); // Opacité de l'image
$this->Image(base_url('public/images/filigrane1.png'), 100, 0, $this->GetPageWidth(), $this->GetPageHeight(), '', '', '', true);
$this->SetAlpha(1); // Réinitialiser l'opacité à 1



$this->SetFont("Arial", "B", 15);

if (isset($this->admin[0]) && $this->admin[0]->logo != '' && getimagesize(base_url($this->admin[0]->logo))) {
$this->Image(base_url($this->admin[0]->logo), 10, 10, 25, 0, 'PNG');
$this->Cell(90, 20, "", '', 0, 'L');
} else {
$this->Cell(90, 20, utf8_decode(strtoupper($this->admin[0]->entreprise)), '', 0, 'L');
}



$this->SetFont("Arial", "", 15);
$this->Cell(12, 20, "", 0, 0, 'L');
$this->Cell(90, 10, utf8_decode("Facture N° : ") . $this->fact[0]->Facture, '', 1, 'L');

$this->Cell(102, 10, "", '', 0, 'L');

$this->SetFont("Arial", "", 10);
$this->Cell(90, 10, "Antananarivo le : " . form_date($this->fact[0]->dateVente), '', 1, 'L');

$this->SetFont("Arial", "", 10);
$this->SetFont("", "");
$this->SetFont("Arial", "", 9);
if ($this->admin[0]->adresse) {
$this->Cell(90, 6, utf8_decode($this->admin[0]->adresse), '', 0, 'L');
} else {
$this->Cell(90, 6, " Votre adresse ", '', 0, 'L');
}



$this->SetFont("Arial", "", 9);
$this->SetFont("", "U");
$this->Cell(12, 20, "", 0, 0, 'L');
if (isset($this->fact['r_social']) && $this->fact['r_social'] != '') {
$this->Cell(25, 6, "Raison social", 'L,T', 0, 'L');
$this->SetFont("", "");
$this->Cell(90, 6, " : " . utf8_decode($this->fact[0]->r_social), 'R,T', 1, 'L');
} else {
$this->Cell(90, 6, "", 'R,T;L', 1, 'L');
}






$this->SetFont("", "");

$this->Cell(15, 6, utf8_decode('Tél'), '', 0, 'L');
$this->SetFont("", "");
if ($this->admin[0]->tel) {
$this->Cell(75, 6, " : " . utf8_decode($this->admin[0]->tel), '', 0, 'L');
} else {
$this->Cell(75, 6, " : ", '', 0, 'L');
}



$this->Cell(12, 6, "", 0, 0, 'L');

$this->SetFont("", "U");
if (isset($this->fact[0]->nif) && $this->fact[0]->nif != '') {
$this->Cell(25, 6, "NIF", 'L', 0, 'L');
$this->SetFont("", "");
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->nif), 'R', 1, 'L');
} else {
$this->Cell(25, 6, "Nom", 'L', 0, 'L');
$this->SetFont("", "");
if (isset($this->fact[0]->nomClient)) {
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->nomClient), 'R', 1, 'L');
} else {
$this->Cell(65, 6, " : ", 'R', 1, 'L');
}
}



$this->SetFont("", "");

$this->Cell(15, 6, "Mail", '', 0, 'L');
$this->SetFont("", "");
if ($this->admin[0]->mail) {
$this->Cell(75, 6, " : " . utf8_decode($this->admin[0]->mail), '', 0, 'L');
} else {
$this->Cell(75, 6, " : ", '', 0, 'L');
}


// $this->Cell(25, 6, "", 'L', 0, 'L');
// $this->SetFont("", "");
// $this->Cell(65, 6, " En face Pharmacie Hanitra", 'R', 0, 'L');




$this->Cell(12, 6, "", 'R', 0, 'L');

$this->SetFont("", "U");


if ($this->fact[0]->stat != '') {
$this->Cell(25, 6, "STAT", '', 0, 'L');
$this->SetFont("", "");
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->stat), 'R', 1, 'L');
} else {
$this->Cell(25, 6, utf8_decode('Prénoms'), 'L', 0, 'L');
$this->SetFont("", "");
if (isset($this->fact[0]->prenomClient))
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->prenomClient), 'R', 1, 'L');
else
$this->Cell(65, 6, " : ", 'R', 1, 'L');
}




$this->SetFont("", "");

$this->Cell(15, 6, "NIF", '', 0, 'L');
$this->SetFont("", "");
if ($this->admin[0]->nif) {
$this->Cell(75, 6, " : " . utf8_decode($this->admin[0]->nif), '', 0, 'L');
} else {
$this->Cell(75, 6, " : ", '', 0, 'L');
}


$this->Cell(12, 6, "", 'R', 0, 'L');

$this->SetFont("", "U");
$this->Cell(25, 6, utf8_decode('Téléphone'), 'L', 0, 'L');
$this->SetFont("", "");
if (isset($this->fact[0]->telClient))
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->telClient), 'R', 1, 'L');
else
$this->Cell(65, 6, " : ", 'R', 1, 'L');



$this->Cell(15, 6, "STAT", '', 0, 'L');
$this->SetFont("", "");
if ($this->admin[0]->stat) {
$this->Cell(75, 6, " : " . utf8_decode($this->admin[0]->stat), '', 0, 'L');
} else {
$this->Cell(75, 6, " : ", '', 0, 'L');
}



$this->Cell(12, 6, "", 'R', 0, 'L');

$this->SetFont("", "U");
$this->Cell(25, 6, "Adresse", 'L', 0, 'L');
$this->SetFont("", "");
if (isset($this->fact[0]->adresseClient))
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->adresseClient), 'R', 1, 'L');
else
$this->Cell(65, 6, " : ", 'R', 1, 'L');




$this->Cell(12, 6, "", '', 0, 'L');
$this->Cell(90, 6, '', '', 0, 'L');

$this->SetFont("", "U");
$this->Cell(25, 6, "Mail", 'L', 0, 'L');
$this->SetFont("", "");
if (isset($this->fact[0]->emailClient))
$this->Cell(65, 6, " : " . utf8_decode($this->fact[0]->emailClient), 'R', 1, 'L');
else
$this->Cell(65, 6, " :", 'R', 1, 'L');

$this->Cell(90, 6, "", '', 0, 'L');
$this->Cell(12, 6, "", 0, 0, 'L');

$this->SetFont("", "");
$this->Cell(90, 6, " ", 'L;R', 1, 'L');


$this->SetFont("", "U");
$this->Cell(90, 6, "", '', 0, 'L');

$this->Cell(12, 6, "", 0, 0, 'L');

$this->SetFont("", "");
$this->Cell(90, 6, " ", 'L,R,B', 1, 'L');


$this->Ln(2);
$this->Cell(132, 1, "", '', 1, 'L');
$this->Ln(3);
}