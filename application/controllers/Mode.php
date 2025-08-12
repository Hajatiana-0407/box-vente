<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mode extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("ModeModel", "mode_model");
    }

    public function mode_de_paiment()
    {
        $data['title'] = 'Mode de Paiment';
        $data['css'] = 'mode.css';
        $js['js'] = 'mode.js';

        $nPages = ceil($this->mode_model->countAllMode() / PAGINATION);
        $current = 1;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mode' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mode_de_paiment', [
            'mode' => $this->getMode(),
            'list' => $this->mode_model->getAllMode(),
            "nPages" => $nPages,
            "current" => $current,
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function page($page)
    {
        $data['title'] = 'Mode de Paiment';
        $data['css'] = 'mode.css';
        $js['js'] = 'mode.js';

        $nPages = ceil($this->mode_model->countAllMode() / PAGINATION);
        $current = $page;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['mode' => true]);
        $this->load->view('templates/tete');
        $this->load->view('mode_de_paiment', [
            'mode' => $this->getMode(),
            'list' => $this->mode_model->getAllMode($page),
            "nPages" => $nPages,
            "current" => $current,
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function register()
    {
        $nom = strip_tags(trim($this->input->post('nom')));
        $numero = strip_tags(trim($this->input->post('numero')));
        
        // Vérifier que $nom et $numero ne sont pas des tableaux
        if (!is_array($nom) && !is_array($numero)) {
            $nomExist = $this->mode_model->getModeByNom($nom);
            $numExist = $this->mode_model->getModeByNumero($numero);


            if (count($nomExist) > 0) {
                $this->session->set_flashdata('nom', 'Le nom existe déjà');
            } elseif (count($numExist) > 0) {
                $this->session->set_flashdata('num', 'Le numéro existe déjà');
            } elseif(
                $nom === 'ESPECE' || 
                $nom === 'ESPÈCE' || 
                $nom === 'espece' || 
                $nom === 'espèce' ||
                $nom === 'Espèce' ||
                $nom === 'Espece' ||
                strtolower($nom) === 'espece' || 
                strtolower($nom) === 'espèce'
            ){
                $this->session->set_flashdata('nom', 'Le nom existe déjà');
            } else {
                $this->mode_model->insertMode([
                    "denom" => $nom,
                    "numeroCompte" => $numero , 
                    "idadmin" => $_SESSION['idadmin'] , 
                ]);
                $this->session->set_flashdata('success', 'Ajout réussi');
            }
        } else {
            // Gérer le cas où $nom ou $numero est un tableau
            $this->session->set_flashdata('error', 'Données non valides');
        }

        redirect('mode_de_paiment');
    }

    private function getMode()
    {
        $mode = $this->mode_model->getAllMode();
        return $mode;
    }

    public function deleteMode()
    {
        $id = $this->input->post('id');

        // Verifier si le mode de paiement a dans des dependances
        // $data = $this->mode_model->verify($id);
        $data = [] ; 
        if (count($data) > 0) {
            $this->session->set_flashdata('sup_erreur' , true ) ; 
            echo json_encode([
                'success' => false,
                'error' => true,
            ]);

        } else {
            $this->session->set_flashdata('sup_success' , true ) ; 
            $this->mode_model->deleteMode($id);
            echo json_encode([
                'success' => true,
                'false' => false,
            ]);
        }
    }
    public function DonneMode()
    {
        $id = $this->input->post('modepaiement');
        $data = $this->mode_model->getModeById($id);

        echo json_encode($data[0]);
    }

    public function editMode()
    {
        $id = $this->input->post('id');
        $data = array(
            'denom' => $this->input->post('nom'),
            'numeroCompte' => $this->input->post('numero'),
        );
        $this->mode_model->updateMode($data, $id);
        $this->session->set_flashdata('updated' , true  ) ; 
        echo json_encode([
            'success' => true,
        ]);
    }


    public function verify_mode_if_exist()
    {
        $nom = $this->input->post('nom');
        $numero = $this->input->post('numero');
        $id = $this->input->post('idMode');
        $nom = trim($nom);
        $numero = trim($numero);

        $nom_ = $this->mode_model->testnom( $nom  ,  $id);
        $num  = $this->mode_model->testnum( $numero   ,  $id);

        if ( count( $nom_ ) != 0 ||  count( $num  ) != 0  ){
            if ( count( $nom_ ) != 0 ){
                echo json_encode( [
                    'success' => false , 
                    'type' => 'nom' , 
                    'espece' => false 
                ]) ; 
            }
            else {
                echo json_encode( [
                    'success' => false , 
                    'type' => 'num' , 
                    'espece' => false 
                ]) ; 
            }
        }
        else if(
            $nom === 'ESPECE' || 
            $nom === 'ESPÈCE' || 
            $nom === 'espece' || 
            $nom === 'espèce' ||
            $nom === 'Espèce' ||
            $nom === 'Espece' ||
            strtolower($nom) === 'espece' || 
            strtolower($nom) === 'espèce'
        ){
            $response['espece'] = true;
            $response['success'] = false;
            $response['type'] = 'esepce';
            echo json_encode($response);
        } 
        else {
            echo json_encode([
                "success" => true 
            ]) ; 
        }

    }
}
