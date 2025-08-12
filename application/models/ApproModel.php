<?php

class ApproModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }




    // ********************** Utile ************************* // 
    public function getProduitByRef($ref)
    {
        $data = $this->db->select('*')
            ->from('produit')
            ->where('refProduit', $ref)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $data->result();
    }

    public function insertAppro($data)
    {
        $this->db->insert('appro', $data);
        return $this->db->insert_id();
    }
    public function insert_batch($data)
    {
        $this->db->insert_batch('appro', $data);
    }

    public function commande_recue($idcommande)
    {
        $this->db->where('idcommande', $idcommande)->update('commande', ['recue' => 1]);
    }

    public function teste_commande($idcommande)
    {
        return $this->db->select('*')
            ->from('depense')
            ->where('idcommande', $idcommande)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }


    public function get_count()
    {
        $this->db->select('*')
            ->from('appro')
            ->where('appro.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            // si il'y a une filtre par point de vente 
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        return   $this->db->order_by('idAppro', 'desc')->count_all_results();
    }
    public function get_authors($page = 1)
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $this->db->select('appro.* , pointvente.* , produit.* , unite.*  , fournisseur.* , pv_1 ,  pv_2 ')
            ->from('appro')
            // jointure du transfert
            ->join('transfert', 'transfert.idtransfert = appro.idtransfert', 'left')
            ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
            ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
            // jointure du transfert
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
            ->join('produit', 'produit.idProduit = appro.idProduit', 'left')
            ->join('unite', 'unite.idunite = appro.idunite', 'left')
            ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
            ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left')
            // ->where('unite.idadmin', $_SESSION['idadmin'])
            // ->where('produit.idadmin', $_SESSION['idadmin'])
            // ->where('appro.idadmin', $_SESSION['idadmin'])
            // ->where('pointvente.idadmin', $_SESSION['idadmin'])
        ;
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }
        $this->db->where('appro.idadmin', $_SESSION['idadmin']);
        return  $this->db->order_by('idAppro', 'desc')
            ->limit(PAGINATION, $realOffset)
            ->get()->result();
    }

    public function searchDate($page = 1, $debut = '', $fin = '', $mot = '', $count = false)
    {

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        // $this->db->select('*')
        //     ->from('appro')
        //     ->join('pointvente', 'pointvente.idPointVente  = appro.idPointVente', 'left')
        //     ->join('unite', 'unite.idunite  = appro.idunite', 'left')
        //     ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
        //     ->join('produit', 'produit.idProduit  = appro.idProduit', 'left')
        //     ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
        //     ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left')
        //     ->where('appro.idadmin', $_SESSION['idadmin'])
        //     ->where('unite.idadmin', $_SESSION['idadmin'])
        //     ->where('pointvente.idadmin', $_SESSION['idadmin']);

        // // user
        // if (isset($_SESSION['pv'])) {
        //     $this->db->where('appro.idPointVente', $_SESSION['pv']);
        // }

        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $this->db->select('appro.* , pointvente.* , produit.* , unite.* , commande.* , fournisseur.* , pv_1 ,  pv_2 ')
            ->from('appro')

            // jointure du transfert
            ->join('transfert', 'transfert.idtransfert = appro.idtransfert', 'left')
            ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
            ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
            // jointure du transfert


            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
            ->join('produit', 'produit.idProduit = appro.idProduit', 'left')
            ->join('unite', 'unite.idunite = appro.idunite', 'left')
            ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
            ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left')
            // ->where('unite.idadmin', $_SESSION['idadmin'])
            // ->where('produit.idadmin', $_SESSION['idadmin'])
            // ->where('appro.idadmin', $_SESSION['idadmin'])
            // ->where('pointvente.idadmin', $_SESSION['idadmin'])
        ;
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }
        $this->db->where('appro.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            // $mot = '%' . $mot .'%' ; 
            $this->db->like('produit.refProduit', $mot);
            $this->db->or_like('produit.designation', $mot);
            $this->db->or_like('unite.denomination', $mot);
            $this->db->or_like('appro.couleur', $mot);
            $this->db->or_like('appro.numero', $mot);
            $this->db->or_like('appro.imei1', $mot);
            $this->db->or_like('appro.imei2', $mot);
            $this->db->or_like('pointvente.denomination_pv', $mot);
            $this->db->or_like('commande.cmfacture', $mot);
            $this->db->or_like('fournisseur.nom_entr', $mot);
        }

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('dateAppro LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('dateAppro LIKE', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('dateAppro >=', $debut);
            $this->db->where('dateAppro <=', $fin);
        }

        if (!$count) {
            $this->db->limit(PAGINATION, $start);
        }
        $q = $this->db->order_by('idAppro', 'desc')->get();
        $query = $q->result();

        if ($count) {
            return count($query);
        }
        return $query;
    }


    public function deleteAppro($id)
    {
        $this->db->where('idAppro ', $id);
        $this->db->delete('appro');
    }
    public function deleteDepense($id)
    {
        $this->db->where('idAppro ', $id);
        $this->db->delete('depense');
    }



    public function getAllMat()
    {
        $q = $this->db->select('*')
            ->from('produit')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idProduit ')
            ->get();
        return $q->result();
    }

    public function depenseAppro($montant = '', $idpv = '', $idcommande = '', $idAppro = '')
    {
        $data = [
            'raison' => 'Approvisionnement',
            'montant' => $montant,
            'idadmin' => $_SESSION['idadmin'],
            'idPointVente' => $idpv,
            'idcommande' => $idcommande,
            'idAppro' => $idAppro,
        ];
        $this->db->insert('depense', $data);
    }

    public function verifyNumserie($numero)
    {
        return $this->db->select('*')
            ->from('appro')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('numero', $numero)
            ->get()->result();
    }
    public function verifyImei($imei)
    {
        return $this->db->select('*')
            ->from('appro')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('imei1', $imei)
            ->or_where('imei2', $imei)
            ->get()->result();
    }
    public function getAllNumero($idProduit, $idPoinvente)
    {
        $this->db->select('*')
            ->from('appro')
            ->join('produit', 'appro.idProduit = produit.idProduit', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->where('produit.idProduit', $idProduit);
        if ($idPoinvente != '') {
            $this->db->where('appro.idPointVente', $idPoinvente);
        }
        $appros = $this->db->get()->result();

        $non_vendues = [];
        foreach ($appros as $key => $appro) {
            $panier = $this->db->select('*')
                ->from('panier')
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idProduit', $idProduit)
                ->where('numero', $appro->numero)
                ->get()->result();
            // transefert 
            $tansfert = $this->db->select('*')
                ->from('transfert')
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idProduit', $idProduit)
                ->where('numero', $appro->numero)
                ->where('idPointVente_source', $idPoinvente)
                ->get()->result();
            if (count($panier) == 0 && count($tansfert) == 0) {
                $non_vendues[] = $appro;
            } else if ($idPoinvente == '') {
                // pour le proforma 
                $non_vendues[] = $appro;
            }
        }

        return $non_vendues;
    }
    public function getProduitByNumero($numero, $idPoinvete)
    {
        $this->db->select('*')
            ->from('appro')
            ->join('produit', 'appro.idProduit = produit.idProduit', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->where('appro.numero', $numero);
        if (isset($_SESSION['pv'])) {
            if ($idPoinvete != ' ')
                $this->db->where('appro.idPointVente', $_SESSION['pv']);
        } else {
            if ($idPoinvete != '')
                $this->db->where('appro.idPointVente', $idPoinvete);
        }
        $appros =  $this->db->get()->result();

        // transefert 
        $tansfert = $this->db->select('*')
            ->from('transfert')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('numero', $numero)
            ->where('idPointVente_source', $idPoinvete)
            ->get()->result();
        if (count($tansfert) == 0) {
            return $appros;
        }
        else  if ($idPoinvete != ' ') {
            // proforma 
            return $appros;
        }
        return [];
    }

    // ********************** Utile ************************* // 
}
