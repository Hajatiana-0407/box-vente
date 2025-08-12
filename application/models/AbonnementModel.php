<?php 
class AbonnementModel extends CI_Model 
{
    public function __construct()
    {
        parent::__construct() ; 

    }

    public function register ( $data ) 
    {
        $this->db->insert('abonnement' , $data ) ; 
    }
 
    public function getAll( )
    {
        return $this->db->select('*')
                 ->from('abonnement')
                 ->order_by('idabonnement' ,'desc')
                 ->get()->result() ; 
        
    }
    public function delete( $id ){
        $this->db->where('idabonnement' , $id )->delete('abonnement') ; 
    }
    public function update( $data ,  $id ){
        $this->db->where('idabonnement' , $id )->update('abonnement' , $data ) ; 
    }

    public function search ( $debut , $fin    , $mot = ''){
        $this->db->select('*')->from('abonnement') ; 

        $debut = trim( $debut ) ; 
        $fin = trim( $fin ) ; 

        if (  $mot != ''){
            $mot = '%' . $mot . '%' ; 
            $this->db->where('contact like' , $mot ) ; 
            $this->db->or_where('nom like' , $mot ) ; 
            $this->db->or_where('dure like' , $mot ) ; 
        }

        
        if ( $debut != '' && $fin ==''){
            $debut = '%'. $debut . "%" ; 
            $this->db->where('date_debut like' , $debut ) ;
            
        }
        if ( $debut == '' && $fin !=''){
            $fin = '%'. $fin . "%" ; 
            $this->db->where('date_debut like' , $fin ) ; 
        }
        if ( $debut != '' && $fin !=''){
            $this->db->where('date_debut >=' , $debut ) ; 
            $this->db->where('date_debut <=' , $fin ) ; 
        }

         

        


        $data =  $this->db->get()->result() ;
        return $data ;

    }

    public function getAdmin( $contact )
    {
        // $contact = '%'. $contact . "%" ; 
        return $this->db->select('*')
                    ->from('admin')
                    ->where("REPLACE(teladmin, ' ', '') = ",str_replace(' ', '', $contact), FALSE)
                    ->get()->result() ;
    }

}