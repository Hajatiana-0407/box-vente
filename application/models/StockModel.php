<?php
class StockModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getAll_desc_description()
    {
        $query = $this->db->select('*')
            ->from('sous_produit')
            ->join('appro', 'appro.refProduit = sous_produit.refProduit', 'inner')
            ->join('pointvente ', 'pointvente.idPointVente = appro.idPointVente', 'inner')
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            // ->where('pointvente.idadmin' , $_SESSION['idadmin'])
            // ->where('appro.idadmin' , $_SESSION['idadmin'])
            ->group_by('sous_produit.refProduit')
            ->group_by('pointvente.idPointVente')
            ->order_by('appro.designation', 'desc')
            ->get()->result();

        for ($i = 0; $i < count($query); $i++) {
            $res = $this->db->select('*')
                ->from('sous_produit')
                ->where('sous_produit.idadmin', $_SESSION['idadmin'])
                ->where('sous_produit.refProduit', $query[$i]->refProduit)
                ->where('sous_produit.idPointVente', $query[$i]->idPointVente)
                ->where('sous_produit.etat_vente', 'Non vendu')
                ->get()
                ->result();
            $query[$i]->quatiter = count($res);
        }
        return $query;
    }

    public function getAllNum($idProduit = '',  $idPointVente = '', $page = '')
    {

        // ******************* panier 
        $this->db->select('*')
            ->from('panier')
            ->where('idProduit', $idProduit)
            ->where('idadmin', $_SESSION['idadmin']);
        if ($idPointVente != '') {
            $this->db->where('idPointVente', $idPointVente);
        }
        $paniers = $this->db->get()->result();

        // ******************* panier 
        $this->db->select('*')
            ->from('transfert')
            ->where('idProduit', $idProduit)
            ->where('idadmin', $_SESSION['idadmin']);
        if ($idPointVente != '') {
            $this->db->where('idPointVente_source', $idPointVente);
        }
        $transferts = $this->db->get()->result();


        $this->db->select('*')
            ->from('appro')
            ->join('produit', 'produit.idProduit = appro.idProduit', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->where('appro.idProduit', $idProduit);
        if ($idPointVente != '') {
            $this->db->where('appro.idPointVente', $idPointVente);
        }

        foreach ($paniers as $key => $panier) {
            $this->db->where('appro.numero <>', $panier->numero);
        }
        foreach ($transferts as $key => $transfert) {
            $this->db->where('appro.numero <>', $transfert->numero);
        }

        if ($page) {
            // LIMITE 
            if ((int)$page == 0) {
                $realOffset = (int)$page * PAGINATION;
            } else {
                $realOffset = ((int)$page - 1) * PAGINATION;
            }
            $this->db->limit(PAGINATION, $realOffset);
        }
        $datas = $this->db->get()->result();

        return $datas;
    }




    public function getAll_asc_description()
    {
        $query = $this->db->select('*')
            ->from('sous_produit')
            ->join('appro', 'appro.refProduit = sous_produit.refProduit', 'inner')
            ->join('pointvente ', 'pointvente.idPointVente = appro.idPointVente', 'inner')
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            // ->where('appro.idadmin' , $_SESSION['idadmin'])
            // ->where('pointvente.idadmin' , $_SESSION['idadmin'])
            ->group_by('sous_produit.refProduit')
            ->group_by('pointvente.idPointVente')
            ->order_by('appro.designation', 'asc')
            ->get()->result();

        for ($i = 0; $i < count($query); $i++) {
            $res = $this->db->select('*')
                ->where('sous_produit.idadmin', $_SESSION['idadmin'])
                ->from('sous_produit')
                ->where('sous_produit.refProduit', $query[$i]->refProduit)
                ->where('sous_produit.idPointVente', $query[$i]->idPointVente)
                ->where('sous_produit.etat_vente', 'Non vendu')
                ->get()
                ->result();
            $query[$i]->quatiter = count($res);
        }
        return $query;
    }

    public function getAll_asc_ref()
    {
        $query = $this->db->select('*')
            ->from('sous_produit')
            ->join('appro', 'appro.refProduit = sous_produit.refProduit', 'inner')
            ->join('pointvente ', 'pointvente.idPointVente = appro.idPointVente', 'inner')
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            // ->where('appro.idadmin' , $_SESSION['idadmin'])
            // ->where('pointvente.idadmin' , $_SESSION['idadmin'])
            ->group_by('sous_produit.refProduit')
            ->group_by('pointvente.idPointVente')
            ->order_by('appro.refProduit', 'asc')
            ->get()->result();

        for ($i = 0; $i < count($query); $i++) {
            $res = $this->db->select('*')
                ->from('sous_produit')
                ->where('sous_produit.idadmin', $_SESSION['idadmin'])
                ->where('sous_produit.refProduit', $query[$i]->refProduit)
                ->where('sous_produit.idPointVente', $query[$i]->idPointVente)
                ->where('sous_produit.etat_vente', 'Non vendu')
                ->get()
                ->result();
            $query[$i]->quatiter = count($res);
        }
        return $query;
    }
    public function getAll_desc_ref()
    {
        $query = $this->db->select('*')
            ->from('sous_produit')
            ->join('appro', 'appro.refProduit = sous_produit.refProduit', 'inner')
            ->join('pointvente ', 'pointvente.idPointVente = appro.idPointVente', 'inner')
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            // ->where('appro.idadmin' , $_SESSION['idadmin'])
            // ->where('pointvente.idadmin' , $_SESSION['idadmin'])
            ->group_by('sous_produit.refProduit')
            ->group_by('pointvente.idPointVente')
            ->order_by('appro.refProduit', 'desc')
            ->get()->result();

        for ($i = 0; $i < count($query); $i++) {
            $res = $this->db->select('*')
                ->from('sous_produit')
                ->where('sous_produit.idadmin', $_SESSION['idadmin'])
                ->where('sous_produit.refProduit', $query[$i]->refProduit)
                ->where('sous_produit.idPointVente', $query[$i]->idPointVente)
                ->where('sous_produit.etat_vente', 'Non vendu')
                ->get()
                ->result();
            $query[$i]->quatiter = count($res);
        }
        return $query;
    }

    public function getQTE($ref, $pv)
    {
        $idadmin = $_SESSION['idadmin'];
        $qte = $this->db->query("SELECT SUM(quantiter) as qte FROM appro WHERE refProduit='$ref' AND  idPointVente ='$pv' AND idadmin = '$idadmin'")->result();
        return $qte;
    }

    public function recherche($keyword)
    {
        $this->db->select('*')
            ->from('appro')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'inner')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            // ->where('pointvente.idadmin' , $_SESSION['idadmin'])
            ->where('appro.refProduit LIKE', $keyword)
            ->or_where('appro.designation LIKE', $keyword)
            ->or_where('appro.quantiter LIKE', $keyword)
            ->or_where('pointvente.adressPv LIKE', $keyword)
            ->order_by('appro.refProduit', 'DESC')->get();
        return $this->db->result();
    }

    public function vente()
    {
        $query = $this->db->select('*')
            ->from('vente')
            ->join('panier', 'panier.Facture = vente.Facture', 'right')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            // ->where('panier.idadmin' , $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }

    public function getAllNumSerie($ref, $pv)
    {
        $numseri = $this->db->select('*')
            ->from('sous_produit')
            ->where('sous_produit.idadmin', $_SESSION['idadmin'])
            ->where('refProduit', $ref)
            ->where('idPointVente', $pv)
            ->where('etat_vente', 'Non vendu')
            ->get();
        return $numseri->result();
    }



    // *****************
    public function getSousPr($date, $ref, $pv = '')
    {
        $this->db->select('*')
            ->from('sous_produit')
            ->where('idadmin', $_SESSION['idadmin']);
        if ($date != '')
            $this->db->where('date_num', $date);
        if ($pv != '') {
            $this->db->where('idPointVente', $pv);
        }
        $this->db->where('etat_vente', 'Non vendu');
        $q = $this->db->where('refProduit', $ref)
            ->get();
        return $q->result();
    }

    public function getSousPrMPaginer($ref, $date, $limite, $ofset, $pv = '')
    {
        $this->db->select('*')
            ->from('sous_produit')
            ->where('idadmin', $_SESSION['idadmin']);
        $this->db->where('etat_vente', 'Non vendu');
        if ($date != '')
            $this->db->where('date_num', $date);
        if ($pv != '')
            $this->db->where('idPointVente', $pv);
        $q = $this->db->where('refProduit', $ref)
            ->limit($limite, $ofset)
            ->get();
        return $q->result();
    }


    // UTITY
    public function getStock($approvisionnement = [])
    {
        foreach ($approvisionnement as $key => $approv) {

            $idProduit = $approv->idProduit;
            $reference = $approv->refProduit;
            $id_pv = $approv->idPointVente;

            $type =  $approv->type;

            if ($type == 'telephone') {
                // reselectioner les appros
                $datas = $this->db->select('*')
                    ->from('appro')
                    ->where('idProduit', $idProduit)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->where('idPointVente', $id_pv)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->get()->result();

                $approv->quantite_stk = count($datas);



                // teste si les produits sont deja vendue ou transferé
                foreach ($datas as $key => $data) {
                    // vente
                    $is_inpanier = $this->db->select('*')
                        ->from('panier')
                        ->where('numero', $data->numero)
                        ->where('idadmin', $_SESSION['idadmin'])
                        ->where('idPointVente', $data->idPointVente)
                        ->get()->result();

                    if (count($is_inpanier)) {
                        $approv->quantite_stk--;
                    }

                    // recue
                    // les transfert recue sont deja enregistrer dans l'approvisionnement 
                    // envoyer
                    $transfer = $this->db->select('*')
                        ->from('transfert')
                        ->where('numero', $data->numero)
                        ->where('idadmin', $_SESSION['idadmin'])
                        ->where('idPointVente_source', $data->idPointVente)
                        ->get()->result();
                    if (count($transfer)) {
                        $approv->quantite_stk--;
                    }
                }
            } else {
                // pour l'appro 
                $appros = $this->db->select('*')
                    ->from('appro')
                    ->where('idProduit', $idProduit)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->where('idPointVente', $id_pv)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->get()->result();
                $quantite_appro = 0;

                foreach ($appros as $key => $appro) {
                    $quantite_appro += $appro->quantite;
                }

                // pour les vente
                $ventes =  $this->db->select('panier.quantite')
                    ->from('panier')
                    ->where('idProduit', $idProduit)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->where('idPointVente', $id_pv)
                    ->group_by('idProduit')
                    ->group_by('idfacture')
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->get()->result();

                $quantite_vente = 0;
                foreach ($ventes as $key => $vente) {
                    $quantite_vente += $vente->quantite;
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
                    $qte_min_envoie += $envoi->qunatite_transfert;
                }

                $approv->quantite_stk = $quantite_appro  - $quantite_vente - $qte_min_envoie;



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
            }
        }


        return $approvisionnement;
    }

    public function getAll($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('')
            ->from('appro')
            ->join('produit', 'produit.idProduit = appro.idProduit', 'left')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idProduit')
            ->group_by('appro.idPointVente');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }
        if ($page != '') {
            $this->db->limit(PAGINATION , $start );
        }
        $appros =  $this->db->get()->result();
        


        return $appros;
    }
    public function getAll_seuil($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $this->db->select('a.idPointVente, 
                           p.*, 
                           COALESCE(SUM(a.quantite), 0) AS total_appro, 
                           COALESCE(v.total_vendu, 0) AS total_vendu  , pv.*');
        $this->db->from('appro a');

        // Jointure avec la table produit
        $this->db->join('produit p', 'a.idProduit = p.idProduit', 'left');
        $this->db->join('pointvente  pv', 'a.idPointVente = pv.idPointVente', 'left');

        // Sous-requête pour obtenir les ventes par point de vente
        $this->db->join('(SELECT idProduit, idPointVente, SUM(quantite) AS total_vendu FROM panier GROUP BY idProduit, idPointVente) v', 'p.idProduit = v.idProduit AND a.idPointVente = v.idPointVente', 'left');

        // Regrouper par idPointVente et idProduit
        $this->db->group_by('a.idPointVente, p.idProduit');
        $this->db->where('a.idadmin', $_SESSION['idadmin']);

        $this->db->having('(total_appro - total_vendu) <= p.seuil_min');
        $this->db->having('(total_appro - total_vendu) >=  0');

        // par pv 
        if (isset($_SESSION['pv'])) {
            $this->db->where('a.idPointVente', $_SESSION['pv']);
        }

        // Appliquer une limite pour la pagination
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        // Exécution de la requête
        $appro = $this->db->get()->result();
        return $appro;
    }
    public function getAll_search_seuil($keyword = '', $page = '')
    {

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $this->db->select('a.idPointVente, 
                           p.*, 
                           COALESCE(SUM(a.quantite), 0) AS total_appro, 
                           COALESCE(v.total_vendu, 0) AS total_vendu  , pv.*');
        $this->db->from('appro a');

        // Jointure avec la table produit
        $this->db->join('produit p', 'a.idProduit = p.idProduit', 'left');
        $this->db->join('pointvente  pv', 'a.idPointVente = pv.idPointVente', 'left');

        // Sous-requête pour obtenir les ventes par point de vente
        $this->db->join('(SELECT idProduit, idPointVente, SUM(quantite) AS total_vendu FROM panier GROUP BY idProduit, idPointVente) v', 'p.idProduit = v.idProduit AND a.idPointVente = v.idPointVente', 'left');

        // Regrouper par idPointVente et idProduit
        $this->db->group_by('a.idPointVente, p.idProduit');
        $this->db->where('a.idadmin', $_SESSION['idadmin']);

        if ($keyword != '') {
            $this->db->like('p.refProduit', $keyword);
            $this->db->or_like('p.designation', $keyword);
            $this->db->or_like('p.fiche', $keyword);
            $this->db->or_like('pv.adressPv', $keyword);
        }

        // Appliquer une limite pour la pagination
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        // Exécution de la requête
        $appro = $this->db->get()->result();



        return $appro;
    }
    public function getAll_search($keyword = '', $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('')
            ->from('appro')
            ->join('produit', 'produit.idProduit = appro.idProduit', 'left')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idProduit')
            ->group_by('appro.idPointVente');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }


        if ($keyword != '') {
            $this->db->like('produit.refProduit', $keyword);
            $this->db->or_like('produit.designation', $keyword);
            $this->db->or_like('produit.fiche', $keyword);
            $this->db->or_like('pointvente.adressPv', $keyword);
        }

        $appro =  $this->db->get()->result();

        return $appro;
    }
    public function getAll_filtre($type  = '', $filtre = '', $page = '')
    {

        $the_filter = $filtre[$type];

        $count_pv = count($this->pv_stock());

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('')
            ->from('appro')
            ->join('produit', 'produit.idProduit = appro.idProduit', 'left')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idProduit')
            ->group_by('appro.idPointVente');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        if ($type == 'reference') {
            $this->db->order_by('produit.refProduit', $the_filter);
        } else if ($type == 'designation') {
            $this->db->order_by('produit.designation', $the_filter);
        } else if ($type == 'pv') {
            if (is_array($the_filter) && count($the_filter) > 0) {
                if (!isset($the_filter[0])) {
                    $_where = true;
                    for ($i = 1; $i <= $count_pv; $i++) {
                        if (isset($the_filter[$i])) {
                            if ($_where) {
                                $this->db->where('pointvente.idPointVente', $the_filter[$i]);
                                $_where = false;
                            } else {
                                $this->db->or_where('pointvente.idPointVente', $the_filter[$i]);
                            }
                        }
                    }
                }
            }
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $appro =  $this->db->get()->result();

        return $appro;
    }
    public function getAll_filtre_seuil($type  = '', $filtre = '', $page = '')
    {

        $the_filter = $filtre[$type];

        $count_pv = count($this->pv_stock());

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $this->db->select('a.idPointVente, 
        p.*, 
        COALESCE(SUM(a.quantite), 0) AS total_appro, 
        COALESCE(v.total_vendu, 0) AS total_vendu  , pv.*');
        $this->db->from('appro a');

        // Jointure avec la table produit
        $this->db->join('produit p', 'a.idProduit = p.idProduit', 'left');
        $this->db->join('pointvente  pv', 'a.idPointVente = pv.idPointVente', 'left');

        // Sous-requête pour obtenir les ventes par point de vente
        $this->db->join('(SELECT idProduit, idPointVente, SUM(quantite) AS total_vendu FROM panier GROUP BY idProduit, idPointVente) v', 'p.idProduit = v.idProduit AND a.idPointVente = v.idPointVente', 'left');

        // Regrouper par idPointVente et idProduit
        $this->db->group_by('a.idPointVente, p.idProduit');
        $this->db->where('a.idadmin', $_SESSION['idadmin']);


        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        if ($type == 'reference') {
            $this->db->order_by('p.refProduit', $the_filter);
        } else if ($type == 'designation') {
            $this->db->order_by('p.designation', $the_filter);
        } else if ($type == 'pv') {
            if (is_array($the_filter) && count($the_filter) > 0) {
                if (!isset($the_filter[0])) {
                    $_where = true;
                    for ($i = 1; $i <= $count_pv; $i++) {
                        if (isset($the_filter[$i])) {
                            if ($_where) {
                                $this->db->where('pv.idPointVente', $the_filter[$i]);
                                $_where = false;
                            } else {
                                $this->db->or_where('pv.idPointVente', $the_filter[$i]);
                            }
                        }
                    }
                }
            }
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $appro =  $this->db->get()->result();

        return $appro;
    }
    public function pv_stock()
    {
        $this->db->select('*')
            ->from('appro')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION["idadmin"]);
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        $this->db->group_by('appro.idPointVente');

        return $this->db->get()->result();
    }

    // UTIliTY
}
