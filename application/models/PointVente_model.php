<?php

class PointVente_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // *  pagination 

    public function get_count()
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin' , $_SESSION['idadmin'])
            ->order_by('idPointVente', 'desc')
            ->get()->result();
        return count($query);
    }

    public function get_authors($limit, $start)
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin' , $_SESSION['idadmin'])
            ->limit($limit, $start)
            ->order_by('idPointVente', 'desc')
            ->get();
        return $query->result();
    }
    // *  pagination 


    public function ajoutPV($data)
    {
        // echo '<pre>'; var_dump( $data) ;  echo '</pre>' ; die ; 
        $this->db->insert('pointvente', $data);
    }


    public function recherchePV($keyword  = '', $limit  = '', $start = '')
    {

        $data = $this->db->select('*')
            ->from('pointvente')
            ->where('adressPv LIKE', $keyword)
            ->where('idadmin', $_SESSION['idadmin'])
            ->or_where('contactPv LIKE', $keyword);
        if ($limit == '')
            return $data->get()->result();
        else
            return $data->limit( $limit , $start)->get()->result();


        // var_dump('eee');
        // die;
    }

    public function editPv($data, $id)
    {
        $this->db->where('idPointVente', $id);
        $this->db->update('pointvente', $data);
        return $this->db->affected_rows() > 0;
    }

    public function deletePv( $id ){

        // point de vente 
        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $this->db->delete('pointvente') ; 
        
        // appro de vente 
        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $this->db->delete('appro') ; 
        
        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $this->db->delete('sous_produit') ; 
        
        
        // depense de vente 
        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $this->db->delete('depense') ; 

        // depense de vente 
        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $this->db->delete('user') ;
        
        // vente  et panier 
        $this->db->select('Facture')
        ->from('vente') ; 
        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $ventes = $this->db->get()->result() ; 
        foreach ( $ventes as $key => $vente ){
            $fact = $vente->Facture ; 
            $this->db->where('Facture' , $fact  ) ; 
            $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
            $this->db->delete('panier') ;
        }

        $this->db->where('idPointVente' , $id ) ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'] ) ; 
        $this->db->delete('vente') ;
    }


    public function getAllPv()
    {
        $data = $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'DESC')
            ->get();
        return $data->result();
    }

    public function verifAddress($address)
    {
        $query = $this->db->select('*')
                        ->from('pointvente')
                        ->where('adressPv' , $address)
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->get();
        return $query->result();
    }
    public function verifdenom($denom)
    {
        $query = $this->db->select('*')
                        ->from('pointvente')
                        ->where('denomination_pv' , $denom)
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->get();
        return $query->result();
    }

    public function verifContact($tel)
    {
        $query = $this->db->select('*')
                        ->from('pointvente')
                        ->where('contactPv' , $tel)
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->get();
        return $query->result();
    }

    public function verifyIfPvExiste($id)
    {
        $idadmin = $_SESSION['idadmin'] ; 
        $data = $this->db->query("SELECT * FROM pointvente WHERE idPointVente <> '$id' AND idadmin = '$idadmin'")->result();
        return $data;
    }
    public function getPvByid($id)
    {
        $idadmin = $_SESSION['idadmin'] ; 
        $data = $this->db->query("SELECT * FROM pointvente WHERE idPointVente = '$id' AND idadmin = '$idadmin '")->result();
        return $data;
    }
}
