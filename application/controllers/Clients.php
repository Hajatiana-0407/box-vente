<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->bd = $this->load->database();
        $this->load->model("ClientsModel", "Clients_model");
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper('string');
    }



    public function index()
    {
        $this->jail();

        // echo '<pre>' ;
        // var_dump( $_SESSION ) ; 
        // echo '</pre>' ; die ; 

        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'client';
        $config["total_rows"] = $this->Clients_model->get_count();
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
        $datapag['client'] = $this->Clients_model->get_authors($config["per_page"], $start);
        // * pagination * // 


        // echo '<pre>' ; 
        // var_dump( $datapag ) ; 
        // echo '</pre>' ; die ; 

        $data['title'] = 'Ajout de Client';
        $data['css'] = 'client.css';
        $js['js'] = 'client.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['client' => true]);
        $this->load->view('templates/tete');
        $this->load->view('clients', ['data' => $datapag]);
        $this->load->view('templates/footer', $js);
    }

    public function verify_client_js()
    {
        $num = '';
        if (isset($_POST['numero'])) {
            $num = trim(strip_tags($this->input->post('numero')));
        };
        $mail = '';
        if (isset($_POST['email'])) {
            $mail = trim(strip_tags($this->input->post('email')));
        };

        if ($mail != '') {
            $data = $this->Clients_model->verify_client_js($num, $mail);
        } else {
            $data = $this->Clients_model->verify_client_js_num($num);
        }

        if (count($data) == 0) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $data[0]
            ]);
        }
    }

    public function registerClient()
    {

    $nom = isset($_POST['nom']) ? strip_tags(trim($_POST['nom'])) : '';
    $prenom = isset($_POST['prenom']) ? strip_tags(trim($_POST['prenom'])) : '';
    $adress = isset($_POST['adress']) ? strip_tags(trim($_POST['adress'])) : '';
    $numero = isset($_POST['numero']) ? my_trim($_POST['numero']) : '';
    $mail_ = isset($_POST['email']) ? my_trim($_POST['email']) : '';

    $nif = isset($_POST['nif']) ? my_trim($_POST['nif']) : '';
    $stat = isset($_POST['stat']) ? my_trim($_POST['stat']) : '';
    $raison = isset($_POST['r_social']) ? my_trim($_POST['r_social']) : '';


        $iduser = '';
        $idadmin =  0;
        if (isset($_SESSION['idadmin'])) {
            $idadmin = $_SESSION['idadmin'];
        }
        if (isset($_SESSION['id_user'])) {
            $iduser = $_SESSION['id_user'];
        }

        // GESTION DES FICHIERS
        $upload_dir = FCPATH . 'public/upload/clients/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $cin_recto_path = '';
        $cin_verso_path = '';
        $image_profil_path = '';

        if (isset($_FILES['cin_recto']) && $_FILES['cin_recto']['error'] == 0) {
            $ext = pathinfo($_FILES['cin_recto']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('cin_recto-') . '.' . $ext;
            $dest = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['cin_recto']['tmp_name'], $dest)) {
                $cin_recto_path = 'public/upload/clients/' . $filename;
            }
        }
        if (isset($_FILES['cin_verso']) && $_FILES['cin_verso']['error'] == 0) {
            $ext = pathinfo($_FILES['cin_verso']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('cin_verso-') . '.' . $ext;
            $dest = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['cin_verso']['tmp_name'], $dest)) {
                $cin_verso_path = 'public/upload/clients/' . $filename;
            }
        }
        if (isset($_FILES['image_profil']) && $_FILES['image_profil']['error'] == 0) {
            $ext = pathinfo($_FILES['image_profil']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('profil-') . '.' . $ext;
            $dest = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['image_profil']['tmp_name'], $dest)) {
                $image_profil_path = 'public/upload/clients/' . $filename;
            }
        }

        $data  = [
            "nomClient" => $nom,
            "prenomClient" => $prenom,
            "adresseClient" => $adress,
            "telClient" => $numero,
            "emailClient" => $mail_,
            "nif" => $nif,
            "stat" => $stat,
            "r_social" => $raison,
            "idadmin" => $idadmin,
            "idUser" => $iduser,
            "cin_recto" => $cin_recto_path,
            "cin_verso" => $cin_verso_path,
            "image_profil" => $image_profil_path,
        ];


        $num = $this->Clients_model->getclientByNumero($numero);

        $email = $this->Clients_model->getclientByMail($mail_);

        $confirm_mail = true;
        foreach ($email as $mail) {
            if ($mail->emailClient != '') {
                $confirm_mail = false;
            }
        }



        if (isset($_POST['page_'])) {
            if (count($num) == 0 && $confirm_mail == true) {
                $this->Clients_model->insertClients($data);
                $this->session->set_flashdata('success', 'true');
            } else {
                if (count($num) > 0) {
                    $this->session->set_flashdata('num', 'Ajout réussie');
                } elseif ($confirm_mail == false) {
                    $this->session->set_flashdata('mail', 'Ajout réussie');
                }
            }
            if (isset($_POST['page_'])) {
                $this->session->set_flashdata('vente_client', 'Ajout réussie');
                redirect('vente');
            }
        } else {
            if (count($num) > 0) {
                $this->session->set_flashdata('num', 'Ajout réussie');
            } elseif ($confirm_mail == false) {
                $this->session->set_flashdata('mail', 'Ajout réussie');
            } else {
                $this->Clients_model->insertClients($data);
                $this->session->set_flashdata('success', 'Ajout réussie');
            }
        }
        redirect('client');
    }

    public function validationClient()
    {
        $numero = strip_tags(trim($this->input->post('numero')));
        $nif = strip_tags(trim($this->input->post('nif')));
        $stat = strip_tags(trim($this->input->post('stat')));

        $tel = $this->Clients_model->verify_num_clients($numero);

        $response = ['success' => true];

        if (count($tel) > 0) {
            $response['numeroInsertExiste'] = true;
            $response['success'] = false;
        }

        echo json_encode($response);
    }

    private function getclients()
    {
        $clients = $this->Clients_model->getALLclients();
        return $clients;
    }

    public function getclient($id)
    {
        $clients = $this->Clients_model->editClient($id);
        echo json_encode($clients);
    }

    public function fetchClient()
    {
        $num = $this->input->post('client');
        $client = $this->Clients_model->getClientByNum($num);

        if (count($client) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $client[0],
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => [],
            ]);
        }
    }

    public function editClient()
    {
        $id = $this->input->post('idClient_modif');
        $data = array(
            'nomClient' => trim($this->input->post('nom_modif')),
            'prenomClient' => trim($this->input->post('prenom_modif')),
            'adresseClient' => trim($this->input->post('adresse_modif')),
            'telClient' => my_trim($this->input->post('numero_modif')),
            'emailClient' => my_trim($this->input->post('email_modif')),
            'stat' => my_trim($this->input->post('stat_modif')),
            'nif' => my_trim($this->input->post('nif_modif')),
            'r_social' => my_trim($this->input->post('r_social_modif')),
            'idClient ' => $id
        );

        $this->Clients_model->updateclient($data, $id);
        $this->session->set_flashdata('edit', 'Ajout réussie');

        if (isset($_POST['page_'])) {
            $this->session->set_flashdata('vente_client', 'ok');
            redirect('vente ');
        } else {
            redirect('client');
        }
    }

    public function donnerclient()
    {
        $id = $this->input->post('client');
        $data = $this->Clients_model->getclientById($id);

        echo json_encode($data[0]);
    }

    public function deleteclient()
    {
        $id = $this->input->post('id');

        $res = $this->Clients_model->verifyIfClientInUse($id);

        $data = $this->Clients_model->delete($id);

        echo json_encode([
            'success' => true,
            'error' => '',
            'data' => $data,
        ]);

        $this->session->set_flashdata('delete', 'Ajout réussie');
        $this->session->set_flashdata('vente_client', 'ok');
    }


    public function rechercheClient()
    {

        if (isset($_POST['page_'])) {
            $keyword = strip_tags(trim($_POST['recherche']));
            $res = $this->Clients_model->searchClients($keyword, '', '');

            echo json_encode(['data' => $res]);
        } else {
            $keyword = strip_tags(trim($_GET['recherche']));
            $_POST['post'] = $keyword;
            // * pagination * // 
            $config = array();
            $config["base_url"] = base_url() . 'rechercheClient';
            $config["total_rows"] = count($this->Clients_model->searchClients($keyword, '', ''));
            $config["per_page"] = PAGINATION;
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
            $datapag['client'] = $this->Clients_model->searchClients($keyword, $config["per_page"], $start);
            // * pagination * // 



            $data['title'] = 'Ajout de Client';
            $data['css'] = 'client.css';
            $js['js'] = 'client.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['client' => true]);
            $this->load->view('templates/tete');
            $this->load->view('clients', [
                'data' => $datapag,
                'post' => $_POST['post']
            ]);
            $this->load->view('templates/footer', $js);
        }
    }

    public function verifiClient()
    {
        $email = strip_tags(trim($this->input->post('emailModif')));
        $numero = strip_tags(trim($this->input->post('numeroModif')));
        $old = $this->input->post('old');
        $data = $this->Clients_model->verify_client($numero);

        $dataAll = $this->Clients_model->getALLClientWithCriteria($old);
        $tab = [];
        for ($i = 0; $i < count($dataAll); $i++) {
            array_push($tab, $dataAll[$i]->telClient);
            array_push($tab, $dataAll[$i]->emailClient);
        }
        if (in_array($numero, $tab)) {
            echo json_encode(
                ['numero' => true]
            );
        } elseif (in_array($email, $tab) && $email  != '') {
            echo json_encode(
                ['email' => true]
            );
        } else {
            echo json_encode(
                ['success' => true]
            );
        }
    }

    public function rechercherClient()
    {
        $numClient = htmlspecialchars(my_trim($_POST['numClient']));
        $client = $this->Clients_model->getClientByNum($numClient);

        if (empty($client)) {
            echo json_encode(['success' => false, 'data' => '',]);
        } else {
            echo json_encode(['success' => true, 'data' => $client[0],]);
        }
    }

    public function rechercherClientForFacturation()
    {
        $numClient = htmlspecialchars(my_trim($_POST['numClient']));
        $client = $this->Clients_model->getClientByNumForFacturation($numClient);

        if (empty($client)) {
            echo json_encode(['success' => false, 'data' => '',]);
        } else {
            echo json_encode(['success' => true, 'data' => $client[0],]);
        }
    }

    public function getAllClient()
    {
        $clients = $this->Clients_model->getALLclients();

        echo json_encode([
            'data' => $clients
        ]);
    }
}
