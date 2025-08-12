<?php

class ProfilModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getTheConnected( $idadmin ){
        return $this->db->select('*')
                ->from('admin')
                ->where('idAdmin' , $idadmin )
                ->get()->result() ; 
    }
    public function getTheConnected_user( $iduser  ){
        return $this->db->select('*')
                ->from('user')
                ->where('idUser' , $iduser  )
                ->get()->result() ; 
    }


    public function change_mdp( $id  , $pass ){
        $this->db->where('idAdmin' , $id)->update('admin' , ['pass' => $pass ]) ; 
    }

    public function change_mdp_user( $id  , $pass ){
        $this->db->where('idUser' , $id)->where('idadmin' , $_SESSION['idadmin'])->update('user' , ['pass' => $pass ]) ; 
    }
    


    public function updateModeAdmin( $mode,  $id ){
        $this->db->where('idAdmin' , $id)
        ->update('admin' , ['mode' => $mode ]) ; 
    }
    
    public function updateModeUser( $mode, $id ){
        $this->db->where('idUser' , $id)
            ->update('user' , ['mode' => $mode ]); 
    }
    
    
    
    public function getAdminMail( $mail = ''){
        return $this->db->select('*')
                ->from('admin')
                ->where('idadmin' , $_SESSION['idadmin'])
                ->where('mail' , $mail )
                ->get()->result() ; 
    }
    public function getAdmin(){
        return $this->db->select('*')
                ->from('admin')
                ->where('idadmin' , $_SESSION['idadmin'])
                ->get()->result() ; 
    }
    public function getuser ( $id ){
        return $this->db->select('*')
                ->from('user')
                ->where('idadmin' , $_SESSION['idadmin'])
                ->where('idUser' , $id )
                ->get()->result() ; 
    }
    public function getuserMail( $mail = ''){
        return $this->db->select('*')
                ->from('user')
                ->where('idadmin' , $_SESSION['idadmin'])
                ->where('mail' , $mail )
                ->get()->result() ; 
    }

    public function updateAdminmail( $mail = '' ){
        $this->db->where('idadmin' , $_SESSION['idadmin'])->update('admin' , ['mail' => $mail ] ) ; 
    }
    public function updateUsermail( $mail = '' , $id = '' ){
        $this->db->where('idadmin' , $_SESSION['idadmin'])->where("idUser" , $id )->update('user' , ['mail' => $mail ]) ; 
    }


}
