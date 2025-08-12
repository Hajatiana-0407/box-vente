<?php

class PointVente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PointVente_model', 'pv_model');
    }

     

    public function index()
    {
        $this->jail();
        /// * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'pointVente';
        $config["total_rows"] = $this->pv_model->get_count();
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

        $datapag['lieu'] = $this->pv_model->get_authors($config["per_page"], $start);

        // * pagination * // 

        $data['title'] = 'Points de Vente:';
        $data['css'] = 'pointVente.css';
        $js['js'] = 'pointVente.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['pv' => true]);
        $this->load->view('templates/tete');
        $this->load->view('pointVente', [
            'data' => $datapag
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function ajoutPV()
    {
        $address = '' ; 
        if ( isset( $_POST['adresse']) && $_POST['adresse'] != '') 
            $address = strip_tags(trim($this->input->post('adresse')));

        $contact = '' ; 
        if ( isset( $_POST['contact']) && $_POST['contact'] != '') 
            $contact =  strip_tags(trim($this->input->post('contact')));

        $denomination = '' ; 
        if ( isset( $_POST['denomination']) && $_POST['denomination'] != '') 
            $denomination =  strip_tags(trim($this->input->post('denomination')));

        $type = '' ; 
        if ( isset( $_POST['type']) && $_POST['type'] != '') 
            $type =  strip_tags(trim($this->input->post('type')));

        $tel = $this->pv_model->verifContact($contact);
        $adr = $this->pv_model->verifAddress($address);
        $denom = $this->pv_model->verifdenom($denomination);

        if (count($tel) > 0) {
            $this->session->set_flashdata('tel', 'Ajout réussie');
        } elseif (count($denom) > 0) {
            $this->session->set_flashdata('denomination', 'Ajout réussie');
        } else {
            $data = [
                'adressPv' => ucfirst(strtolower($address)),
                'contactPv' => $contact , 
                'idadmin' => $_SESSION['idadmin'] ,
                'denomination_pv' => $denomination ,
                'type_pv' => $type 
            ];
            $this->pv_model->ajoutPV($data);
            $this->session->set_flashdata('ajout', 'Ajout réussie');
        }
        redirect('pointDeVente');
    }

    public function editPv()
    {
        $address = strip_tags(trim($this->input->post('adresse_edit')));
        $contact = strip_tags(trim($this->input->post('contact_edit')));
        $id = strip_tags(trim($this->input->post('idPv')));

        $denomination = '' ; 
        if ( isset( $_POST['denomination']) && $_POST['denomination'] != '') 
            $denomination =  strip_tags(trim($this->input->post('denomination')));

        $type = '' ; 
        if ( isset( $_POST['type']) && $_POST['type'] != '') 
            $type =  strip_tags(trim($this->input->post('type')));


        $data = [
            'adressPv' => ucfirst(strtolower($address)),
            'contactPv' => $contact ,
            'denomination_pv' => $denomination ,
            'type_pv' => $type 
        ];

        $this->pv_model->editPv($data, $id);

        $this->session->set_flashdata('edit', 'Ajout réussie');
        redirect('pointDeVente');
    }

    public function verifPv()
    {
        $id = $this->input->post('id');
        $contact = strip_tags(trim($this->input->post('contact')));
        $adress = strip_tags(trim($this->input->post('adress')));
        $denomination = strip_tags(trim($this->input->post('denomination')));

        $dataId = $this->pv_model->verifyIfPvExiste($id);

        $tab = [];

        for ($i = 0; $i < count($dataId); $i++) {
            array_push($tab, ucfirst(strtolower($dataId[$i]->adressPv)));
            array_push($tab, $dataId[$i]->contactPv);
            array_push($tab, $dataId[$i]->denomination_pv);
        }

        $response = ['success' => true];
        if (in_array($contact, $tab)) {
            $response['contactExiste'] = true;
            $response['success'] = false;
        }

        // if (in_array($adress, $tab)) {
        //     $response['adressExiste'] = true;
        //     $response['success'] = false;
        // }
        if (in_array($denomination, $tab)) {
            $response['denomination'] = true;
            $response['success'] = false;
        }
        echo json_encode($response);
    }

    public function recherchePV()
    {
        $keyword = strip_tags(trim($this->input->get('recherche')));
        $_POST['post'] = $keyword;
        $keyword = '%' . $keyword . '%';

        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'recherchePV';
        $config["total_rows"] = count($this->pv_model->recherchePV($keyword, '', ''));
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

        $datapag['lieu'] = $this->pv_model->recherchePV($keyword, $config["per_page"], $start);
        // * pagination * // 

        // $result = $this->pv_model->recherchePV($keyword);
        // var_dump($datapag);
        // die();

        if (count($datapag['lieu']) > 0) {
            $data['title'] = 'Points de Vente:';
            $data['css'] = 'pointVente.css';
            $js['js'] = 'pointVente.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['pv' => true]);
            $this->load->view('templates/tete');
            $this->load->view('pointVente', [
                'data' => $datapag,
                'post' => $_POST['post'],
            ]);
            $this->load->view('templates/footer', $js);
        } else {
            redirect('pointDeVente');
        }
    }

    public function deletePv()
    {
        $id = $this->input->post('id');

        $this->pv_model->deletePv($id);

        echo json_encode(['success' => true]);
        $this->session->set_flashdata('delete', 'Ajout réussie');
    }

    public function getAllPv()
    {
        $data = $this->pv_model->getAllPv();
        return $data;
    }

    public function getPvByid()
    {
        $id = $this->input->post('id');
        $pv = $this->pv_model->getPvByid($id);
        echo json_encode($pv[0]);
    }
}
