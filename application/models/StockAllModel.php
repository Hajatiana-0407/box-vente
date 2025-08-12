<?php
class StockAllModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
            ->group_by('appro.idProduit');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $appros =  $this->db->get()->result();



        return $appros;
    }

    /**
     * Stock
     *
     * @param array $approvisionnement
     * @return void
     */
    public function getStock($approvisionnement = [])
    {
        foreach ($approvisionnement as $key => $approv) {

            $idProduit = $approv->idProduit;
            $reference = $approv->refProduit;

            $type =  $approv->type;

            if ($type == 'telephone') {
                // reselectioner les appros
                $datas = $this->db->select('*')
                    ->from('appro')
                    ->where('idProduit', $idProduit)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->get()->result();


                $approv->quantite_stk = count($datas);

                // teste si les produist sont deja vendue  ou transferé
                foreach ($datas as $key => $data) {
                    // vente
                    $is_inpannier = $this->db->select('*')
                        ->from('panier')
                        ->where('numero', $data->numero)
                        ->where('idadmin', $_SESSION['idadmin'])
                        ->where('idPointVente', $data->idPointVente)
                        ->get()->result();

                    if (count($is_inpannier)) {
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

    /**
     * Filtre 
     *
     * @param string $type
     * @param string $filtre
     * @param string $page
     * @return void
     */
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
            ->group_by('appro.idProduit');
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
            ->group_by('appro.idProduit');
        // ->group_by('appro.idPointVente');
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
}
