<?php


class Commandefacture extends FPDF
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
    public function head($fact, $admin = [])
    {
        $this->SetFont("Arial", "B", 15);

        if (isset($admin[0]) && $admin[0]->logo != '' && getimagesize(Myurl($admin[0]->logo))) {
            $this->Image(Myurl($admin[0]->logo), 10, 14, 0 , 15, '');
            $this->Cell(90, 20, "", '', 0, 'L');
        } else {
            $this->Cell(90, 20, utf8_decode(strtoupper($admin[0]->entreprise)), '', 0, 'L');
        }

        $this->SetFont("Arial", "", 15);
        $this->Cell(12, 20, "", 0, 0, 'L');
        $this->Cell(90, 10, utf8_decode("Bon de commande N° : ") . $fact->cmfacture, '', 1, 'L');

        $this->Cell(102, 10, "", '', 0, 'L');

        $this->SetFont("Arial", "", 10);
        $this->Cell(90, 10, "Antananarivo le : " . only_date($fact->datecommande), '', 1, 'L');

        $this->SetFont("Arial", "", 10);
        $this->SetFont("", "");
        $this->SetFont("Arial", "", 9);
        if ($admin[0]->adresse) {
            $this->Cell(90, 6,  utf8_decode($admin[0]->adresse), '', 0, 'L');
        } else {
            $this->Cell(90, 6, " Votre adresse ", '', 0, 'L');
        }



        $this->Cell(12, 6, "", 0, 0, 'L');
        $this->Cell(90, 6, " ", 'RTL', 1, 'L');

        $this->SetFont("", "");

        $this->Cell(10, 6, utf8_decode('Tél'), '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->tel) {
            $this->Cell(80, 6, " : " . utf8_decode($admin[0]->tel), '', 0, 'L');
        } else {
            $this->Cell(80, 6, " : ", '', 0, 'L');
        }





        // $this->SetFont("", "U");
        // $this->Cell(15, 6, "NIF", 'L', 0, 'L');
        // if (isset($fact->nif_fournisseur) &&  $fact->nif_fournisseur != '') {
        //     $this->SetFont("", "");
        //     $this->Cell(90, 6, " : " . utf8_decode($fact->nif_fournisseur), 'R', 1, 'L');
        // } else {
        //     $this->SetFont("", "");

        // }
        $this->Cell(12, 6, "", 0, 0, 'L');

        $this->SetFont("", "U");
        if (isset($fact->nom_entr)) {
            $this->SetFont("", "");
            $this->Cell(90, 6, '      ' . utf8_decode($fact->nom_entr), 'RL', 1, 'L');
        } else {
            $this->Cell(90, 6, " ", 'RL', 1, 'L');
        }
        $this->SetFont("", "");

        $this->Cell(10, 6, "Mail", '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->mail) {
            $this->Cell(80, 6, " : " . utf8_decode($admin[0]->mail), '', 0, 'L');
        } else {
            $this->Cell(80, 6, " : ", '', 0, 'L');
        }


        // $this->Cell(15, 6, "", 'L', 0, 'L');
        // $this->SetFont("", "");
        // $this->Cell(75, 6, "  En face Pharmacie Hanitra", 'R', 0, 'L');




        $this->Cell(12, 6, "", 'R', 0, 'L');

        $this->SetFont("", "U");


        $this->SetFont("", "U");
        $this->SetFont("", "");
        if (isset($fact->adresse_fournisseur))
            $this->Cell(90, 6, '      ' . utf8_decode($fact->adresse_fournisseur), 'RL', 1, 'L');
        else
            $this->Cell(90, 6, " ", 'RL', 1, 'L');




        $this->SetFont("", "");

        $this->Cell(10, 6, "NIF", '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->nif) {
            $this->Cell(80, 6, " :  " . utf8_decode($admin[0]->nif), '', 0, 'L');
        } else {
            $this->Cell(80, 6, " : ", '', 0, 'L');
        }


        $this->Cell(12, 6, "", 'R', 0, 'L');

        $this->SetFont("", "U");
        $this->SetFont("", "");
        if (isset($fact->tel_fournisseur))
            $this->Cell(90, 6, '      ' . utf8_decode($fact->tel_fournisseur), 'RL', 1, 'L');
        else
            $this->Cell(90, 6, "", 'RL', 1, 'L');



        $this->Cell(10, 6, "STAT", '', 0, 'L');
        $this->SetFont("", "");
        if ($admin[0]->stat) {
            $this->Cell(80, 6, " : " . utf8_decode($admin[0]->stat), '', 0, 'L');
        } else {
            $this->Cell(80, 6, " : ", '', 0, 'L');
        }



        $this->Cell(12, 6, "", '', 0, 'L');

        // eto
        $this->SetFont("", "U");
        $this->SetFont("", "");
        if (isset($fact->mail_fournisseur) && $fact->mail_fournisseur != '')
            $this->Cell(90, 6, '      ' .  utf8_decode($fact->mail_fournisseur), 'RL', 1, 'L');
        else
            $this->Cell(90, 6, "", 'RL', 1, 'L');

        $this->Cell(90, 6, "", '', 0, 'L');
        $this->Cell(12, 6, "", 0, 0, 'L');

        $this->SetFont("", "");
        $this->Cell(90, 6, "  ", 'LBR', 1, 'L');





        // $this->Cell(12, 6, "", '', 0, 'L');
        // $this->Cell(90, 6, '', '', 0, 'L');


        // $this->Cell(90, 6, "", 'RBL', 1, 'L');




        // $this->SetFont("", "U");
        // $this->Cell(90, 6, "", '', 0, 'L');

        // $this->Cell(12, 6, "", 0, 0, 'L');

        // $this->SetFont("", "");
        // $this->Cell(90, 6, "***", 'L,R,B', 1, 'L');


        $this->Ln(2);
        $this->Cell(132, 1, "", '', 1, 'L');
        $this->Ln(3);
    }
    public function corps($fact, $total, $offset,  $limite = '')
    {


        //TABLEAU
        $this->SetFont("Arial", "B", 10);
        $this->Cell(30, 8, utf8_decode('Référence'), 1, 0, 'C');
        $this->Cell(38, 8, utf8_decode('Désignation'), 1, 0, 'C');
        $this->Cell(38, 8, "Prix Unitaire (en Ar)", 1, 0, 'C');
        $this->Cell(41, 8, utf8_decode('Quantité'), 1, 0, 'C');
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
                $par_produit = $fact->paniers[$i];
                $this->Cell(30, 7, utf8_decode($par_produit->reference_fournisseur), 1, 0, 'R');
                $this->Cell(38, 7, utf8_decode($par_produit->designation), 1, 0, 'R');
                $this->Cell(38, 7, format_number_simple($par_produit->prixunitaire), 1, 0, 'R');
                $this->Cell(41, 7, $par_produit->quantite, 1, 0, 'R');
                $this->Cell(45, 7, format_number_simple($par_produit->prixunitaire * $par_produit->quantite), 1, 1, 'R');

                $this->pos_foot -= 7;
            }
        }

        if ($this->affiche) {
            // TOTAL

            if ($fact->frais != 0) {
                $this->SetFont("Arial", "B", 11);
                $this->Cell(106, 7, "", 0, 0, 'L');
                $this->Cell(41, 7, "Frais de livraison", 1, 0, 'R');
                $this->Cell(45, 7, format_number_simple($fact->frais), 1, 1, 'R');
            }


            if ($fact->tva == 'true') {
                $this->SetFont("Arial", "B", 11);
                $this->Cell(106, 7, "", 0, 0, 'L');
                $this->Cell(41, 7, "TOTAL HT", 1, 0, 'R');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["ht"]), 1, 1, 'R');
                $this->Cell(106, 7, "", 0, 0, 'L');
                $this->Cell(41, 7, "TVA (20%)", 1, 0, 'R');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["tva"]), 1, 1, 'R');
                $this->Cell(106, 7, "", 0, 0, 'L');
                $this->Cell(41, 7, "TOTAL TTC", 1, 0, 'R');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["ttc"]), 1, 1, 'R');
            } else {
                $this->SetFont("Arial", "B", 11);
                $this->Cell(106, 7, "", 0, 0, 'L');
                $this->Cell(41, 7, "TOTAL", 1, 0, 'R');

                //MONTANT TOTAL
                $this->Cell(45, 7, format_number_simple($total["ht"]), 1, 1, 'R');
            }



            $this->Ln(9);
            $this->SetFont("", "", 11);
            $this->Cell(50, 7, "Signature", 0, 0, 'L');
        }




        // Ajoutez la filigrane (image) ici
        $this->SetAlpha(0.5); // Opacité de l'image
        if ( !isset( $_SESSION['abonne']) || $_SESSION['abonne'] == false ){
            $this->Image(Myurl('public/images/filigrane1.png'), 100, -250, $this->GetPageWidth(), $this->GetPageHeight(), '', '', '', true);
        }
        $this->SetAlpha(1); // Réinitialiser l'opacité à 1


    }
}


