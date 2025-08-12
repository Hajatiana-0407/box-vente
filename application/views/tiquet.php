<?php

class Pdf extends FPDF
{
    private $admin;
    private $facture;
    private $total;

    public function set_admin($admin_)
    {

        $this->admin = $admin_[0]; // Affectation correcte

    }

    public function set_facture($facture)
    {
        $this->facture = $facture;
    }

    public function set_total($total)
    {
        $this->total = $total;
    }
    public function lettre($texte , $nbr = 4 )
    {
        $nombre_de_lettres = $nbr ; // Spécifie combien de lettres tu veux afficher

        $resultat = substr($texte, 0, $nombre_de_lettres);

        return  $resultat;
    }

    function Header()
    {

        $this->SetFont('Courier', 'B', 10);
        $this->SetY(2);

        // Titre
        $this->SetX(2);
        if (isset($this->admin->entreprise) && $this->admin->entreprise != '') {
            $this->Cell(0, 6, utf8_decode($this->admin->entreprise), 0, 1, 'L');
        } else {
            $this->Cell(0, 6, 'SOFTIO-APP', 0, 1, 'L');
        }
        $this->Ln(3);
        $this->SetX(1);
        $this->SetFont('Courier', '', 8);
        $this->Cell(4, 4, utf8_decode('PV  '), 0, 0, 'L');
        $this->Cell(40, 4, utf8_decode(' : ' . $this->facture->denomination_pv .' ' . utf8_decode( $this->lettre( $this->facture->adressPv , 10  ))), 0, 0, 'L');

        $this->Cell(6, 4, utf8_decode('N°'), 0, 0, 'L');
        $this->Cell(25, 4, ' : ' . $this->facture->Facture, 0, 1, 'L');
        $this->SetX(1);
        $this->Cell(44, 4,'', 0, 0, 'L');
        $this->Cell(6, 4, utf8_decode('Date'), 0, 0, 'L');
        $this->Cell(20, 4, ' : ' . only_date($this->facture->dateVente), 0, 1, 'L');
        // Ligne
        $this->Ln(2);
    }

    function Footer()
    {
    }

    function Body()
    {

        $this->SetFont('Courier', 'B', 7);
        $this->SetX(1);
        $this->Cell(10, 4, 'Des', 0, 0, 'L');
        $this->Cell(18, 4, 'Qte', 0, 0, 'L');
        $this->Cell(18, 4, 'Prix', 0, 0, 'L');
        $this->Cell(15, 4, 'Rem', 0, 0, 'L');
        $this->Cell(18, 4, 'Montant', 0, 1, 'L');

        $paniers = $this->facture->paniers;
        $this->SetFont('Courier', 'I', 6.5);
        foreach ($paniers  as $key => $panier) {
            $this->SetX(1);
            $this->Cell(10, 4, utf8_decode($this->lettre($panier->designation)), 0 , 0, 'L');
            $this->Cell(18, 4, ( $panier->quantite > 1  ) ?  $panier->quantite :  $panier->quantite , 0 , 0, 'L');
            $this->Cell(18, 4,  $panier->prixunitaire  ,  0 , 0, 'L');
            $this->Cell(15, 4,  $panier->remise  ,  0, 0, 'L');
            $this->Cell(18, 4,( $panier->prixunitaire *  $panier->quantite  - $panier->remise ) ,  0, 0, 'L');
        }

        $this->Ln(6);
        $this->Cell(0, 4, "..............", 0, 1, 'R');

        if ( $this->facture->tva ){
            $this->SetFont('Courier', '', 7);
            $this->Cell(36, 4, "TOTAL HT", 0, 0, 'R');
            $this->Cell(33, 4,' : ' .  number_three( $this->total['ht']), 0, 1, 'L');
            $this->Cell(36, 4, utf8_decode ( " TVA (20%)" ), 0, 0, 'R');
            $this->Cell(33, 4,' : ' .  number_three( $this->total['tva']), 0, 1, 'L');
            $this->SetFont('Courier', 'B', 7);
            $this->Cell(36, 4, "TOTAL TTC", 0, 0, 'R');
            $this->Cell(33, 4,' : ' .  number_three( $this->total['ttc']), 0, 1, 'L');
        }else {
            $this->SetFont('Courier', 'B', 7);
            $this->Cell(36, 4, "TOTAL ", 0, 0, 'R');
            $this->Cell(33, 4,' : ' .  number_three( $this->total['ht']), 0, 1, 'L');
        }

    }
}



$pdf = new Pdf("P", "mm", [80, 100]);

$pdf->set_admin($admin);
$pdf->set_facture($facture);
$pdf->set_total($total);
$pdf->AddPage();

// Appeler la méthode Body pour ajouter le contenu principal
$pdf->Body();

$pdf->Output();
