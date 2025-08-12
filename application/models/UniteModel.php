<?php 
class UniteModel extends CI_Model 
{
    public function __construct()
    {
        parent::__construct() ; 
    }

    public function getAllUnite( ){
        $produits = $this->db->select('*')
                ->from('produit')
                ->where('idadmin' , $_SESSION['idadmin']) 
                ->order_by('idProduit' , 'desc')
                ->get()->result() ;
        foreach ($produits as $key => $produit) {
            $unites = $this->db->select('*')
                                ->from('unite')
                                ->where('idadmin' , $_SESSION['idadmin'])
                                ->where('') ; 
        } 
    }

    public function deleteByproduit( $id )
    {
        $this->db->where('idProduit' , $id )->delete('unite') ; 
    }

    public function deleteById( $id )
    {
        $this->db->where('idunite' , $id )->delete('unite') ; 
    }

    public function updateunite( $data , $id ){
        $this->db->where('idunite' , $id )->update('unite' , $data ) ; 
    }

    public function getIdlastUnite( $idproduit ){
        return $this->db->select('*')
                ->from('unite')
                ->where('idProduit' , $idproduit )
                ->where('idadmin' , $_SESSION['idadmin'] )
                ->order_by('idunite' , 'desc')
                ->get()->result() ; 
    }

    public function getUniteByIdProduit( $idproduit ){
        return $this->db->select('*')
                ->from('unite')
                ->where('idProduit' , $idproduit )
                ->where('idadmin' , $_SESSION['idadmin'] )
                ->order_by('idunite' , 'asc')
                ->get()->result() ; 
    }
    
    public function deletePrix( $idUnite ){
        $this->db->where('idunite' , $idUnite )
                ->delete('prix')  ; 
    }

    public function convertion( $idunite){
        // selectionner l'unite qui a comme parent notre idunite
        $data = $this->db->select('*')
                        ->from('unite')
                        ->where('idparent' , $idunite )
                        ->get()->result() ; 
        return $data ;           
    }
    
}