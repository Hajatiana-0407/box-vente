<?php 
class Unite extends CI_Controller 
{
    public function __construct( )
    {
        parent::__construct() ; 
        $this->load->model('UniteModel' , 'unite') ; 
    }

    public function index(){
        $assets['title'] = 'Unité' ; 
        $assets['css'] = 'unite.css' ; 
        $assets['js'] = 'unite.js' ; 


        // $unites = $this->unite->getAllUnite() ; 


        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('unite');
        $this->load->view('templates/footer', $assets);
    }

    public function getProduit() 
    {

        $reference = '' ; 
        if ( isset(  $_POST['reference'] ) &&  $_POST['reference'] != null ){
            $reference  = trim( strip_tags( $_POST['reference'])) ; 
        }
        $produit =  $this->unite->getProduit( $reference ) ; 

        if ( count( $produit ) > 0 ){
            echo json_encode( [
                'success' => true ,
                'data' => $produit 
            ]) ; 
        } else {
            echo json_encode( [
                'success' => false  ,
            ]) ; 
        }

    }
}