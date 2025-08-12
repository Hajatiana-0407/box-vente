<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suivi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("SuiviModel", "suivi");
        $this->load->model("ListeModel", "liste");
    }

    /**
     * page pricipale 
     *
     * @return void
     */
    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $assets['title'] = 'Suivi';
        $assets['css'] = 'liste.css';
        $assets['js'] = 'suivi.js';

        $datas['client'] = $this->suivi->get_authors($page);
        $datas['links'] = $this->pagination('suivi', count($this->suivi->get_authors()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['suivi' => true]);
        $this->load->view('templates/tete');
        $this->load->view('suivi', [
            'data' => $datas
        ]);
        $this->load->view('templates/footer', $assets);
    }

    /**
     * Recherche 
     *
     * @return void
     */
    public function search()
    {
        $keyword = strip_tags(trim($_GET['recherche']));
        $_POST['post'] = $keyword;
        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datas['client'] = $this->suivi->search($keyword, $page);
        $datas['links'] = $this->pagination('suivi', count($this->suivi->search($keyword)));


        $assets['title'] = 'Suivi';
        $assets['css'] = 'liste.css';
        $assets['js'] = 'suivi.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['suivi' => true]);
        $this->load->view('templates/tete');
        $this->load->view('suivi', [
            'data' => $datas,
        ]);
        $this->load->view('templates/footer', $assets);
    }


    /**
     * DEtaille 
     *
     * @return void
     */
    public function details($page = 1)
    {
        $telClient = '';
        if (isset($_POST['telClient']) && $_POST['telClient'] != '') {
            $telClient = trim(strip_tags($this->input->post('telClient')));
        }
        $nom = '';
        if (isset($_POST['nom']) && $_POST['nom'] != '') {
            $nom = trim(strip_tags($this->input->post('nom')));
        }

        $data = $this->suivi->details($telClient, $page);

        $nPages = ceil(count($this->suivi->details($telClient)) / PAGINATION);

        $allpv = $this->liste->getAllPv();


        $assets['title'] = 'Suivi';
        $assets['css'] = 'liste.css';
        $assets['js'] = 'suivi.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('suivi-details', [
            'data' => $data,
            'current' => $page,
            'nom' => $nom,
            'nPages' => $nPages,
            'telClient' => $telClient,
            'nom' => $nom,
            'pv' => $allpv
        ]);
        $this->load->view('templates/footer', $assets);
    }

    public function detailsSearch($page = 1)
    {

        // date
        $date_debut = trim(strip_tags($_POST['date_debut']));
        $heure_debut = trim(strip_tags($_POST['heure_debut']));

        $_POST['date_debut'] = $date_debut;
        $_POST['heure_debut'] = $heure_debut;

        if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_POST['date_fin']));
        $heure_fin = trim(strip_tags($_POST['heure_fin']));


        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;

        if ($heure_fin != '' && $date_fin != '')
            $heure_fin .= ':00';

        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        $mot = strip_tags(trim($_POST['recherche']));
        $lieu = strip_tags(trim($_POST['lieu']));

        $_POST['mot'] = $mot;
        $_POST['lieu'] = $lieu;

        $telClient = '';
        if (isset($_POST['telClient']) && $_POST['telClient'] != '') {
            $telClient = trim(strip_tags($this->input->post('telClient')));
        }
        $nom = '';
        if (isset($_POST['nom']) && $_POST['nom'] != '') {
            $nom = trim(strip_tags($this->input->post('nom')));
        }


        $data = $this->suivi->detailsSearch($date_debut, $date_fin, $mot, $lieu, $telClient,  $page);
        $allpv = $this->liste->getAllPv();

        $nPages = ceil(count($this->suivi->detailsSearch($date_debut, $date_fin, $mot, $lieu, $telClient)) / PAGINATION);

        $assets['title'] = 'Suivi';
        $assets['css'] = 'liste.css';
        $assets['js'] = 'suivi.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('suivi-details', [
            'data' => $data,
            'current' => $page,
            'nom' => $nom,
            'nPages' => $nPages,
            'telClient' => $telClient,
            'nom' => $nom,
            'pv' => $allpv
        ]);
        $this->load->view('templates/footer', $assets);
    }
}
