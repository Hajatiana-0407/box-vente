<?php
class Facturevente extends FPDF
{


    private $admin;
    private $facture;
    private $total;

    private $noHeader = false;
    private $noFooter = false;

    private $islaste_page = false;

    public function __construct()
    {
        parent::__construct();
    }

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


    public function no_header($values = true)
    {
        $this->noHeader = $values;
    }
    public function no_footer($values = true)
    {
        $this->noFooter = $values;
    }


    // Fonction pour définir l'opacité de l'image
    function SetAlpha($alpha)
    {
        $this->_out(sprintf('q %.3F 0 0 %.3F 0 0 cm', $alpha, $alpha));
    }

    public function lettre($texte, $nbr = 38)
    {
        $nombre_de_lettres = $nbr; // Spécifie combien de lettres tu veux afficher

        $resultat = substr($texte, 0,  $nombre_de_lettres);

        return  $resultat;
    }

    public function Header()
    {

        if ($this->noHeader) {
            return;
        }


        $this->SetFont("Arial", "B", 20);

        if (isset($this->admin) && $this->admin->logo != '' && getimagesize(Myurl($this->admin->logo))) {
            $this->Image(Myurl($this->admin->logo), 10, 10, 0, 20, '');
            $this->Cell(90, 20, "", '', 0, 'L');
        } else {
            $this->Cell(90, 20, utf8_decode(strtoupper($this->admin->entreprise)), '', 0, 'L');
        }



        $this->SetFont("Arial", "", 20);
        $this->Cell(10, 20, "", 0, 0, 'L');
        $this->Cell(90, 10, utf8_decode("Facture N° : ") . $this->facture->Facture, '', 1, 'L');

        $this->Cell(102, 10, "", "", 0, 'L');

        $this->SetFont("Arial", "", 15);
        $this->Cell(90, 10, "Antananarivo le : " . only_date($this->facture->dateVente), '', 1, 'L');

        $this->Cell(100, 5, "", "", 0, 'L');
        $this->Cell(92, 5, "", "TRL", 1, 'L');

        $this->SetFont("Arial", "", 15);
        $this->SetFont("", "");
        $this->SetFont("Arial", "B", 12);
        if ($this->admin->adresse) {
            $this->Cell(90, 6, utf8_decode($this->admin->adresse), '', 0, 'L');
        } else {
            $this->Cell(90, 6, " Votre adresse ", '', 0, 'L');
        }



        $this->SetFont("Arial", "", 12);
        $this->SetFont("", "BU");
        $this->Cell(10, 20, "", 0, 0, 'L');
        if (isset($this->facture->r_social) && $this->facture->r_social != '') {
            $this->Cell(17, 6, "Raison ", 'L', 0, 'L');
            $this->SetFont("", "");
            $this->Cell(75, 6, " : "       .  utf8_decode($this->facture->r_social), 'R', 1, 'L');
        } else {
            $this->Cell(92, 6, "", 'RL', 1, 'L');
        }


        $this->SetFont("", "B");

        $this->Cell(12, 6, utf8_decode('Tél'), '', 0, 'L');
        $this->SetFont("", "");
        if ($this->admin->tel) {
            $this->Cell(78, 6, " : "       . utf8_decode($this->admin->tel), '', 0, 'L');
        } else {
            $this->Cell(78, 6, " : ", '', 0, 'L');
        }



        $this->Cell(10, 6, "", 0, 0, 'L');

        $this->SetFont("", "BU");
        if (isset($this->facture->nif) && $this->facture->nif != '') {
            $this->Cell(17, 6, "NIF", 'L', 0, 'L');
            $this->SetFont("", "");
            $this->Cell(75, 6, " : "       .  utf8_decode($this->facture->nif), 'R', 1, 'L');
        } else {
            $this->Cell(17, 6, "Nom", 'L', 0, 'L');
            $this->SetFont("", "");
            if (isset($this->facture->nomClient)) {
                $this->Cell(75, 6, " : "       .  utf8_decode($this->facture->nomClient), 'R', 1, 'L');
            } else {
                $this->Cell(75, 6, "", 'R', 1, 'L');
            }
        }



        $this->SetFont("", "B");

        $this->Cell(12, 6, "Mail", '', 0, 'L');
        $this->SetFont("", "");
        if ($this->admin->mail) {
            $this->Cell(78, 6, " : "       . utf8_decode($this->admin->mail), '', 0, 'L');
        } else {
            $this->Cell(78, 6, " : ", '', 0, 'L');
        }





        $this->Cell(10, 6, "", 'R', 0, 'L');

        $this->SetFont("", "BU");


        if (isset($this->facture->stat) && $this->facture->stat != '') {
            $this->Cell(17, 6, "STAT", '', 0, 'L');
            $this->SetFont("", "");
            $this->Cell(75, 6, " : "       .  utf8_decode($this->facture->stat), 'R', 1, 'L');
        } else {
            $this->Cell(17, 6, utf8_decode('Prénoms'), 'L', 0, 'L');
            $this->SetFont("", "");
            if (isset($this->facture->prenomClient))
                $this->Cell(75, 6, " : "    .   utf8_decode($this->facture->prenomClient), 'R', 1, 'L');
            else
                $this->Cell(75, 6, "", 'R', 1, 'L');
        }




        $this->SetFont("", "B");

        $this->Cell(12, 6, "NIF", '', 0, 'L');
        $this->SetFont("", "");
        if ($this->admin->nif) {
            $this->Cell(78, 6, " : "       . utf8_decode($this->admin->nif), '', 0, 'L');
        } else {
            $this->Cell(78, 6, " : ", '', 0, 'L');
        }


        $this->Cell(10, 6, "", 'R', 0, 'L');

        $this->SetFont("", "BU");
        $this->Cell(17, 6, utf8_decode('Tél'), 'L', 0, 'L');
        $this->SetFont("", "");
        if (isset($this->facture->telClient) && $this->facture->telClient != '')
            $this->Cell(75, 6, " : " .  utf8_decode($this->facture->telClient), 'R', 1, 'L');
        else
            $this->Cell(75, 6, "", 'R', 1, 'L');



        $this->SetFont("", "B");
        $this->Cell(12, 6, "STAT", '', 0, 'L');
        $this->SetFont("", "");
        if ($this->admin->stat) {
            $this->Cell(78, 6, " : "       . utf8_decode($this->admin->stat), '', 0, 'L');
        } else {
            $this->Cell(78, 6, " : ", '', 0, 'L');
        }



        $this->Cell(10, 6, "", 'R', 0, 'L');

        $this->SetFont("", "BU");
        $this->Cell(17, 6, "Adresse", 'L', 0, 'L');
        $this->SetFont("", "");
        if (isset($this->facture->adresseClient))
            $this->Cell(75, 6, " : "       . utf8_decode($this->facture->adresseClient), 'R', 1, 'L');
        else
            $this->Cell(75, 6, "", 'R', 1, 'L');




        $this->Cell(10, 6, "", '', 0, 'L');
        $this->Cell(90, 6, '', '', 0, 'L');

        $this->SetFont("", "BU");
        $this->Cell(17, 6, "Mail", 'L', 0, 'L');
        $this->SetFont("", "");
        if (isset($this->facture->emailClient))
            $this->Cell(75, 6, " : "       . utf8_decode($this->facture->emailClient), 'R', 1, 'L');
        else
            $this->Cell(75, 6, "", 'R', 1, 'L');

        $this->Cell(90, 6, "", '', 0, 'L');
        $this->Cell(10, 6, "", 0, 0, 'L');

        $this->SetFont("", "");
        $this->Cell(92, 6, " ", 'LBR', 1, 'L');


        $this->Ln(2);
        $this->Cell(132, 1, "", '', 1, 'L');
        $this->Ln(3);
    }

    function Footer()
    {
        if ($this->islaste_page) {
            $this->SetFont('', '', 14);
        }


        // Positionnement à 1.5 cm du bas


        // Police pour le footer
        $this->SetFont('Arial', '', 7);
        if ($this->islaste_page) {
            $this->setY(180); // Ajustez ici la position verticale selon votre besoin
            $this->SetFont('Arial', '', 14);
            // Positionnement du texte à gauche

            $this->SetX(15);
            // Texte à gauche : NIF, STAT, Email, Web
            $this->MultiCell(0, 8, "Top Tech Generation", 0, 'L');
            $this->SetX(15);
            $this->MultiCell(0, 8, "NIF : 6011866582", 0, 'L');
            $this->SetX(15);
            $this->MultiCell(0, 8, "STAT : 47 41411 2022 0 05500", 0, 'L');
            $this->SetX(15);
            $this->MultiCell(0, 8, "Email : shop@toptechgeneration.mg", 0, 'L');
            $this->SetX(15);
            $this->MultiCell(0, 8, "Web : www.toptechgeneration.mg", 0, 'L');
            $this->SetX(15);
            $this->MultiCell(290, 8, utf8_decode("TERRE ROUGE Mall - Box 106 - Ankadifotsy, Antananarivo 101 "), 0, 'L');
            $this->SetX(15);
            $this->MultiCell(290, 7, utf8_decode("Le Colisée Galerie commerciale - Box 22B - Tsiadana Ampasanimalo, Antananarivo 101 .Contact : +261 34 30 598 35 | +261 38 63 513 50"), 0, 'L');
        } else {
            // Positionnement du texte à gauche
            $this->setY(230); // Ajustez ici la position verticale selon votre besoin
            $this->SetX(10); // Positionne à 10mm du bord gauche

            // Texte à gauche : NIF, STAT, Email, Web
            $this->MultiCell(0, 4, "Top Tech Generation", 0, 'L');
            $this->MultiCell(0, 4, "NIF : 6011866582", 0, 'L');
            $this->MultiCell(0, 4, "STAT : 47 41411 2022 0 05500", 0, 'L');
            $this->MultiCell(0, 4, "Email : shop@toptechgeneration.mg", 0, 'L');
            $this->MultiCell(0, 4, "Web : www.toptechgeneration.mg", 0, 'L');
            $this->MultiCell(290, 4, utf8_decode("TERRE ROUGE Mall - Box 106 - Ankadifotsy, Antananarivo 101 "), 0, 'L');
            $this->MultiCell(290, 4, utf8_decode("Le Colisée Galerie commerciale - Box 22B - Tsiadana Ampasanimalo, Antananarivo 101 .Contact : +261 34 30 598 35 | +261 38 63 513 50"), 0, 'L');
        }
    }
    public function corps()
    {
        $this->SetFont('Arial', '', 10);

        $this->Cell(64, 8, utf8_decode('Déscription'), 1, 0, 'L');
        $this->Cell(38, 8, "Prix Unitaire (en Ar)", 1, 0, 'C');
        $this->Cell(15, 8, utf8_decode('Qte'), 1, 0, 'C');
        $this->Cell(30, 8, "Remise (en Ar)", 1, 0, 'C');
        $this->Cell(45, 8, "Montant (en Ar)", 1, 1, 'C');

        for ($i = 0; $i < count($this->facture->paniers); $i++) {
            if (!is_null($this->facture->paniers[$i]->refProduit)) {
                $par_produit = $this->facture->paniers[$i];

                $this->Cell(64,  0.5, '', "LRT");
                $this->Cell(38,  0.5, '', "LRT", 0, 'R');
                $this->Cell(15,  0.5, '', "LRT", 0, 'R');
                $this->Cell(30,  0.5, '', "LRT", 0, 'R');
                $this->Cell(45,  0.5, '', "LRT", 1, 'R');

                // *****************
                $this->Cell(64,  6.5, $this->lettre(utf8_decode($par_produit->designation), 40), "LR");
                $this->Cell(38,  6.5, format_number_simple($par_produit->prixunitaire), "LR", 0, 'R');
                $this->Cell(15,  6.5, $par_produit->quantite, "LR", 0, 'R');
                $this->Cell(30,  6.5, format_number_simple($par_produit->remise), "LR", 0, 'R');
                $this->Cell(45,  6.5, format_number_simple($par_produit->prixunitaire * $par_produit->quantite - $par_produit->remise), "LR", 1, 'R');                // *****************
                if ($par_produit->fiche != '') {
                    $this->Cell(64,  6.5, $this->lettre(utf8_decode($par_produit->fiche), 40), "LR");
                    $this->Cell(38,  6.5, '', "LR", 0, 'R');
                    $this->Cell(15,  6.5, '', "LR", 0, 'R');
                    $this->Cell(30,  6.5, '', "LR", 0, 'R');
                    $this->Cell(45,  6.5, '', "LR", 1, 'R');
                }
                if ($par_produit->couleur != '') {
                    $this->Cell(64,  6.5, utf8_decode('Couleur : '              . $par_produit->couleur), "LR");
                    $this->Cell(38,  6.5, '', "LR", 0, 'R');
                    $this->Cell(15,  6.5, '', "LR", 0, 'R');
                    $this->Cell(30,  6.5, '', "LR", 0, 'R');
                    $this->Cell(45,  6.5, '', "LR", 1, 'R');
                }                // *****************
                if ($par_produit->imei1 != '') {
                    $this->Cell(64,  6.5, utf8_decode('IMEI 1  : '              . $par_produit->imei1), "LR");
                    $this->Cell(38,  6.5, '', "LR", 0, 'R');
                    $this->Cell(15,  6.5, '', "LR", 0, 'R');
                    $this->Cell(30,  6.5, '', "LR", 0, 'R');
                    $this->Cell(45,  6.5, '', "LR", 1, 'R');
                }
                if ($par_produit->imei2 != '') {
                    $this->Cell(64,  6.5, utf8_decode('IMEI 2  : '              . $par_produit->imei2), "LR");
                    $this->Cell(38,  6.5, '', "LR", 0, 'R');
                    $this->Cell(15,  6.5, '', "LR", 0, 'R');
                    $this->Cell(30,  6.5, '', "LR", 0, 'R');
                    $this->Cell(45,  6.5, '', "LR", 1, 'R');
                }
                $this->Cell(64,  0.5, '', "LRB");
                $this->Cell(38,  0.5, '', "LRB", 0, 'R');
                $this->Cell(15,  0.5, '', "LRB", 0, 'R');
                $this->Cell(30,  0.5, '', "LRB", 0, 'R');
                $this->Cell(45,  0.5, '', "LRB", 1, 'R');

                if ($this->GetY() > 200) {  // Si la position dépasse 140 mm (la moitié de la page)
                    $this->AddPage();  // Ajouter une nouvelle page
                }
            }
        }

        if ($this->facture->frais) {
            $this->SetFont("Arial", "B", 10);
            $this->Cell(117, 7, "", 0, 0, 'L');
            $this->Cell(30, 7, "Frais de livraison", 1, 0, 'C');
            $this->Cell(45, 7, format_number_simple($this->facture->frais), 1, 1, 'R');
        }

        if ($this->facture->tva == 'true') {
            $this->SetFont("Arial", "B", 11);
            $this->Cell(117, 7, "", 0, 0, 'L');
            $this->Cell(30, 7, "TOTAL HT", 1, 0, 'C');

            //MONTANT TOTAL
            $this->Cell(45, 7, format_number_simple($this->total["ht"]), 1, 1, 'R');
            $this->Cell(117, 7, "", 0, 0, 'L');
            $this->Cell(30, 7, "TVA (20%)", 1, 0, 'C');

            //MONTANT TOTAL
            $this->Cell(45, 7, format_number_simple($this->total["tva"]), 1, 1, 'R');
            $this->Cell(117, 7, "", 0, 0, 'L');
            $this->Cell(30, 7, "TOTAL TTC", 1, 0, 'C');

            //MONTANT TOTAL
            $this->Cell(45, 7, format_number_simple($this->total["ttc"]), 1, 1, 'R');
        } else {
            $this->SetFont("Arial", "B", 11);
            $this->Cell(117, 7, "", 0, 0, 'L');
            $this->Cell(30, 7, "TOTAL", 1, 0, 'C');

            //MONTANT TOTAL
            $this->Cell(45, 7, format_number_simple($this->total["ht"]), 1, 1, 'R');
        }


        // FOOTER
        $this->Ln(8);
        $this->SetFont("Arial", "", 10.5);
        //CHIFFRE EN LETTRE
        if ($this->facture->tva == 'true') {
            $this->MultiCell(189, 7, utf8_decode('Arrêtée la présente facture à la somme de : ')              . utf8_decode(strtolower(Utility::number_to_letter(number_format($this->total['ttc'], 2, '.', '')))), 0, 1);
        } else {

            $this->MultiCell(189, 7, utf8_decode('Arrêtée la présente facture à la somme de : ')              . utf8_decode(strtolower(Utility::number_to_letter(number_format($this->total['ht'], 2, '.', '')))), 0, 1);
        }

        $this->SetFont("Arial", "", 9);
        $this->MultiCell(189, 7, utf8_decode('Mode de paiement : ')              . utf8_decode($this->facture->denom), 0, 1);

        $this->Ln(8);
        $this->SetFont("", "UB", 11);
        $this->Cell(50, 7, "Le Client", 0, 0, 'L');
        $this->Cell(130, 7, "Le Fournisseur", 0, 1, 'R');


        // Ajoutez la filigrane (image) ici
        $this->SetAlpha(0.5); // Opacité de l'image
        if (!isset($_SESSION['abonne']) || $_SESSION['abonne'] == false) {
            $this->Image(Myurl('public/images/filigrane1.png'), 100, -250, $this->GetPageWidth(), $this->GetPageHeight(), '', '', '', true);
        }
        $this->SetAlpha(1); // Réinitialiser l'opacité à 1 
    }

    public function LastPage()
    {
        $this->islaste_page = true;
    }
}
