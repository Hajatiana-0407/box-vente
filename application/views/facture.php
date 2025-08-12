<?php
class Facturevente extends FPDF
{
    // Fonction pour définir l'opacité de l'image
    function SetAlpha($alpha)
    {
        $this->_out(sprintf('q %.3F 0 0 %.3F 0 0 cm', $alpha, $alpha));
    }

    public $nbr_page = 1;
    public $pos_foot = 100;
    public $affiche = true;
    public function __construct()
    {
        parent::__construct();
    }
    public function head($fact , $admin = [])
    {
        $this->SetFont("Arial", "B", 15);

        if (isset($admin[0]) && $admin[0]->logo != '' && getimagesize(base_url($admin[0]->logo))) {
            $this->Image(base_url($admin[0]->logo), 10, 10, 25, 0, '');
            $this->Cell(90, 20, "", '', 0, 'L');
        } else {
            $this->Cell(90, 20, utf8_decode(strtoupper($admin[0]->entreprise)), '', 0, 'L');
        }



        $this->SetFont("Arial", "", 15);
        $this->Cell(12, 20, "", 0, 0, 'L');
        $this->Cell(90, 10, utf8_decode("Facture N° : ") . $fact->Facture, '', 1, 'L');

        $this->Cell(102, 10, "", '', 0, 'L');

        $this->SetFont("Arial", "", 10);
        $this->Cell(90, 10, "Antananarivo le : " . only_date($fact->dateVente), '', 1, 'L');

        $this->SetFont("Arial", "", 10);
        $this->SetFont("", "");
        $this->SetFont("Arial", "", 9);
        if ($admin[0]->adresse) {
            $this->Cell(90, 6,  utf8_decode($admin[0]->adresse), '', 0, 'L');
        } else {
            $this->Cell(90, 6, " Votre adresse ", '', 0, 'L');
        }



        $this->SetFont("Arial", "", 9);
        $this->SetFont("", "U");
        $this->Cell(12, 20, "", 0, 0, 'L');
        if (isset($fact->r_social) && $fact->r_social != '') {
            $this->Cell(25, 6, "Raison social", 'L,T', 0, 'L');
            $this->SetFont("", "");
            $this->Cell(65, 6, " : " . utf8_decode($fact->r_social), 'R,T', 1, 'L');
        } else {
            $this->Cell(90, 6, "", 'R,T;L', 1, 'L');
        }






        $this->SetFont("", "");

        $this->Cell(15, 6, utf8_decode('Tél'), '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->tel) {
            $this->Cell(75, 6, " : " . utf8_decode($admin[0]->tel), '', 0, 'L');
        } else {
            $this->Cell(75, 6, " : ", '', 0, 'L');
        }



        $this->Cell(12, 6, "", 0, 0, 'L');

        $this->SetFont("", "U");
        if (isset($fact->nif) &&  $fact->nif != '') {
            $this->Cell(25, 6, "NIF", 'L', 0, 'L');
            $this->SetFont("", "");
            $this->Cell(65, 6, " : " . utf8_decode($fact->nif), 'R', 1, 'L');
        } else {
            $this->Cell(25, 6, "Nom", 'L', 0, 'L');
            $this->SetFont("", "");
            if (isset($fact->nomClient)) {
                $this->Cell(65, 6, " :  " . utf8_decode($fact->nomClient), 'R', 1, 'L');
            } else {
                $this->Cell(65, 6, " :  ", 'R', 1, 'L');
            }
        }



        $this->SetFont("", "");

        $this->Cell(15, 6, "Mail", '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->mail) {
            $this->Cell(75, 6, " : " . utf8_decode($admin[0]->mail), '', 0, 'L');
        } else {
            $this->Cell(75, 6, " : ", '', 0, 'L');
        }


        // $this->Cell(25, 6, "", 'L', 0, 'L');
        // $this->SetFont("", "");
        // $this->Cell(65, 6, "  En face Pharmacie Hanitra", 'R', 0, 'L');




        $this->Cell(12, 6, "", 'R', 0, 'L');

        $this->SetFont("", "U");


        if ( isset( $fact->stat ) &&  $fact->stat != '') {
            $this->Cell(25, 6, "STAT", '', 0, 'L');
            $this->SetFont("", "");
            $this->Cell(65, 6, " : " . utf8_decode($fact->stat), 'R', 1, 'L');
        } else {
            $this->Cell(25, 6, utf8_decode('Prénoms'), 'L', 0, 'L');
            $this->SetFont("", "");
            if (isset($fact->prenomClient))
                $this->Cell(65, 6, " :  " . utf8_decode($fact->prenomClient), 'R', 1, 'L');
            else
                $this->Cell(65, 6, " : ", 'R', 1, 'L');
        }




        $this->SetFont("", "");

        $this->Cell(15, 6, "NIF", '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->nif) {
            $this->Cell(75, 6, " :  " . utf8_decode($admin[0]->nif), '', 0, 'L');
        } else {
            $this->Cell(75, 6, " : ", '', 0, 'L');
        }


        $this->Cell(12, 6, "", 'R', 0, 'L');

        $this->SetFont("", "U");
        $this->Cell(25, 6, utf8_decode('Téléphone'), 'L', 0, 'L');
        $this->SetFont("", "");
        if (isset($fact->telClient))
            $this->Cell(65, 6, " :  " . utf8_decode($fact->telClient), 'R', 1, 'L');
        else
            $this->Cell(65, 6, " : ", 'R', 1, 'L');



        $this->Cell(15, 6, "STAT", '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->stat) {
            $this->Cell(75, 6, " : " . utf8_decode($admin[0]->stat), '', 0, 'L');
        } else {
            $this->Cell(75, 6, " : ", '', 0, 'L');
        }



        $this->Cell(12, 6, "", 'R', 0, 'L');

        $this->SetFont("", "U");
        $this->Cell(25, 6, "Adresse", 'L', 0, 'L');
        $this->SetFont("", "");
        if (isset($fact->adresseClient))
            $this->Cell(65, 6, " :  " . utf8_decode($fact->adresseClient), 'R', 1, 'L');
        else
            $this->Cell(65, 6, " : ", 'R', 1, 'L');




        $this->Cell(12, 6, "", '', 0, 'L');
        $this->Cell(90, 6, '', '', 0, 'L');

        $this->SetFont("", "U");
        $this->Cell(25, 6, "Mail", 'L', 0, 'L');
        $this->SetFont("", "");
        if (isset($fact->emailClient))
            $this->Cell(65, 6, " :  " . utf8_decode($fact->emailClient), 'R', 1, 'L');
        else
            $this->Cell(65, 6, " :", 'R', 1, 'L');

        $this->Cell(90, 6, "", '', 0, 'L');
        $this->Cell(12, 6, "", 0, 0, 'L');

        $this->SetFont("", "");
        $this->Cell(90, 6, "  ", 'L;R', 1, 'L');


        $this->SetFont("", "U");
        $this->Cell(90, 6, "", '', 0, 'L');

        $this->Cell(12, 6, "", 0, 0, 'L');

        $this->SetFont("", "");
        $this->Cell(90, 6, "  ", 'L,R,B', 1, 'L');


        $this->Ln(2);
        $this->Cell(132, 1, "", '', 1, 'L');
        $this->Ln(3);
    }
    public function corps($fact, $total, $offset,  $limite = '')
    {


        //TABLEAU
        $this->SetFont("Arial", "B", 10);
        $this->Cell(38, 8, "Description", 1, 0, 'C');
        $this->Cell(38, 8, "Prix Unitaire (en Ar)", 1, 0, 'C');
        $this->Cell(35, 8, utf8_decode('Quantité'), 1, 0, 'C');
        $this->Cell(36, 8, "Remise (en Ar)", 1, 0, 'C');
        $this->Cell(45, 8, "Montant (en Ar)", 1, 1, 'C');

        // CORPS DU TABLEAU
        $this->SetFont("Arial", "", 10);


        if ($this->nbr_page > 1) {
            $count = $limite;
        } else {
            $count = count($fact->paniers);
        }

        for ($i = $offset; $i < $count; $i++) {
            if (!is_null($fact->paniers[$i]->refProduit)) {
                $par_produit = $fact->paniers[$i] ; 
                $this->Cell(38, 7, $par_produit->refProduit, 1, 0, 'L');
                $this->Cell(38, 7, format_number_simple($par_produit->prixunitaire), 1, 0, 'R');
                $this->Cell(35, 7, $par_produit->quantite , 1, 0, 'R');
                $this->Cell(36, 7, format_number_simple($par_produit->remise), 1, 0, 'R');
                $this->Cell(45, 7, format_number_simple($par_produit->prixunitaire * $par_produit->quantite - $par_produit->remise ), 1, 1, 'R');

                $this->pos_foot -= 7;
            }
        }

        if ($this->affiche) {
            // TOTAL

            if ($fact->tva == 'true') {
                $this->SetFont("Arial", "B", 11);
                $this->Cell(111, 7, "", 0, 0, 'L');
                $this->Cell(36, 7, "TOTAL HT", 1, 0, 'C');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["ht"] ), 1, 1, 'R');
                $this->Cell(111, 7, "", 0, 0, 'L');
                $this->Cell(36, 7, "TVA (20%)", 1, 0, 'C');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["tva"] ), 1, 1, 'R');
                $this->Cell(111, 7, "", 0, 0, 'L');
                $this->Cell(36, 7, "TOTAL TTC", 1, 0, 'C');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["ttc"] ), 1, 1, 'R');
            } else {
                $this->SetFont("Arial", "B", 11);
                $this->Cell(111, 7, "", 0, 0, 'L');
                $this->Cell(36, 7, "TOTAL", 1, 0, 'C');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["ht"] ), 1, 1, 'R');
            }


            // FOOTER
            $this->Ln(8);
            $this->SetFont("Arial", "", 10.5);
            //CHIFFRE EN LETTRE
            if ($fact->tva == 'true') {
                $this->MultiCell(189, 7, utf8_decode('Arrêtée la présente facture à la somme de : ') . utf8_decode(ucfirst(Utility::number_to_letter(number_format($total['ttc'], 2, '.', '')))), 0, 1);
            } else {

                $this->MultiCell(189, 7, utf8_decode('Arrêtée la présente facture à la somme de : ') . utf8_decode(ucfirst(Utility::number_to_letter(number_format($total['ht'], 2, '.', '')))), 0, 1);
            }

            $this->Ln(8);
            $this->SetFont("", "UB", 11);
            $this->Cell(50, 7, "Le Client", 0, 0, 'L');
            $this->Cell(130, 7, "Le Vendeur", 0, 1, 'R');
        }




        // Ajoutez la filigrane (image) ici
        $this->SetAlpha(0.5); // Opacité de l'image
        $this->Image(base_url('public/images/filigrane1.png'), 100, -250, $this->GetPageWidth(), $this->GetPageHeight(), '', '', '', true);
        $this->SetAlpha(1); // Réinitialiser l'opacité à 1


    }
    public function foot()
    {
        $this->SetFont("", "", 9);
        $this->Cell(7, 4, "(1) - ", 0, 0, 'L');
        $this->Cell(126, 4, utf8_decode("La maison décline toutes responsabilités pour les marchandises sorites de ses magasins. (avaries , manquants ou déchets.)"), 0, 1, 'L');
        $this->Cell(7, 4, "(2) - ", 0, 0, 'L');
        $this->Cell(126, 4, utf8_decode("Toutes expéditions sont faites aux risques et périls du déstinataire."), 0, 1, 'L');
        $this->Cell(7, 4, "(3) - ", 0, 0, 'L');
        $this->Cell(126, 4, utf8_decode("Les marchandises ne sont assurées au départ que sur demande expresse du client."), 0, 1, 'L');
        $this->Cell(7, 4, "(4) - ", 0, 0, 'L');
        $this->Cell(126, 4, utf8_decode("Les marchandises vendues ne sont ni reprises ni échangées."), 0, 1, 'L');
        $this->Cell(7, 4, "(5) - ", 0, 0, 'L');
        $this->Cell(126, 4, utf8_decode("En cas de contestation le Tribunal Antananarivo est le seul compétent."), 0, 1, 'L');
    }
}

$pdf = new Facturevente("P", "mm", "A4");
$pdf->AddPage();

// if (count($fact) > 10) {
//     $pdf->nbr_page = ceil(count($fact) / 10);
// }

for ($i = 0; $i < $pdf->nbr_page; $i++) {
    $offest = $i * 10;
    $limite = ($i + 1) * 10;
    if ($pdf->nbr_page > 1 && $i != ($pdf->nbr_page - 1)) {
        $pdf->pos_foot = 158;
        $pdf->affiche = false;
    } else {
        $pdf->pos_foot = 100;
        $pdf->affiche = true;
    }

    $pdf->head($fact, $admin);
    $pdf->Ln(8);
    $pdf->corps($fact, $total, $offest,  $limite);
    $pdf->Ln($pdf->pos_foot);
    // $pdf->foot();

    $pdf->pos_foot = 100;
}


$pdf->Output();
