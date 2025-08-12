<?php

use function PHPUnit\Framework\containsOnly;

class Fournisseur extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('FournisseurModel', 'fournisseur');
    }

    public function index()
    {
        $assets['title'] = 'Fournisseur';
        $assets['css'] = 'fournisseur.css';
        $assets['js'] = 'fournisseur.js';


        $page = (isset($_GET['page'])) ? $_GET['page'] : 0;

        $datas = $this->fournisseur->getAll($page);

        $lien = $this->pagination('fournisseur', count($this->fournisseur->getAll()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['fournisseur' => true]);
        $this->load->view('templates/tete');
        $this->load->view('fournisseur', [
            'datas' => $datas,
            'lien' => $lien
        ]);
        $this->load->view('templates/footer', $assets);
    }

    public function register()
    {
        $entreprise = '';
        if (isset($_POST['entreprise']) && $_POST['entreprise'] != "") {
            $entreprise = trim(strip_tags($_POST['entreprise']));
        }
        $adresse = '';
        if (isset($_POST['adresse']) && $_POST['adresse'] != "") {
            $adresse = trim(strip_tags($_POST['adresse']));
        }
        $telephone = '';
        if (isset($_POST['telephone']) && $_POST['telephone'] != "") {
            $telephone = trim(strip_tags($_POST['telephone']));
            $telephone = str_replace(' ', '', $telephone);
        }
        $email = '';
        if (isset($_POST['email']) && $_POST['email'] != "") {
            $email = trim(strip_tags($_POST['email']));
        }
        $nif = '';
        if (isset($_POST['nif']) && $_POST['nif'] != "") {
            $nif = trim(strip_tags($_POST['nif']));
        }
        $stat = '';
        if (isset($_POST['stat']) && $_POST['stat'] != "") {
            $stat = trim(strip_tags($_POST['stat']));
        }


        $data = [
            'nom_entr' => $entreprise,
            'adresse_fournisseur' => $adresse,
            'tel_fournisseur' => $telephone,
            'nif_fournisseur' => $nif,
            'stat_fournisseur' => $stat,
            'mail_fournisseur' => $email,
            'idadmin' => $_SESSION['idadmin']
        ];


        $idfournisseur = $this->fournisseur->register($data);


        if (isset($_POST['page']) && $_POST['page'] == 'appro') {
            echo json_encode( [
                'id' => $idfournisseur ,
            ]) ; 
        } else {
            $this->session->set_flashdata('added', true);
            redirect('fournisseur');
        }
    }
    public function edit()
    {
        $idfournisseur = '';
        if (isset($_POST['idfournisseur']) && $_POST['idfournisseur'] != "") {
            $idfournisseur = trim(strip_tags($_POST['idfournisseur']));
        }
        $entreprise = '';
        if (isset($_POST['entreprise']) && $_POST['entreprise'] != "") {
            $entreprise = trim(strip_tags($_POST['entreprise']));
        }
        $adresse = '';
        if (isset($_POST['adresse']) && $_POST['adresse'] != "") {
            $adresse = trim(strip_tags($_POST['adresse']));
        }
        $telephone = '';
        if (isset($_POST['telephone']) && $_POST['telephone'] != "") {
            $telephone = trim(strip_tags($_POST['telephone']));
            $telephone = str_replace(' ', '', $telephone);
        }
        $email = '';
        if (isset($_POST['email']) && $_POST['email'] != "") {
            $email = trim(strip_tags($_POST['email']));
        }
        $nif = '';
        if (isset($_POST['nif']) && $_POST['nif'] != "") {
            $nif = trim(strip_tags($_POST['nif']));
        }
        $stat = '';
        if (isset($_POST['stat']) && $_POST['stat'] != "") {
            $stat = trim(strip_tags($_POST['stat']));
        }


        $data = [
            'nom_entr' => $entreprise,
            'adresse_fournisseur' => $adresse,
            'tel_fournisseur' => $telephone,
            'nif_fournisseur' => $nif,
            'stat_fournisseur' => $stat,
            'mail_fournisseur' => $email
        ];

        $this->fournisseur->edit($data, $idfournisseur);
        $this->session->set_flashdata('edited', true);
        redirect('fournisseur');
    }

    public function search()
    {
        $recherche = '';
        if (isset($_GET['recherche']) && $_GET['recherche'] != '') {
            $recherche = trim(strip_tags($_GET['recherche']));
        }
        $page = (isset($_GET['page'])) ? $_GET['page'] : 0;

        $datas = $this->fournisseur->search($recherche,  $page);
        $lien = $this->pagination_search('Fournisseur/search', count($this->fournisseur->search($recherche)));


        $assets['title'] = 'Fournisseur';
        $assets['css'] = 'fournisseur.css';
        $assets['js'] = 'fournisseur.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['fournisseur' => true]);
        $this->load->view('templates/tete');
        $this->load->view('fournisseur', [
            'datas' => $datas,
            'lien' => $lien,
            'post' => $recherche
        ]);
        $this->load->view('templates/footer', $assets);
    }

    public function delete()
    {
        $idfournisseur = '';
        if (isset($_POST['idfournisseur']) && $_POST['idfournisseur'] != '') {
            $idfournisseur = trim(strip_tags($_POST['idfournisseur']));
        }
        $tel_fournisseur = '';
        if (isset($_POST['tel_fournisseur']) && $_POST['tel_fournisseur'] != '') {
            $tel_fournisseur = trim(strip_tags($_POST['tel_fournisseur']));
        }


        // delete commande 
        $this->fournisseur->deleteCommande( $tel_fournisseur ) ; 


        $this->fournisseur->delete($idfournisseur);
        $this->session->set_userdata('isdelete', true);

        echo json_encode(true);
    }

    public function duplicate()
    {
        $telephone = '';
        if (isset($_POST['telephone']) && $_POST['telephone'] != '') {
            $telephone = trim(strip_tags($_POST['telephone']));
            $telephone = str_replace(' ', '', $telephone);
        }
        $entreprise = '';
        if (isset($_POST['entreprise']) && $_POST['entreprise'] != '') {
            $entreprise = trim(strip_tags($_POST['entreprise']));
        }
        $email = '';
        if (isset($_POST['email']) && $_POST['email'] != '') {
            $email = trim(strip_tags($_POST['email']));
        }

        $id_not = '';
        if (isset($_POST['id_not']) && $_POST['id_not'] != '') {
            $id_not = trim(strip_tags($_POST['id_not']));
        }

        $tel =  $this->fournisseur->duplicatetel($telephone, $id_not);
        $entreprise =  $this->fournisseur->duplicatenom($entreprise, $id_not);
        if (count($tel) > 0) {
            echo json_encode([
                'success' => false,
                'type' => 'telephone', 
                'data' => $tel[0]
            ]);
        } else if (count($entreprise) > 0) {
            echo json_encode([
                'success' => false,
                'type' => 'entreprise' ,
                'data' => $entreprise[0]
            ]);
        } else {
            echo json_encode([
                'success' => true
            ]);
        }
    }
}
