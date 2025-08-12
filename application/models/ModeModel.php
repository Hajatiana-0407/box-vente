<?php
class ModeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllMode($page = 1)
    {
        // PAGINATION 
        if($page == 0){
            $realOffset = $page * PAGINATION;
        }else{
            $realOffset = ($page - 1) * PAGINATION;
        }

        // $query = $this->db->query("SELECT * FROM modepaiement
        // ORDER BY idModePaiement DESC  WHERE idadmin =". $_SESSION['idadmin']  . " LIMIT " . PAGINATION ." OFFSET ".$realOffset);
        // return $query->result();

        $query = $this->db->select('*')
                        ->from('modepaiement')
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->limit( PAGINATION , $realOffset )
                        ->order_by('idModePaiement' , 'DESC')
                        ->get()->result() ;
        return $query ;  

    }
    public function AllMode($page = 1)
    {

        // $query = $this->db->query("SELECT * FROM modepaiement
        // ORDER BY idModePaiement DESC WHERE idadmin =" . $_SESSION['idadmin'] );
        // return $query->result();

        $query = $this->db->select('*')
                        ->from('modepaiement')
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->order_by('idModePaiement' , 'DESC')
                        ->get()->result() ;
        return $query ; 
    }

    public function countAllMode()
    {
        $query = $this->db->select('*')->from('modepaiement')
        ->order_by('idModePaiement', 'DESC')
        ->where('idadmin',  $_SESSION['idadmin'])
        ->get();
        return count($query->result());
    }

    public function insertMode($data)
    {
        $this->db->insert('modepaiement', $data);
    }

    public function deleteMode($id)
    {
        $this->db->where('idModePaiement ', $id);
        $this->db->delete('modepaiement');
    }
    public function editMode($id)
    {
        $this->db->where('idModePaiement', $id);
        $query = $this->db->get('modepaiement');
        return $query->execut();
    }


    // public function getModeByNomExceptId($nom, $id)
    // {
    //     $query = $this->db->select('*')->from('modepaiement')->where('denom', $nom)->where('idModePaiement !=', $id)->get();
    //     return $query->result();
    // }

    // public function getModeByNumeroExceptId($num, $id)
    // {
    //     $query = $this->db->select('*')->from('modepaiement')->where('numeroCompte', $num)->where('idModePaiement !=', $id)->get();
    //     return $query->result();
    // }









    public function getModeByNumero($num)
    {
        $query = $this->db->select('*')->from('modepaiement')->where('idadmin',  $_SESSION['idadmin'])->where('numeroCompte', $num)->get();
        return $query->result();
    }
    public function getModeByNom($nom)
    {
        $query = $this->db->select('*')->from('modepaiement')->where('idadmin',  $_SESSION['idadmin'])->where('denom', $nom)->get();
        return $query->result();
    }
    public function updateMode($data, $id)
    {
        $this->db->where('idModePaiement', $id);
        $this->db->update('modepaiement', $data);
        return $this->db->affected_rows() >  0;
    }
    public function getModeById($id)
    {
        $query = $this->db->select('*')->from('modepaiement')->where('idModePaiement', $id)->get();
        return $query->result();
    }



    public function verify_nom_mode($nom)
    {
        $data = $this->db->select("*")->from("modepaiement")->where('idadmin',  $_SESSION['idadmin'])->where("denom", $nom)->get()->result();
        return $data;
    }

    public function verify_num_mode($num)
    {
        $data = $this->db->select("*")->from("modepaiement")->where('idadmin',  $_SESSION['idadmin'])->where("numeroCompte", $num)->get()->result();
        return $data;
    }

    public function getALLModeWithCriteria($critere)
    {
        $data = $this->db->query("SELECT * FROM modepaiement WHERE idModePaiement  <> '$critere'")->result();
        return $data;
    }

    public function verify($id)
    {
        $data = $this->db->query("SELECT * FROM modepaiement 
        INNER JOIN facture ON facture.idModePaiement = modepaiement.idModePaiement
        INNER JOIN vente ON vente.idFacture = facture.idFacture 
        WHERE modepaiement.idModePaiement='$id'")->result();
        return $data;
    }

    public function testnom( $nom , $oldid ){
        return $this->db->select('*')
                ->from('modepaiement')
                ->where('denom' , $nom )
                ->where('idadmin',  $_SESSION['idadmin'])
                ->where('idModePaiement <>' , $oldid )
                ->get()->result() ; 
    }
    public function testnum( $num  , $oldid ){
        return $this->db->select('*')
                ->from('modepaiement')
                ->where('numeroCompte' , $num )
                ->where('idadmin',  $_SESSION['idadmin'])
                ->where('idModePaiement <>' , $oldid )
                ->get()->result() ; 
    }
}
