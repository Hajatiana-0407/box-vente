function condition()
    {
        // Empêcher le header et le footer pour cette page
        $this->noHeaderFooter = true;

        // Ajouter la page
        $this->AddPage();

        // Ajouter le contenu spécifique de la page (conditions générales de garantie)
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Conditions générales de garantie'), 0, 1, 'C');

        // Espacement
        $this->Ln(10);

        // Texte principal
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, utf8_decode("Top-Tech Generation garantit que son Produit ne présentera aucun défaut de matériaux et de fabrication dans les conditions normales d'utilisation pendant la durée de Garantie. (10 Mois pour les smartphones)."), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("Cette garantie concerne uniquement les vices et défauts liés à la conception ou à la fabrication des composants de l’appareil et non les accessoires qui l'accompagnent (Chargeur, housse, câble...)"), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("La présente garantie ne s'applique pas dans les cas suivants :"), 0, 'L');
        $this->Ln(5);

        // Liste des exclusions
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, utf8_decode("             -    Dommages imputables à des intempéries ou catastrophes naturelles (Foudre, tornades, inondations, incendies...)"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Négligence et mal manipulation"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Altérations ou modifications d'une partie du Produit"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Dommages causés par l'utilisation de produits non issu de chez Top-Tech Generation"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Dommages causés par une source d'alimentation ou une tension inadéquate"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Accidents (chocs, noyade...)"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Dommages causés à la suite d'une intervention de l’utilisateur (y compris mise à jour et améliorations)"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Non-respect des instructions ayant trait à l'utilisation du produit"), 0, 'L');

        $this->Ln(10);

        // Comment obtenir un service de garantie ?
        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(0, 5, utf8_decode("Comment obtenir un service de garantie ?"), 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, utf8_decode("Si un problème matériel survient, veuillez faire une réclamation dans notre boutique . Notre service technique procédera d'une vérification de 48h. Si la réclamation reçue est valide pendant la période de garantie, on pourra :"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Réparer le Produit sans frais"), 0, 'L');
        $this->MultiCell(0, 5, utf8_decode("             -    Remplacer le Produit défectueux selon la disponibilité des stocks par un autre Produit neuf (même modèle) (ou occasion à voir cas par cas)"), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("Des preuves d'achats comportant les numéros de série (Facture) seront demandées lors de la réclamation."), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("La société ne pourra être tenue pour responsable des dommages liés à la perte de données."), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("Il est de votre devoir de faire des copies de sauvegarde de toutes vos données, aussi bien personnelles que des logiciels téléchargés."), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("Les données, logiciels ou autres informations sur l'équipement sont susceptibles d'être perdus ou supprimés au cours du processus de service."), 0, 'L');
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode("A la réception, le client est tenu de bien vérifier la conformité et l’état du produit livré. A cet effet toutes remarques ou tous problèmes constatés dès la première utilisation doivent être signalés immédiatement à la société."), 0, 'L');

        $this->noHeaderFooter = true;
    }