<?php
class Reception extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReceptionModel' , 'reception') ; 
    }

    public function index( ){

        $this->jail() ; 


        $assets['title'] = 'Reception';
        $assets['css'] = 'reception.css';
        $js['js'] = 'reception.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['reception' => true]);
        $this->load->view('templates/tete');
        $this->load->view('reception');
        $this->load->view('templates/footer', $js);
    }

    public function search(){

        $recherche = '' ; 
        if ( isset( $_POST['recherche']) && $_POST['recherche'] != ''){
            $recherche = trim( strip_tags( $_POST['recherche'] )); 
        }

        $datas = $this->reception->search( $recherche )  ; 

        $pointdeventes = $this->reception->pointvente( ) ; 



        $_POST['numero'] = $recherche ; 



        $assets['title'] = 'Reception';
        $assets['css'] = 'reception.css';
        $js['js'] = 'reception.js';
        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['reception' => true]);
        $this->load->view('templates/tete');
        $this->load->view('reception' , [
            'datas' => $datas ,
            'pv' => $pointdeventes
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function getUnite()
    {
        $idProduit = '' ;
        if ( isset( $_POST['idProduit']) &&  $_POST['idProduit'] != ''){
            $idProduit = trim( strip_tags( $_POST['idProduit'])) ; 
        }

        $datas = $this->reception->getUnite( $idProduit ) ; 
        if ( count( $datas )> 0 ){
            echo json_encode([
                'success' => true ,
                'datas' => $datas 
            ]) ; 
        }else {
            echo json_encode([
                'success' => false ,
                'datas' => $datas 
            ]) ; 
        }
    }
}