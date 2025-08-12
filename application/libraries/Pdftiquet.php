    <?php

    class Pdftiquet extends FPDF
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
        public function lettre($texte, $nbr = 38)
        {
            $nombre_de_lettres = $nbr; // Spécifie combien de lettres tu veux afficher

            $resultat = substr($texte, 0,  $nombre_de_lettres );

            return  $resultat;
        }

        function Header()
        {

            $this->SetFont('Courier', 'B', 10);
            $this->SetY(1);

            // Titre
            $this->SetX(1);
            if (isset($this->admin->entreprise) && $this->admin->entreprise != '') {
                $this->Cell(0, 6, utf8_decode($this->admin->entreprise), 0, 1, 'C');
                $this->SetFont('Courier', '', 8);
                if ($this->admin->tel) {
                    $this->SetX(2);
                    $this->Cell(0, 4, 'Tel:' . $this->admin->tel, 0, 1, 'C');
                }
                if ($this->admin->mail) {
                    $this->SetX(2);
                    $this->Cell(0, 4, $this->admin->mail, 0, 1, 'C');
                }
                if ($this->admin->adresse) {
                    $this->SetX(2);
                    $this->Cell(0, 4,  $this->admin->adresse, 0, 1, 'C');
                }
            } else {
                $this->Cell(0, 4, 'SOFTIO-APP', 0, 1, 'C');
            }
            $this->Ln(3);
            $this->SetX(1);
            $this->SetFont('Courier', '', 8);
            $this->Cell(4, 4, utf8_decode('PV  '), 0, 0, 'L');
            $this->Cell(40, 4, utf8_decode(' : ' . $this->facture->denomination_pv . ' ' . utf8_decode($this->lettre($this->facture->adressPv, 10))), 0, 0, 'L');

            $this->Cell(6, 4, utf8_decode('N°'), 0, 0, 'L');
            $this->Cell(25, 4, ' : ' . $this->facture->Facture, 0, 1, 'L');

            $this->SetX(1);
            $this->Cell(44, 4, '', 0, 0, 'L');
            $this->Cell(6, 4, utf8_decode('Date'), 0, 0, 'L');
            $this->Cell(20, 4, ' : ' . only_date($this->facture->dateVente), 0, 1, 'L');
            // Ligne
            // $this->Ln(2);
        }

        function Footer() {}

        function Body()
        {
            $this->SetX(1);
            $paniers = $this->facture->paniers;

            foreach ($paniers  as $key => $panier) {

                $this->SetX(1);

                $this->SetFont('Courier', 'B', 7);
                $this->Cell(22, 3.8,  utf8_decode('Désignation'), 0, 0, 'L');
                $this->SetFont('Courier', 'I', 6.5);
                $this->Cell(55, 3.8, utf8_decode($this->lettre($panier->designation)), 0, 1, 'L');


                $this->SetX(1);
                $this->SetFont('Courier', 'B', 7);
                $this->Cell(22, 3.8, utf8_decode('Quantité'), 0, 0, 'L');
                $this->SetFont('Courier', 'I', 6.5);
                $this->Cell(55, 3.8,   $panier->quantite, 0, 1, 'L');

                $this->SetX(1);
                $this->SetFont('Courier', 'B', 7);
                $this->Cell(22, 3.8, 'Prix', 0, 0, 'L');
                $this->SetFont('Courier', 'I', 6.5);
                $this->Cell(55, 3.8,  $panier->prixunitaire,  0, 1, 'L');

                $this->SetX(1);
                $this->SetFont('Courier', 'B', 7);
                $this->Cell(22, 3.8, 'Remise', 0, 0, 'L');
                $this->SetFont('Courier', 'I', 6.5);
                $this->Cell(55, 3.8,  $panier->remise,  0, 1, 'L');

                $this->SetX(1);
                $this->SetFont('Courier', 'B', 7);
                $this->Cell(22, 3.8, 'Montant', 0, 0, 'L');
                $this->SetFont('Courier', 'I', 6.5);
                $this->Cell(55, 3.8, format_number_simple($panier->prixunitaire *  $panier->quantite  - $panier->remise),  0, 1, 'L');

                $this->SetX(2);
                $this->Cell(76, 1, "",  'B', 1, 'L');
                // $this->Ln(1);
                if ($key != (count($paniers) - 1)) {
                }
            }

            $this->Ln(1);
            if ( $this->facture->frais ){
                $this->SetX(1);
                $this->SetFont('Courier', '', 7);
                $this->Cell(28, 4, "Frais de livraison", 0, 0, 'L');
                $this->Cell(33, 4, ' : ' .  number_three($this->facture->frais), 0, 1, 'L');
            }
            if ($this->facture->tva) {
                $this->SetX(1);
                $this->SetFont('Courier', '', 7);
                $this->Cell(28, 4, "TOTAL HT", 0, 0, 'L');
                $this->Cell(33, 4, ' : ' .  number_three($this->total['ht']), 0, 1, 'L');
                $this->SetX(1);
                $this->Cell(28, 4, utf8_decode("TVA (20%)"), 0, 0, 'L');
                $this->Cell(33, 4, ' : ' .  number_three($this->total['tva']), 0, 1, 'L');
                $this->SetX(1);
                $this->SetFont('Courier', 'B', 7);
                $this->Cell(28, 4, "TOTAL TTC", 0, 0, 'L');
                $this->Cell(33, 4, ' : ' .  number_three($this->total['ttc']), 0, 1, 'L');
            } else {
                $this->SetX(1);
                $this->SetFont('Courier', 'B', 7);
                $this->Cell(28, 4, "TOTAL ", 0, 0, 'L');
                $this->Cell(33, 4, ' : ' .  number_three($this->total['ht']), 0, 1, 'L');
            }
        }
    }
