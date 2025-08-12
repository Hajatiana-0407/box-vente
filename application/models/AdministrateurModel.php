<?php 
class AdministrateurModel extends CI_Model 
{
    public function __construct()
    {
        parent::__construct() ; 
    }

    public function getAdminByIdentifiant ( $identifiant = '' ){
        return $this->db->select('*')
                ->from('administrateur')
                ->where('identifiant' , $identifiant ) 
                ->get()->result() ; 
    }

    public function getAllUser( ){ 
        return $this->db->select('*')
                ->from('admin')
                ->order_by('idAdmin' , 'desc')
                ->get()->result()  ; 
    }

    public function save ( $data ){
        $this->db->insert('tutoriel' , $data ) ; 
    }
    public function getAllTutoMal(){
        $data = $this->db->select('*')
                ->from('tutoriel')
                ->order_by('numero' , 'asc')
                ->order_by('langue' , 'asc')
                ->get()->result() ; 
        return $data ; 
    }
    public function getAllTutoFr(){
        $data = $this->db->select('*')
                ->from('tutoriel')
                ->where('langue' ,'Français')
                ->order_by('numero' , 'asc')
                ->get()->result() ; 
        return $data ; 
    }

    public function delete( $id ){
        $this->db->where('idtuto' , $id )->delete('tutoriel') ; 
    }

    public function getLastVideo ( $langue ){
        return $this->db->select('*')
                ->from('tutoriel')
                ->where('langue' , $langue )
                ->order_by('numero' , 'desc')
                ->get()->result() ; 
    }

    public function updatetuto ( $data , $id ){
        $this->db->where('idtuto' , $id )->update('tutoriel' , $data) ; 
    }

    public function getAllTuto_search( $query = '' ){
        $this->db->select('*')
        ->from('tutoriel') ;  
        if ( $query != ''){
            $this->db->where('titre like' , $query ) ;
            $this->db->or_where('numero like' , $query ) ;
            $this->db->or_where('langue like' , $query ) ;
        }


        $data = $this->db->order_by('numero' , 'asc')
        ->get()->result() ; 
        return $data ;  
    }
    public function getAllTutoMal_search( $query = '' ){
        $this->db->select('*')
        ->from('tutoriel')
        ->where('langue' ,'Malagasy') ; 


        if ( $query != ''){
            $this->db->where('titre like' , $query ) ;
            $this->db->or_where('numero like' , $query ) ;
        }


        $data = $this->db->order_by('numero' , 'asc')
        ->get()->result() ; 
        return $data ;  
    }


    public function getAllClient_search ( $debut , $fin , $mot ){
        $this->db->select('*')
                ->from('admin'); 
                
        if  ( $debut != '' && $fin == ''){
            $debut = '%'. $debut . "%" ; 
            $this->db->where('dateinscription like' , $debut ) ; 
        }  else if  ( $debut == '' && $fin != ''){
            $fin = '%'. $fin . "%" ; 
            $this->db->where('dateinscription like' , $fin ) ; 
        } 
        else if  ( $debut != '' && $fin != ''){
            $this->db->where('dateinscription >=' , $debut ) ; 
            $this->db->where('dateinscription <=' , $fin ) ; 
        } 


        if ( $mot != ''){
            $mot = '%' . $mot . '%'; 

            $this->db->where('mail Like ' , $mot ) ; 
            $this->db->or_where('nom Like ' , $mot ) ; 
            $this->db->or_where('prenom Like ' , $mot ) ; 
            $this->db->or_where('entreprise Like ' , $mot ) ; 
            $this->db->or_where('tel Like ' , $mot ) ; 
            $this->db->or_where('adresse Like ' , $mot ) ; 
        }
        $data = $this->db->order_by('idAdmin' , 'desc')
                    ->get()->result() ;
        return $data  ; 
    }

    public function changeprofil(  $data , $identifiant )
    {
        $this->db->where('identifiant' , $identifiant )->update('administrateur' , $data ) ; 
    }

    public function reactive( $idAdmin ){
        $this->db->where('idAdmin' , $idAdmin )->update('admin' , [ 'dateinscription' => date('Y-m-d H:i:s')]) ; 
    }

    public function updateMode( $mode, $id ){
        $this->db->where('idAdmin' , $id)
            ->update('administrateur' , ['mode' => $mode ]); 
    }

}