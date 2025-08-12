<?php
class CommandeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProduit($reference) {
        return $this->db->select('*')
                ->from('produit')
                ->where('idadmin' , $_SESSION['idadmin'])
                ->where('refProduit' , $reference )
                ->get()->result() ; 
    }

    public function getLastFacture( ){
        return $this->db->select('*')
                ->from('commande')
                ->where('idadmin' , $_SESSION['idadmin'])
                ->order_by('idcommande' , 'desc')
                ->get()->result() ; 
    }

    public function insertcommande( $data ){
        $this->db->insert('commande',  $data ) ; 
        return $this->db->insert_id() ; 
    }

    public function insertPanier( $data ){
        $this->db->insert_batch('cmpanier', $data ) ; 
    }


    public function getFature( $cmfacture = '' ){
        $commande = $this->db->select('*')
                ->from('commande')
                ->join('fournisseur' , 'fournisseur.tel_fournisseur = commande.tel_fournisseur' , 'left')
                ->where('commande.cmfacture' , $cmfacture )
                ->where('commande.idadmin' , $_SESSION['idadmin'])
                ->where('fournisseur.idadmin' , $_SESSION['idadmin'])
                ->get()->result() ; 
        if ( count( $commande ) > 0  ){
            $commande = $commande[0] ; 
            $idcommande = $commande->idcommande ;
            $paniers = $this->db->select('*')
                    ->from('cmpanier')
                    ->join('produit' , 'produit.idProduit = cmpanier.idProduit' , 'left')
                    ->join('unite' , 'unite.idunite = cmpanier.idunite' , 'left')
                    ->where('cmpanier.idadmin' , $_SESSION['idadmin'])
                    ->where('cmpanier.idcommande' , $idcommande )
                    ->get()->result() ; 

            $commande->paniers = $paniers ; 
            return $commande ; 
        }else {
            return [] ; 
        }
    }
}
