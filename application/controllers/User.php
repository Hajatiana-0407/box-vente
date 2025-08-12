<?php

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'user');
        $this->load->model("VenteModel", "vente");
        $this->load->model('PointVente_model', 'pv');
    }
     
    public function index()
    {
        $this->jail();
        if ($_SESSION['user_type'] == 'admin') {
            $config = array();
            $config["base_url"] = base_url() . 'user';
            $config["total_rows"] = $this->user->get_count();
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
            $datapag['user'] = $this->user->get_authors($config["per_page"], $start);

            // echo '<pre>' ;
            // var_dump( $datapag['user']) ;
            // echo '</pre>' ; die  ; 
    
            $data['title'] = 'Utilisateur';
            $data['css'] = 'stock.css';
            $js['js'] = 'user.js';
    
            $pv = $this->pv->getAllPv();
            
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['user' => true]);
            $this->load->view('templates/tete');
            $this->load->view('user', [
                'data' => $datapag,
                'pv' => $pv
            ]); 
            $this->load->view('templates/footer', $js);
        } else {
            redirect('vente');
        }


        // Utility::set_notification( ) ; 

    }

    public function registerUser()
    {
        $nom = strip_tags(trim($_POST['nom']));
        $prenom = strip_tags(trim($_POST['prenom']));
        $adresse = strip_tags(trim($_POST['adresse']));
        $type = strip_tags(trim($_POST['typeUser']));
        $email = strip_tags(trim($_POST['email']));
        $pv = strip_tags(trim($_POST['pv']));
        $numero = strip_tags(trim($_POST['numero']));

        $idadmin = 0  ; 

        if ( isset( $_SESSION["idadmin"]) ) {
            $idadmin = $_SESSION['idadmin'] ; 
        }






        $mail = $this->user->verifMail($email);

        $num = $this->user->verifNum($numero);

        if (count($mail) > 0) {
            $this->session->set_flashdata('mail', 'Ajout réussie'); 
        } elseif (count($num) > 0) {
            $this->session->set_flashdata('num', 'Ajout réussie');
        } elseif (empty($pv)){
            $this->session->set_flashdata('adrres', 'Ajout réussie');
        } else {
            $data = [
                'nomUser' => $nom, 
                'prenomUser' => $prenom,
                'contact' => my_trim($numero),
                'adress' => $adresse,
                'typeUser' => $type,
                'mail' => $email,
                'idPointVente' => $pv,
                'pass' => hash_it('123456') , 
                'idadmin' => $idadmin
            ];
    
            $this->user->insertUser($data);
            $this->session->set_flashdata('ajout', 'Ajout réussie');
        }
        redirect('user') ;
    }

    public function deleteUser()
    {

        $this->jail() ; 
        $id = $this->input->post('id');
        $data = $this->user->deleteUser($id);

        echo json_encode([
            'success' => true,
        ]);

        $this->session->set_flashdata('delete', 'Ajout réussie');
    }

    public function DonnerUser()
    {
        $id = $this->input->post('id');
        $data = $this->user->getAllUserById($id);
        $pv = $this->pv->getAllPv() ; 
        echo json_encode([
            'success' => true , 
            'data' => $data ,
            'pv' => $pv 
        ]);
    }

    public function editUser()
    {
        $id = strip_tags(trim($_POST['id_modif']));
        $nom = strip_tags(trim($_POST['nom_modif']));
        $prenom = strip_tags(trim($_POST['prenom_modif']));
        $adresse = strip_tags(trim($_POST['adresse_modif']));
        $numero = strip_tags(trim($_POST['numero_modif']));
        $type = strip_tags(trim($_POST['type_modif']));
        $email = strip_tags(trim($_POST['email_modif']));
        $idPv_modif = strip_tags(trim($_POST['idPv_modif']));

        $data = [
            'nomUser' => $nom,
            'prenomUser' => $prenom,
            'contact' => my_trim($numero),
            'adress' => $adresse,
            'typeUser' => $type,
            'mail' => $email,
            'idPointVente' => $idPv_modif
        ];

        $this->user->updateUser($id, $data);
        $this->session->set_flashdata('edit', 'Ajout réussie');
        redirect('user') ;
    }

    public function verifUser()
    {
        $id = $this->input->post('id');
        $numero = strip_tags(trim($this->input->post('numero')));
        $email = strip_tags(trim($this->input->post('email')));

        $dataId = $this->user->verifyIfUserExiste($id);

        $tab = [];

        for ($i = 0; $i < count($dataId); $i++) {
            array_push($tab, $dataId[$i]->contact);
            array_push($tab, $dataId[$i]->mail);
        }

        $response = ['success' => true];
        if (in_array($numero, $tab)) {
            $response['numExiste'] = true;
            $response['success'] = false;
        }

        if (in_array($email, $tab)) {
            $response['mailExiste'] = true;
            $response['success'] = false;
        }
        echo json_encode($response);
    }

    public function rechercheUser()
    {
        if ($_SESSION['user_type'] == 'admin') {
            $keyword = strip_tags(trim($_GET['recherche'])) ;
            $_POST['post'] = $keyword;
            // * pagination * // 
            $config = array();
            $config["base_url"] = base_url() . 'rechercheUser';
            $config["total_rows"] = count($this->user->searchUser($keyword, '', ''));
            $config["per_page"] = PAGINATION;
            // $config["uri_segment"] = 2;
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'page';
            $config['reuse_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            $this->pagination->initialize($config);

            $page = ( isset($_GET['page'])) ? $_GET['page'] : 0;

            $datapag["links"] = $this->pagination->create_links();
            
            if ((int)$page == 0) {
                $start = (int)$page * (int)$config["per_page"];
            } else {
                $start = ((int)$page - 1) * (int)$config["per_page"];
            }
            $datapag['user'] = $this->user->searchUser($keyword, $config["per_page"], $start);
            // * pagination * // 


            $this->jail();
            $data['title'] = 'Utilisateur';
            $data['css'] = 'stock.css';
            $js['js'] = 'user.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['user' => true]);
            $this->load->view('templates/tete');
            $this->load->view('user',[
                'data' => $datapag ,
                'post' => $_POST['post'] , 
                'pv' => $this->vente->getAllVente()
            ]);
            $this->load->view('templates/footer', $js);
        } else {
            redirect('vente');
        }

    }

    public function mdpUser()
    {
        $id = $this->input->post('id');

        $data = array(
            'pass' => hash_it('123456'),
        );

        $this->user->mdpUser($id , $data);
        $this->session->set_userdata('reinitialiser', true );

        echo json_encode([
            'success' => true
        ]);
        
    }
}
