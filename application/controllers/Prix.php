<?php

use PhpParser\Node\Expr\PostDec;

class Prix extends CI_Controller
{

    public $table  = 'prix';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PrixModel', 'p_model');
        $this->load->model('AjoutProduitModel', 'ajout_m');
        $this->load->model('ApproModel', 'appro');
        $this->load->model('UniteModel', 'unite');
    }

     

    public function prix()
    {
        $this->jail();
        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'prix';
        $config["total_rows"] = $this->p_model->get_count();
        $config["per_page"] = PAGINATION;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);


        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datapag["links"] = $this->pagination->create_links();

        if ((int)$page == 0) {
            $start = (int)$page * (int)$config["per_page"];
        } else {
            $start = ((int)$page - 1) * (int)$config["per_page"];
        }
        $datapag['prix'] = $this->p_model->get_authors($config["per_page"], $start);

        // echo '<pre>' ;
        // var_dump( $datapag['prix']) ; 
        // echo '</pre>' ; die  ; 

        // * pagination * // 

        $mat = $this->appro->getAllMat();
        $this->jail();
        $data['title'] = 'Prix';
        $data['css'] = 'prix.css';
        $js['js'] = 'prix.js';


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['prix' => true]);
        $this->load->view('templates/tete');
        $this->load->view('prix', [
            'data' => $datapag,
            'mat' => $mat,
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function registerPrix()
    {
        $reference = '';
        if (isset($_POST['reference']) && $_POST['reference'] != '') {
            $reference = strip_tags(trim($this->input->post('reference')));
        }

        $designation = '';
        if (isset($_POST['designation']) && $_POST['designation'] != '') {
            $designation = strip_tags(trim($this->input->post('designation')));
        }

        $idproduit = '';
        if (isset($_POST['idproduit']) && $_POST['idproduit'] != '') {
            $idproduit = trim(strip_tags($_POST['idproduit']));
        }

        // Gerer les groupes pour l'identification
        $groupe = $this->p_model->getLastgroupe( ); 
        if ( $groupe != ''){
            $groupe = $groupe + 1  ; 
        }else {
            $groupe = 1  ;  
        }

        if (isset($_POST['idUnite']) && $_POST['prixUnite']) {
            $idUnites = $_POST['idUnite'];
            $prixUnites = $_POST['prixUnite'];

            if (count($idUnites) == count($prixUnites)) {
                

                $data = [];

                for ($i = 0; $i < count($idUnites); $i++) {
                    $idunite = $idUnites[$i];
                    $prix = $prixUnites[$i];

                    $mydata = [
                        'idunite' => $idunite ,
                        'idProduit' => $idproduit , 
                        'groupe' => $groupe,
                        'prixProduit' => $prix,
                        'idadmin' => $_SESSION['idadmin'],
                        'dateAjoutPrix' => date("Y/m/d H:i:s"),
                    ];

                    $data[] = $mydata ; 
                }
                if ( count( $data ) > 0 ){
                    $this->p_model->insert_bacth( $data ) ; 
                    $this->session->set_flashdata('success', 'ok');
                }
            } else {
                // il y a une champ vide 
                $this->session->set_flashdata('erreur', 'Veuillez remplir tous les champs.');
            }
        }
        else {
            $prix_produit = '' ; 
            if ( isset( $_POST['prix_produit']) && $_POST['prix_produit'] !=''){
                $prix_produit = $_POST['prix_produit']; 
            }

            if ( $prix_produit != ""){
                $mydata = [
                    'idunite' => 0 ,
                    'idProduit' => $idproduit , 
                    'groupe' => $groupe ,
                    'prixProduit' => $prix_produit,
                    'idadmin' => $_SESSION['idadmin'],
                    'dateAjoutPrix' => date("Y/m/d H:i:s"),
                ];

                $this->p_model->insertPrix( $mydata ) ;
                $this->session->set_flashdata('success', 'ok'); 
            }
        }
        redirect('prix');
    }

    public function rechercherProd()
    {
        $ref = trim(strip_tags($this->input->post('ref')));
        $result =  $this->p_model->verifRefProd($ref);
        if (count($result) > 0) {
            // Prendre les unités du produit 
            $unites =  $this->unite->getUniteByIdProduit($result[0]->idProduit);

            $data = [
                'success' => true,
                'produit' => $result[0],
                'unites' => $unites
            ];

            echo json_encode($data);
        } else {
            $data = [
                'error' => true,
                'data' => $result[0]
            ];
            echo json_encode($data);
        }
    }

    public function deletePrix()
    {
        $idProduit = $this->input->post('idProduit');
        $groupe = $this->input->post('groupe');

        $data = $this->p_model->deleteItem($idProduit  , $groupe );

        $this->session->set_userdata('delete', 'ok');

        echo json_encode([
            'success' => true,
            'data' => $data,
        ]);

    }


    public function recherchePrix()
    {
        $keyword = strip_tags(trim($_GET['recherche']));
        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'recherchePrix';
        $config["total_rows"] = count($this->p_model->searchPrix($keyword, '', ''));
        $config["per_page"] = PAGINATION;
        // $config["uri_segment"] = 2;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datapag["links"] = $this->pagination->create_links();

        if ((int)$page == 0) {
            $start = (int)$page * (int)$config["per_page"];
        } else {
            $start = ((int)$page - 1) * (int)$config["per_page"];
        }
        $datapag['prix'] = $this->p_model->searchPrix($keyword, $config["per_page"], $start);
        // * pagination * // 


        // $prix = $this->p_model->searchPrix($keyword);
        $data['post'] = $keyword;

        $this->form_validation->set_rules('recherche', 'Recherche', 'required');

        $mat = $this->appro->getAllMat();

        $data['title'] = 'Prix';
        $data['css'] = 'prix.css';
        $js['js'] = 'prix.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['prix' => true]);
        $this->load->view('templates/tete');
        $this->load->view('prix', [
            'data' =>  $datapag,
            'mat' => $mat
        ]);
        $this->load->view('templates/footer', $js);

        $_SERVER['PHP_SELF'] == 'prix';
    }

    // ****************************************************************** //
    
    // ****************************************************************** //
}
