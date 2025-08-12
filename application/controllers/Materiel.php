<?php
defined('BASEPATH') or exit ('No direct script access allowed');

class Materiel extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("MaterielModel", "materiel_model");
        $this->load->model('MaterielModel', 'img_model');
    }



    public function DonnerMateriel()
    {
        $id = $this->input->post('materiel');
        $data = $this->materiel_model->getMaterielById($id);

        echo json_encode($data[0]);
    }

    private function getMateriel()
    {
        $materiel = $this->materiel_model->getAllmateriel();
        return $materiel;
    }

    public function materiel()
    {
        $this->jail();
        $data['title'] = 'Materiel';
        $data['css'] = 'materiel.css';
        $data['js'] = 'materiel.js';

        $data['materiel'] = $this->materiel_model->getAllmateriel();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['materiel' => true]);
        $this->load->view('materiel', ['materiel' => $this->getMateriel()]);
        $this->load->view('templates/footer', $data);
    }

    public function delete()
    {
        $idMateriel = $this->input->post('id');

        // Verifier si materiel est dans d'autres element:
        $dataVerify = $this->materiel_model->verify_mat($idMateriel);

        if (count($dataVerify) > 0) {
            echo json_encode([
                'success' => false,
                'exist' => true,
            ]);
        } else {
            $data = $this->materiel_model->deleteMateriel($idMateriel);
            echo json_encode([
                'success' => true,
                'exist' => false,
            ]);
        }
        // redirect('materiel');
    }

    public function register()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('reference', 'Référence', 'required');
        $this->form_validation->set_rules('designation', 'Désignation', 'required');

        $data['title'] = 'Materiel';
        $data['css'] = 'materiel.css';
        $data['js'] = 'materiel.js';

        $data['materiel'] = $this->materiel_model->getAllmateriel();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['materiel' => true]);
        $this->load->view('materiel', $data);
        $this->load->view('templates/footer', $data); 

        $reference = trim($this->input->post('reference'));
        $designation = trim($this->input->post("designation"));

        
        $filename = $_FILES['photo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $unique_name = time().'_'.uniqid('materiel_', true). '_'. $_SESSION['idadmin'] . '.' . $ext;

        $image = move_uploaded_file($_FILES['photo']['tmp_name'], 'public/upload/' . $unique_name);

        $ref = $this->materiel_model->getMaterielByRefs($reference);
        $designe = $this->materiel_model->getMaterielByDesignation($designation);

        if (count($ref) > 0) {
            $this->session->set_flashdata('ref', 'Ajout réussie');
        } elseif (count($designe) > 0) {
            $this->session->set_flashdata('desingation', 'Ajout réussie');
        } else {
            $this->materiel_model->insertmateriel([
                "designationMateriel" => $designation,
                "refMateriel" => $reference,
                "photo" => 'upload/' . $unique_name
            ]);
            $this->session->set_flashdata('success', 'Erreur de l\'ajout');
        }

        redirect('materiel');
    }

    public function editMateriel()
    {
        $photo = $_FILES['photo'];
        $id = $this->input->post('id');


        if ($photo['name'] == '' || $photo['size'] == 0) {
            // tsy misy sary
            $data['designationMateriel'] = $this->input->post('designation_modif');
            $data['refMateriel'] = $this->input->post('reference_modif');

            $this->materiel_model->updateMateriel($id, $data);
        } else {
            
            $filename = $_FILES['photo']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $unique_name = time().'_'.uniqid('materiel_', true). '_'. $_SESSION['idadmin'] . '.' . $ext;

            move_uploaded_file($_FILES['photo']['tmp_name'], 'public/upload/' . $unique_name);
            $data['designationMateriel'] = $this->input->post('designation_modif');
            $data['refMateriel'] = $this->input->post('reference_modif');
            $data['photo'] = 'upload/' . $unique_name;
            $this->materiel_model->updateMateriel($id, $data);
        }

        $this->session->set_flashdata('updated', 'Ajout réussie');
        redirect('materiel');
    }

    public function verify_materiel_if_exist()
    {
        $reference = trim($this->input->post('reference'));
        $designation = trim($this->input->post('designation'));
        $id = $this->input->post('idmat');

        $dataNum = $this->materiel_model->verify_ref_materiel($reference);
        $dataMail = $this->materiel_model->verify_ref_materiel($designation);

        $dataId = $this->materiel_model->getALLMatsWithCriteria($id);

        $tab = [];
        for ($i = 0; $i < count($dataId); $i++) {
            array_push($tab, $dataId[$i]->designationMateriel);
            array_push($tab, $dataId[$i]->refMateriel);
        }
        $response = ['success' => true];
        if (in_array($reference, $tab)) {
            $response['referenceExiste'] = true;
            $response['success'] = false;
        }
        if (in_array($designation, $tab)) {
            $response['designationExiste'] = true;
            $response['success'] = false;
        }
        echo json_encode($response);
    }

    public function recherche_materiel()
    {
        $keyword = trim($this->input->post('keyword'));
        $data['materiel'] = $this->materiel_model->getMatShearch($keyword);

        $data['title'] = 'Materiel';
        $data['css'] = 'materiel.css';
        $data['js'] = 'materiel.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['materiel' => true]);
        $this->load->view('materiel', $data);
        $this->load->view('templates/footer', $data);
    }
}
