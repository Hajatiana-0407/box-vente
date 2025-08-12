<?php 
class Utility extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct() ; 
    }

    public function session( ){
        if ( isset( $_SESSION['user_type'])){
            echo json_encode([
                'success' => true 
            ]) ; 
        }
        else {
            echo json_encode([
                'success' => false 
            ]) ; 
        }
    }

    public function clavier (){
        if ( isset( $_SESSION['clavier'])){
            unset($_SESSION['clavier']);
            $this->session->set_userdata('clavier_inactive' , true ) ; 
        }else {
            $_SESSION['clavier'] = true ;
            $this->session->set_userdata('clavier_active' , true ) ; 
        }
    }
}