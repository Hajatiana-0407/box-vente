<?php

class VenteModel extends CI_Model
{
    public function insertVente($data)
    {
        $this->db->insert('vente', $data);
        return $this->db->insert_id();
    }

    public function insertPanier($data)
    {
        $this->db->insert_batch('panier', $data);
    }

    public function getLastFacture()
    {
        $idadmin = $_SESSION['idadmin'];
        return $this->db->query("SELECT * FROM vente WHERE idadmin = '$idadmin' ORDER BY Facture  DESC")->result();
    }

    public function getAllProd()
    {
        $query = $this->db->select('*')
            ->from('produit')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idProduit', 'DESC')
            ->get();
        return $query->result();
    }

    public function getAllVente()
    {
        $idadmin = $_SESSION['idadmin'];
        return $this->db->query("SELECT * FROM pointvente WHERE idadmin = '$idadmin' ORDER BY idPointVente   DESC")->result();
    }

    public function verifRefProd($ref)
    {
        $query = $this->db->select('*')
            ->from('produit')
            ->join('prix', 'prix.refProduit = produit.refProduit', 'inner')
            // ->join('sous_produit', 'sous_produit.refProduit = produit.refProduit', 'left')
            ->join('appro', 'appro.refProduit = produit.refProduit', 'inner')
            ->order_by('prix.idPrix ', 'desc')
            ->where('produit.refProduit', $ref)
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }

    public function selectPv()
    {
        $data = $this->db->select("*")
            ->from("pointvente")
            ->where('pointvente.idadmin', $_SESSION['idadmin'])
            ->order_by("idPointVente", "DESC")
            ->get()
            ->result();

        return $data;
    }

    public function getMatByRef($ref)
    {
        $data = $this->db->query("SELECT * FROM `materiel` INNER JOIN approvisionnement ON approvisionnement.idMateriel=materiel.idMateriel INNER JOIN prixmateriel ON prixmateriel.idMateriel=materiel.idMateriel WHERE materiel.refMateriel='$ref' ORDER BY prixmateriel.dateAjoutPrix DESC");

        $balance = $this->getBalance($ref);

        $reste = $balance[0]->reste;
        $data = $data->result();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->stock = $reste;
        }

        return $data;
    }

    public function insertData($data)
    {
        foreach ($data as $tableName => $tableData) {
            $this->db->insert($tableName, $tableData);
        }

        return $this->db->affected_rows() > 0;
    }

    public function getMP()
    {
        return $this->db->query("SELECT * FROM modepaiement ORDER BY denom ASC")->result();
    }

    public function getPV()
    {
        $idadmin = $_SESSION['idadmin'];
        return $this->db->query("SELECT * 
        FROM pointvente 
        WHERE idadmin = '$idadmin'
        ORDER BY adressePointVente ASC")
            ->result();
    }


    public function addToPanier($idMateriel, $qteMateriel, $prixMateriel, $montantTotal, $facture)
    {
        var_dump('to do 2');
        die;
        $this->db->query("INSERT INTO panier(idMateriel,quantite,prixMateriel,montantTotal,Facture) 
        VALUES('$idMateriel','$qteMateriel','$prixMateriel','$montantTotal','$facture')");
    }

    public function getIDFromRef($ref)
    {
        $data = $this->db->query("SELECT * FROM materiel WHERE refMateriel='$ref'")->result();
        return $data[0]->idMateriel;
    }

    public function getClientID($num)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM client WHERE telClient='$num' AND idadmin = '$idadmin'")->result();
        return $data[0]->idClient;
    }
    public function getClientByNum($num)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM client WHERE telClient='$num' AND idadmin = '$idadmin' ")->result();
        return $data;
    }

    public function getIDModePaiement($denom)
    {
        $data = $this->db->query("SELECT * FROM modepaiement WHERE denom='$denom'")->result();
        return $data[0]->idModePaiement;
    }

    public function getIDPointVente($addr)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM pointvente WHERE adressePointVente='$addr' AND idadmin = '$idadmin'")->result();
        return $data[0]->idPointVente;
    }


    public function verify_if_materiel_exist($ref)
    {
        return $this->db->select('*')->from('materiel')->where('refMateriel', $ref)->get()->result();
    }

    public function verify_if_materiel_exist_and_has_price($ref)
    {
        return $this->db->select('*')->from('materiel')->join('prixmateriel', 'prixmateriel.idMateriel=materiel.idMateriel', 'inner')->where('materiel.refMateriel', $ref)->get()->result();
    }

    public function verify_if_materiel_exist_and_has_price_and_stock($ref)
    {
        return $this->db->select('*')->from('materiel')->join('approvisionnement', 'approvisionnement.idMateriel=materiel.idMateriel', 'inner')->where('materiel.refMateriel', $ref)->get()->result();
    }

    public function getBalance($mat)
    {
        $all_materiel = $this->db->query("SELECT * FROM materiel WHERE refMateriel LIKE '%$mat%'")->result();
        for ($i = 0; $i < count($all_materiel); $i++) {
            $totalAppro = $this->db->query("SELECT SUM(qteAppro) AS sommeAppro 
            FROM approvisionnement WHERE idMateriel='" . $all_materiel[$i]->idMateriel . "'")->result();
            if ($totalAppro[0]->sommeAppro == null || $totalAppro[0]->sommeAppro == "") {
                $all_materiel[$i]->totalAppro = 0;
            } else {
                $all_materiel[$i]->totalAppro = $totalAppro[0]->sommeAppro;
            }


            $totalVente = $this->db->query("SELECT 
            SUM(quantite) AS sommeVente 
            FROM vente 
            WHERE idMateriel='" . $all_materiel[$i]->idMateriel . "'")->result();

            if ($totalVente[0]->sommeVente == null || $totalVente[0]->sommeVente == "") {
                $all_materiel[$i]->sommeVente = 0;
            } else {
                $all_materiel[$i]->sommeVente = $totalVente[0]->sommeVente;
            }


            $reste = $all_materiel[$i]->totalAppro - $all_materiel[$i]->sommeVente;
            $all_materiel[$i]->reste = $reste;
        }

        return $all_materiel;
    }


    public function getPvOfCom($id)
    {
        return $this->db->query("SELECT * FROM poste 
        INNER JOIN pointvente ON pointvente.idPointVente = poste.idPointVente 
        WHERE idCommercial = '$id' 
        ORDER BY dateAjoutPoste DESC")
            ->result();
    }

    public function getPvOfAdminPv($id)
    {
        return $this->db->query("SELECT * FROM admin_pv 
        INNER JOIN pointvente ON pointvente.idPointVente = admin_pv.idPointVente 
        WHERE admin_pv.idPointVente = '$id' ")
            ->result();
    }

    public function getFactureIfExist($fact)
    {
        return $this->db->select('*')
            ->from('vente')
            ->where('idFacture', $fact)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()
            ->result();
    }


    public function get_metadata($ref)
    {
        $data = $this->db->select("*")->from("offreconnexion")->where('nomOffre', $ref)->get()->result();

        if (count($data) > 0) {
            return "offre";
        } else {
            $data_prime = $this->db->select("*")->from("materiel")->where("refMateriel", $ref)->get()->result();
            if (count($data_prime) > 0) {
                return "materiel";
            } else {
                return "";
            }
        }
    }

    public function price($ref)
    {
        $data = $this->db->select('*')->from('offreconnexion')->join('prixoffreconnexion', 'prixoffreconnexion.idOffre=offreconnexion.idOffre', 'inner')->where("offreconnexion.nomOffre", $ref)->order_by('prixoffreconnexion.dateAjoutPrix', 'DESC')->get()->result();
        return $data;
    }


    public function getAllCapaciteWithDatasAndAvailable()
    {
        $data = $this->db->query("SELECT DISTINCT(capacite) FROM ligne")->result();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->infos = $this->db->select("*")->from("ligne")->where("capacite", $data[$i]->capacite)->get()->result();
        }

        return $data;
    }

    public function getLignes($page = 1)
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * LIMITE;
        } else {
            $realOffset = ($page - 1) * LIMITE;
        }

        $date = date('Y-m-d H:i:s');

        $query = $this->db->select('*')
            ->from('vente')
            ->where('dateHeureEcheance >=', $date)
            ->where('dateHeureEcheance !=', null)
            ->get();
        $resVente = $query->result();

        $refList = "";

        foreach ($resVente as $key => $res) {
            $refList .= $res->refLigne . ',';
        }
        $refList = trim($refList, ',');

        if (!empty($refList)) {
            $data = $this->db->query("SELECT * FROM ligne 
            WHERE 
            refLigne NOT IN ($refList) 
            LIMIT " . LIMITE . " OFFSET " . $realOffset)
                ->result();
        } else {

            $data = $this->db->query("SELECT * FROM ligne LIMIT " . LIMITE . " OFFSET " . $realOffset)
                ->result();
        }
        return $data;
    }

    public function countLignes()
    {
        $date = date('Y-m-d H:i:s');

        $query = $this->db->select('*')
            ->from('vente')
            ->where('dateHeureEcheance >=', $date)
            ->where('dateHeureEcheance !=', null)
            ->get();
        $resVente = $query->result();

        $refList = "";

        foreach ($resVente as $key => $res) {
            $refList .= $res->refLigne . ',';
        }
        $refList = trim($refList, ',');

        if (!empty($refList)) {
            $data = $this->db->query("SELECT * FROM ligne 
            WHERE 
            refLigne NOT IN ($refList) ")
                ->result();
        } else {

            $data = $this->db->query("SELECT * FROM ligne ")
                ->result();
        }
        return count($data);
    }



    public function getLigneWithKey($key, $page = 1)
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * LIMITE;
        } else {
            $realOffset = ($page - 1) * LIMITE;
        }

        $date = date('Y-m-d H:i:s');

        $query = $this->db->select('*')
            ->from('vente')
            ->where('dateHeureEcheance >=', $date)
            ->where('dateHeureEcheance !=', null)
            ->get();
        $resVente = $query->result();

        $refList = "";

        foreach ($resVente as $key => $res) {
            $refList .= $res->refLigne . ',';
        }
        $refList = trim($refList, ',');

        if (!empty($refList)) {
            $data = $this->db->query("SELECT * FROM ligne 
            WHERE 
            refLigne NOT IN ($refList) 
            AND (
                refLigne LIKE '%$key%' 
                OR numeroPuce LIKE '%$key%' 
                OR capacite LIKE '%$key%' ) 
            LIMIT " . LIMITE . " OFFSET " . $realOffset)
                ->result();
        } else {

            $data = $this->db->query(
                "SELECT * FROM ligne 
            WHERE 
                refLigne LIKE '%$key%' 
                OR numeroPuce LIKE '%$key%' 
                OR capacite LIKE '%$key%'
                LIMIT " . LIMITE . " OFFSET " . $realOffset
            )
                ->result();
        }
        return $data;
    }

    public function countLigneWithKey($key)
    {
        $date = date('Y-m-d H:i:s');

        $query = $this->db->select('*')
            ->from('vente')
            ->where('dateHeureEcheance >=', $date)
            ->where('dateHeureEcheance !=', null)
            ->get();
        $resVente = $query->result();

        $refList = "";

        foreach ($resVente as $key => $res) {
            $refList .= $res->refLigne . ',';
        }

        $refList = trim($refList, ',');

        if (!empty($refList)) {
            $data = $this->db->query("SELECT * FROM ligne 
            WHERE 
            refLigne NOT IN ($refList) 
            AND (
                refLigne LIKE '%$key%' 
                OR numeroPuce LIKE '%$key%' 
                OR capacite LIKE '%$key%' )")
                ->result();
        } else {

            $data = $this->db->query(
                "SELECT * FROM ligne 
        WHERE 
            refLigne LIKE '%$key%' 
            OR numeroPuce LIKE '%$key%' 
            OR capacite LIKE '%$key%'"
            )
                ->result();
        }
        return count($data);
    }


    public function updateSerial($pv, $id, $data)
    {
        $this->db->where('numero_serie', $id);
        $this->db->where('idPointVente', $pv);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->update('sous_produit', $data);
        return $this->db->affected_rows() > 0;
    }

    public function selectDate($num)
    {
        $q = $this->db->select('*')
            ->from('sous_produit')
            ->where('numero_serie', $num)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();

        return $q[0]->date_num;
    }





    public function getEcheanceLignes()
    {
        $query = $this->db->select('*')
            ->from('ligne')
            ->order_by('refLigne ', 'DESC')
            ->get();
        $resLigne = $query->result();

        $resultsFinal = [];

        foreach ($resLigne as $key => $ligne) {
            $query = $this->db->select('*')
                ->from('vente')
                ->join('ligne', 'ligne.refLigne=vente.refLigne')
                ->where('dateHeureEcheance !=', NULL)
                ->where('vente.refLigne', $ligne->refLigne)
                ->order_by('dateHeureEcheance', 'DESC')
                ->get();
            $resVente = $query->result();

            if (!empty($resVente)) {
                $resultsFinal[] = $resVente[0];
            }
        }

        return $resultsFinal;
    }

    public function getEcheanceLignesByDate($date)
    {
        $date = date_create($date);
        $date = date_format($date, "Y-m-d");

        $query = $this->db->select('*')
            ->from('ligne')
            ->order_by('refLigne ', 'DESC')
            ->get();
        $resLigne = $query->result();

        $resultsFinal = [];

        foreach ($resLigne as $key => $ligne) {
            $query = $this->db->select('*')
                ->from('vente')
                ->join('ligne', 'ligne.refLigne=vente.refLigne')
                ->where('dateHeureEcheance !=', NULL)
                ->where('dateHeureEcheance LIKE ', '%' . $date . '%')
                ->where('vente.refLigne', $ligne->refLigne)
                ->order_by('dateHeureEcheance', 'DESC')
                ->get();
            $resVente = $query->result();

            if (!empty($resVente)) {
                $resultsFinal[] = $resVente[0];
            }
        }

        return $resultsFinal;
    }

    public function getNumSerie($pv, $num)
    {
        $q = $this->db->select('*')
            ->from('sous_produit')
            ->join('prix', 'prix.refProduit = sous_produit.refProduit', 'inner')
            ->join('appro', 'appro.refProduit = sous_produit.refProduit', 'inner')
            ->order_by('prix.idPrix', 'DESC')
            ->where('sous_produit.numero_serie', $num)
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('sous_produit.idPointVente', $pv)
            ->get();
        return $q->result();
    }

    public function getAllNumseie($id)
    {
        $data =  $this->db->select('*')
            ->from('sous_produit')
            ->where('etat_vente', 'Non vendu')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idPointVente', $id)
            ->get()
            ->result();
        return $data;
    }
    public function getVenteNum($num, $id)
    {
        $data =  $this->db->select('*')
            ->from('sous_produit')
            ->join('produit', 'produit.refProduit = sous_produit.refProduit')
            ->join('prix', 'prix.refProduit = sous_produit.refProduit')
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('sous_produit.idPointVente', $id)
            ->where('sous_produit.numero_serie', $num)
            ->get()
            ->result();
        return $data;
    }

    public function getPrixPanier($ref)
    {
        $data = $this->db->select('*')
            ->from('prix')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('refProduit', $ref)
            ->order_by('idPrix', 'desc')
            ->get()->result();
        return $data[0];
    }

    public function get_the_pv($id = '')
    {
        $this->db->select('*')
            ->from('pointvente');
        if ($id != '') {
            $this->db->where('idPointVente', $id);
        }
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $data = $this->db->order_by('idPointVente ', 'desc')
            ->get()->result();
        return $data;
    }


    // *********** UTILE **************** //
    public function getStock($idProduit = 0, $id_pv = 0)
    {
        if ($id_pv == '') {
            $id_pv = 0;
        }
        $qte_min = 0;
        // pour l'appro 
        $this->db->select('*')
            ->from('appro')
            ->where('idProduit', $idProduit)
            ->where('idadmin', $_SESSION['idadmin']);
        if ($id_pv != 0) {
            $this->db->where('idPointVente', $id_pv);
        }
        $appros = $this->db->get()->result();
        $qte_appro = 0;
        foreach ($appros as $key => $appro) {
            $qte = $appro->quantite;
            if ($qte == '') {
                $qte = 1;
            }
            $qte_appro += $qte;
        }
        // pour les vente
        $ventes =  $this->db->select('*')
            ->from('panier')
            ->where('idProduit', $idProduit)
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idPointVente', $id_pv)
            ->group_by('idProduit')
            ->group_by('idfacture')
            ->get()->result();

        $qte_vente = 0;
        foreach ($ventes as $key => $vente) {
            $qte_vente += $vente->quantite;
        }

        // quantiter envoyer vers une autre pv

        $envois = $this->db->select('*')
            ->from('transfert')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idPointVente_source', $id_pv)
            ->where('idProduit', $idProduit)
            ->get()->result();
        $qte_min_envoie = 0;
        foreach ($envois as $key => $envoi) {
            $qte_min_envoie += $envoi->qunatite_transfert ;
        }

        // // quantiter recue d' une autre pv
        // $recues = $this->db->select('*')
        //     ->from('transfert')
        //     ->where('idadmin', $_SESSION['idadmin'])
        //     ->where('idPointVente_destination', $id_pv)
        //     ->where('idProduit', $idProduit)
        //     ->get()->result();
        // $qte_min_recue = 0;
        // foreach ($recues as $key => $recue) {
        //     $qte_min_recue += $recue->qte_min_transfert;
        // }

        $qte_min = $qte_appro - $qte_vente - $qte_min_envoie ;
        // + $qte_min_recue - $qte_min_envoie - $qte_min_vente;
        return $qte_min;
    }

    public function getLastgroupe($idProduit = '')
    {
        $this->db->select('groupe')
            ->from('prix')
        ;

        if ($idProduit != '') {
            $this->db->where('idProduit', $idProduit);
        }
        $groups =  $this->db->order_by('idPrix', 'desc')->get()->result();
        if (count($groups) > 0) {
            return $groups[0]->groupe;
        }
        return '';
    }

    public function getPrixUnite($idProduit)
    {
        
        $groups = $this->getLastgroupe($idProduit);
        if ($groups != '') {
            $prix = $this->db->select('*')
                ->from('prix')
                ->where('prix.idadmin', $_SESSION['idadmin'])
                ->where('prix.idProduit', $idProduit)
                ->where('prix.groupe ', $groups)
                ->get()->result();

            return $prix;
        } else {
            return [];
        }
    }

    public function unites($idProduit)
    {
        return $this->db->select('*')
            ->from('unite')
            ->where('idProduit', $idProduit)
            ->get()->result();
    }



    // *********** UTILE **************** //
}
