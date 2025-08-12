<?php

class AjoutProduitModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // *  pagination 

    public function get_count()
    {
        $data = $this->db->select('*')
            ->from('produit')
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
        return count($data);
    }

    public function get_authors($limit, $start)
    {
        $produits = $this->db->select('produit.* ')
            ->from('produit')
            // ->join('unite' , 'unite.idunite = produit.idunite' , 'left')
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->order_by('produit.idProduit', 'DESC')
            ->limit($limit, $start)
            ->get()->result();

        // Recuperation des unite
        foreach ($produits as $key => $produit) {
            $id = $produit->idProduit;
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idProduit', $id)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $produit->unites = $unites;
        }


        return $produits;
    }

    public function getProduitExel()
    {
        $produits = $this->db->select('*')
            ->from('produit')
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->order_by('idProduit', 'asc')
            ->get()->result();

        foreach ($produits as $key => $produit) {
            // selectionner les unite pour et ces prix
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idProduit', $produit->idProduit)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();

            if (count($unites) == 0) {
                // prix unique 
                $prix = $this->db->select('*')
                    ->from('prix')
                    ->where('idProduit', $produit->idProduit)
                    ->where('idadmin', $_SESSION['idadmin'])
                    ->order_by('idPrix', 'desc')
                    ->get()->result();


                if (count($prix) > 0) {
                    $produit->prix = [[
                        "prix" =>  $prix[0]->prixProduit,
                        "denomination" =>  '',
                    ]];
                } else {
                    $produit->prix = [[
                        "prix" =>  '',
                        "denomination" =>  '',
                    ]];
                }
            } else {
                $produit->prix = [];
                foreach ($unites as $key => $unite) {
                    $prix_unite = $this->db->select('*')
                        ->from('prix')
                        ->where('idProduit', $unite->idProduit)
                        ->where('idunite', $unite->idunite)
                        ->where('idadmin', $_SESSION['idadmin'])
                        ->order_by('idPrix', 'desc')
                        ->get()->result();

                    if (count($prix_unite) > 0) {
                        $produit->prix[] = [
                            "prix" => $prix_unite[0]->prixProduit,
                            "denomination" =>  $unite->denomination,
                        ];
                    } else {
                        $produit->prix = [[
                            "prix" =>  '',
                            "denomination" =>  '',
                        ]];
                    }
                }

            }
        }
        return $produits;
    }



    // *  pagination 

    public function insertProduit($data)
    {
        $this->db->insert('produit', $data);

        return $this->db->insert_id();
    }

    public function insertUnite($data)
    {
        $this->db->insert('unite', $data);
        return $this->db->insert_id();
    }

    public function verifRefProd($ref = '', $limit = '', $start = '')
    {
        $this->db->select('*')
            ->from('produit')
            // ->join('unite' , 'unite.idunite = produit.idunite' , 'left')
            ->where('produit.refProduit LIKE', $ref)
            ->where('produit.idadmin ', $_SESSION['idadmin'])
            ;


        if ( $ref != ''){
            $this->db->like('produit.refProduit', $ref) ; 
            $this->db->or_like('produit.designation', $ref) ; 
            $this->db->or_like('produit.designation', $ref) ;
            $this->db->or_like('produit.type', $ref) ;
            $this->db->or_like('produit.fiche', $ref) ; 
        } 
        if ($limit == '') {
            return $this->db->get()->result();
        }

        $this->db->limit($limit, $start);
        $produits = $this->db->get()->result();

        foreach ($produits as $key => $produit) {
            $id = $produit->idProduit;
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idProduit', $id)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $produit->unites = $unites;
        }

        return $produits ; 
    }

    public function verifDesProd($des)
    {
        $query = $this->db->select('*')
            ->from('produit')
            ->where('designation', $des)
            ->get();
        return $query->result();
    }

    public function getAllProduit()
    {
        $query = $this->db->select('*')
            ->from('produit')
            ->order_by('idProduit', 'DESC')
            ->get();
        return $query->result();
    }

    public function getProduitById($id)
    {
        $produits  = $this->db->select('*')
            ->from('produit')
            ->where('produit.idProduit', $id)
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->order_by('produit.idProduit', 'DESC')
            ->get()->result();
        // Recuperation des unites
        foreach ($produits as $key => $produit) {
            $id = $produit->idProduit;
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idProduit', $id)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $produit->unites = $unites;
        }

        return $produits ; 
    }

    public function is_used( $idProduit){
        // prix
        $prix = $this->db->select('*')
                ->from('prix')
                ->where('idProduit' , $idProduit )
                ->get()->result() ; 
        // appro 
        $appros = $this->db->select('*')
                    ->from('appro')
                    ->where('idProduit' , $idProduit )
                    ->get()->result() ; 
        if ( count( $prix ) > 0  || count($appros ) > 0 ){
            return true ;
        }else {
            return false ;
        }
    }

    public function deleteProduit($id)
    {
        $this->db->where('idProduit', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('produit');
    }
    public function deletePrix($id)
    {
        $this->db->where('idProduit', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('prix');
    }
    public function deleteVente($id)
    {
        $facture = $this->db->select('idfacture')
            ->from('panier')
            ->where('idProduit', $id)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();

        if (count($facture) > 0) {
            foreach ($facture as $key => $fact) {
                $idfact = $fact->idfacture;
                $this->db->where('idfacture', $idfact);
                $this->db->where('idadmin', $_SESSION['idadmin']);
                $this->db->delete('vente');
            }
        } else {
            $facture = '';
        }
        $this->db->where('idProduit', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('panier');
    }
    public function deletecommande($id)
    {
        $facture = $this->db->select('idcommande')
            ->from('cmpanier')
            ->where('idProduit', $id)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();

        if (count($facture) > 0) {
            foreach ($facture as $key => $fact) {
                $idfact = $fact->idcommande;
                $this->db->where('idcommande', $idfact);
                $this->db->where('idadmin', $_SESSION['idadmin']);
                $this->db->delete('commande');
            }
        } else {
            $facture = '';
        }
        $this->db->where('idProduit', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('cmpanier');
    }
    public function deleteProforma($id)
    {
        $facture = $this->db->select('idproforma')
            ->from('prpanier')
            ->where('idProduit', $id)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();

        if (count($facture) > 0) {
            foreach ($facture as $key => $fact) {
                $idfact = $fact->idproforma;
                $this->db->where('idproforma', $idfact);
                $this->db->where('idadmin', $_SESSION['idadmin']);
                $this->db->delete('proforma');
            }
        } else {
            $facture = '';
        }
        $this->db->where('idProduit', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('prpanier');
    }

    public function deleteTransfert( $id ){
        $this->db->where('idProduit' , $id )
                    ->where('idadmin' , $_SESSION['idadmin'])
                    ->delete('transfert') ; 
    }
    public function deleteAppro( $id ){
        // selectionner toute les appro lié 
        $appros = $this->db->select('*')
                ->from('appro')
                ->where('idProduit' , $id )
                ->where('idadmin' , $_SESSION['idadmin'])
                ->get()->result() ; 
        
        foreach ($appros as $key => $appro) {
            $idAppro = $appro->idAppro ; 
            // effacer toute les depense lier 
            $this->db->where('idAppro' , $idAppro )->where('idadmin' , $_SESSION['idadmin'])->delete('appro') ; 
        }
        $this->db->where('idProduit' , $id )->delete('appro') ; 
    }

    public function updateProduit($id, $data)
    {
        $this->db->where('idProduit', $id);
        $this->db->update('produit', $data);
        return $this->db->affected_rows() >  0;
    }
    public function updateUniteseuil( $idproduit ,  $idunte ){
        $this->db->where('idProduit' , $idproduit )->update('produit' , ['idunite' => $idunte ]) ; 
    }

    public function verifyIfProdExiste($id , $ref )
    {
        // $idadmin = $_SESSION['idadmin'];
        // $data = $this->db->query("SELECT * FROM produit  WHERE idProduit <> '$id' AND idadmin = '$idadmin'")->result();
        // return $data;


        return $this->db->select('*')
                ->from('produit')
                ->where('idProduit <>' , $id )
                ->where('refProduit' , $ref )
                ->where('idadmin' , $_SESSION['idadmin'])
                ->get()->result() ; 
    }

    public function lastProduit()
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM produit WHERE idadmin = '$idadmin' ORDER BY idProduit DESC")->result();
        return $data;
    }
    // * need 
    public function getProduitByRef($ref)
    {
        // $req = "SELECT * FROM ajoutproduit
        // INNER JOIN groups ON groups.id_group = ajoutproduit.id_group
        // WHERE refProduit LIKE '%$ref%'";
        // return $this->db->query($req)->result();
        $data = $this->db->select('*')
            ->from('produit ')
            ->where('refProduit =', $ref)
            ->where('idadmin =', $_SESSION['idadmin'])
            ->get();
        return $data->result();
    }

    public function rechercherUniteProd($ref)
    {
        $data = $this->db->select('*')
            ->from('produit')
            ->join('prix', 'prix.refProduit = produit.refProduit ', 'left')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('produit.refProduit', $ref)
            ->where('prix.refProduit', $ref)
            ->order_by('idPrix', 'DESC')
            ->get();
        return $data->result();
    }
    public function imprimer()
    {
        $query = $this->db->select('*')
            ->from('produit')
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->order_by('idProduit', 'DESC')
            ->get()->result();


        for ($i = 0; $i < count($query); $i++) {
            $ref = $query[$i]->refProduit;
            $prix = $this->db->select('*')
                ->from('prix')
                ->where('refProduit', $ref)
                ->where('idadmin', $_SESSION['idadmin'])
                ->order_by('idPrix ', 'desc')
                ->get()->result();
            $query[$i]->prix = $prix[0]->prixProduit;
        }
        return $query;
    }
}
