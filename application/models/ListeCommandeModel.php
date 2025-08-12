<?php

class ListeCommandeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // UTILITY
    public function getAllListe($page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }
        $idadmin = $_SESSION['idadmin'];
        $this->db->select('*');
        $this->db->from('commande')
            ->join('fournisseur', 'fournisseur.tel_fournisseur = commande.tel_fournisseur', 'left')
            ->where('commande.idadmin', $_SESSION['idadmin']) 
            ->where('fournisseur.idadmin', $_SESSION['idadmin']) 
            ; 
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $this->db->order_by('commande.idcommande', 'desc');

        $query = $this->db->get()->result();


        return $query;
    }


    public function search($debut = '', $fin = '', $mot = '', $page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('commande')
            ->join('fournisseur', 'fournisseur.tel_fournisseur = commande.tel_fournisseur', 'left')
            ->where('commande.idadmin', $_SESSION['idadmin'])
            ->where('fournisseur.idadmin', $_SESSION['idadmin']) ;

        if ($mot != '') {
            $this->db->like('commande.cmfacture', $mot);
            $this->db->or_like('commande.tel_fournisseur', $mot);
            $this->db->or_like('fournisseur.nom_entr', $mot);
        }


        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('commande.datecommande LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('commande.datecommande like', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('commande.datecommande >=', $debut);
            $this->db->where('commande.datecommande <=', $fin);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        $this->db->order_by('commande.cmfacture', 'desc')
            ->group_by('commande.cmfacture', 'desc');
        $query = $this->db->get()->result();


        return $query;
    }
    // UTILITY
















    public function getAllPv()
    {
        return $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'desc')
            ->get()->result();
    }

    

    public function getAllInfo($fac)
    {
        $q = $this->db->select('*')
            ->from('commande')
            ->where('cmfacture', $fac)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $q->result();
    }

    public function getTotal($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantTotal) as Tot FROM cmpanier WHERE  cmfacture = '$fac' AND idadmin = '$idadmin'")->result();
        return $total[0]->Tot;
    }
    public function getApayer($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $totalP = $this->db->query("SELECT SUM(montantPaye) as TotP FROM cmpanier WHERE  cmfacture = '$fac' AND idadmin = '$idadmin'")->result();
        return $totalP[0]->TotP;
    }

    public function getSumPrix($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(remise) as SommeR FROM cmpanier WHERE cmfacture = '$fac' AND refProduit = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->SommeR;
    }

    public function getSumQte($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(qteProduit) as Qte FROM cmpanier WHERE cmfacture = '$fac' AND refProduit = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->Qte;
    }

    public function getSumPayer($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantPaye) as payer FROM cmpanier WHERE cmfacture = '$fac' AND refProduit = '$ref' AND idadmin = '$idadmin'")->result();
        // var_dump ( $total[0]->payer ) ; die ; 
        return $total[0]->payer;
    }

    public function getFact($idcommande)
    {
        // LE VENTE 
        $commandes = $this->db->select('*')
            ->from('commande')
            ->join('fournisseur' , 'fournisseur.tel_fournisseur = commande.tel_fournisseur' , 'left')
            ->where('commande.idadmin', $_SESSION['idadmin'])
            ->where('commande.idcommande', $idcommande)
            ->where('fournisseur.idadmin', $_SESSION['idadmin']) 
            ->get()->result();
        // ALL PANIER 
        $paniers = $this->db->select('*')
            ->from('cmpanier')
            ->join('produit', 'produit.idProduit = cmpanier.idProduit', 'left')
            ->join('unite', 'unite.idunite = cmpanier.idunite', 'left')
            ->where('cmpanier.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('cmpanier.idcommande', $idcommande)
            ->get()->result();

        if (count($commandes) > 0) {
            $commandes = $commandes[0];
            $commandes->cmpanier = $paniers;
            return $commandes;
        } else {
            return [];
        }
    }

    public function getFacture($idcommande)
    {
        // $idadmin =  $_SESSION['idadmin'];
        // $data = $this->db->query("SELECT DISTINCT(idProduit) FROM cmpanier WHERE idcommande = '$idcommande'  AND idadmin = '$idadmin'")->result();


        // echo '<pre>' ;
        // var_dump( $data ) ; 
        // echo '</pre>' ; die ; 

        // $dataf = [];
        // for ($i = 0; $i < count($data); $i++) {
        //     $res =  $this->db->select('*')
        //         ->from('cmpanier')
        //         ->join('produit', 'produit.idProduit = cmpanier.idProduit', 'left')
        //         ->join('commande', 'commande.idcommande = cmpanier.idcommande', 'left')
        //         ->join('fournisseur', 'fournisseur.tel_fournisseur = commande.tel_fournisseur', 'left')
        //         ->where('cmpanier.idadmin', $_SESSION['idadmin'])
        //         ->where('produit.idadmin', $_SESSION['idadmin'])
        //         ->where('commande.idadmin', $_SESSION['idadmin'])
        //         ->where('cmpanier.idProduit', $data[$i]->idProduit)
        //         ->get()->result();
        //     $dataf[] = $res[0];
        // }

        // return $dataf;

        $commandes = $this->db->select('*')
            ->from('commande')
            ->where('commande.idcommande', $idcommande)
            ->where('commande.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        $paniers =  $this->db->select('*')
            ->from('cmpanier')
            ->join('produit' , 'produit.idProduit = cmpanier.idProduit' , 'left')
            ->join('unite' , 'unite.idunite = cmpanier.idunite' , 'left')
            ->where('cmpanier.idcommande', $idcommande)
            ->where('cmpanier.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        if (count($commandes) > 0) {
            $commandes = $commandes[0];
            $commandes->paniers = $paniers;

            // fournisseur 
            $fournisseur = $this->db->select('*')
                            ->from('fournisseur')
                            ->where('idadmin' , $_SESSION['idadmin'])
                            ->where('tel_fournisseur' , $commandes->tel_fournisseur )
                            ->get()->result() ;
            if ( count( $fournisseur ) > 0 ){
                $fournisseur = $fournisseur[0] ; 
                foreach ($fournisseur as $key => $value) {
                    $commandes->$key = $value ; 
                }

            }else {

            }

            // // prendre tous les unites de chaque produit 
            // foreach ($commandes->paniers as $key => $cmpanier) {
            //     $idProduit = $cmpanier->idProduit;
            //     $unites = $this->db->select('*')
            //         ->from('unite')
            //         ->where('idProduit', $idProduit)
            //         ->get()->result();

            //     $cmpanier->unites = $unites;
            // }
        }

        // echo '<pre>' ; 
        // var_dump( $commandes ) ; 
        // echo '</pre>' ; die ; 

        return $commandes;
    }

    // public function getFacture($ref , $refFacture)
    // {
    //     $res = $this->db->select( 'DISTINCT(cmpanier.refProduit) ' , '*' )
    //         ->from('cmpanier')
    //         ->join('produit', 'produit.refProduit = cmpanier.refProduit', 'left')
    //         ->join('commande', 'commande.cmfacture = cmpanier.cmfacture', 'left')
    //         ->join('fournisseur', 'fournisseur.tel_fournisseur = commande.tel_fournisseur', 'left')
    //         ->where('commande.cmfacture', $refFacture)
    //         ->get()->result();
    //     return $res;
    // }

    public function getUser($id)
    {
        $res = $this->db->select('*')
            ->from('user')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idUser', $id)->get()->result();
        return $res[0];
    }


    public function getGrandTotal($refFacture)
    {
        $res = $this->db->select_sum('montantPaye')
            ->from('cmpanier')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('cmfacture', $refFacture)
            ->get()->result();
        return $res;
    }

    public function getAllDetails($details)
    {
        $req = $this->db->select('*')
            ->from('cmpanier')
            ->join('commande', 'commande.cmfacture = cmpanier.cmfacture', 'left')
            ->join('produit', 'produit.refProduit = cmpanier.refProduit', 'left')
            ->join('prix', 'prix.refProduit = produit.refProduit', 'left')
            ->where('cmpanier.idadmin', $_SESSION['idadmin'])
            ->where('commande.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('cmpanier.cmfacture', $details[0]->cmfacture)
            ->get();
        return $req->result();
    }

    public function getFactureSelected($details)
    {
        $req = $this->db->select('*')
            ->from('cmpanier')
            ->join('commande', 'commande.cmfacture = cmpanier.cmfacture', 'left')
            ->join('produit', 'produit.refProduit = cmpanier.refProduit', 'left')
            ->join('prix', 'prix.refProduit = produit.refProduit', 'left')
            ->where('cmpanier.idadmin', $_SESSION['idadmin'])
            ->where('commande.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('cmpanier.cmfacture', $details)
            ->get();
        return $req->result();
    }

    public function getidCmfacture( $idcommande ){
        return $this->db->select('idcmfacture')
                ->from('cmpanier')
                ->where('idcommande' , $idcommande )
                ->get()->result() ; 
    }

    public function deleteCommande($idcommande)
    {
        $this->db->where('idcommande', $idcommande)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('commande');
    }
    public function deleteAppro($idcmfacture)
    {
        $this->db->where('idcmfacture', $idcmfacture)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('appro');
    }
    public function deleteDepense($idcommande)
    {
        $this->db->where('idcommande', $idcommande)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('depense');
    }
    public function deletePanier($idcommande)
    {
        $this->db->where('idcommande ', $idcommande)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('cmpanier');
    }

    
}
