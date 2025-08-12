<?php

use function PHPUnit\Framework\containsOnly;

class Abonnement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AbonnementModel', 'abonne');
    }

    public function jail()
    {
        if (!isset($_SESSION['user'])) {
            redirect('admin');
        }
    }

    public function index()
    {
        $page = 1;


        $this->jail();
        // Malagasy 
        $assets['title'] = 'Admin';
        $assets['css'] = 'abonnement.css';
        $assets['js'] = 'abonnement.js';

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $datas = $this->abonne->getAll();


        $nPages = ceil(count($datas) / PAGINATION);
        $current = 1;

        $temps = [];
        for ($i =  $start; $i < $start + PAGINATION; $i++) {
            if (isset($datas[$i])) {
                $temps[] = $datas[$i];
            }
        }
        $datas = $temps;


        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('abonnement', [
            'nPages' => $nPages,
            'current' => 1,
            'datas' => $datas
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $assets);
    }
    public function page($page = 1)
    {
        $this->jail();
        // Malagasy 
        $assets['title'] = 'Admin';
        $assets['css'] = 'abonnement.css';
        $assets['js'] = 'abonnement.js';

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $datas = $this->abonne->getAll();


        $nPages = ceil(count($datas) / PAGINATION);
        $current = 1;

        $temps = [];
        for ($i =  $start; $i < $start + PAGINATION; $i++) {
            if (isset($datas[$i])) {
                $temps[] = $datas[$i];
            }
        }
        $datas = $temps;


        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('abonnement', [
            'nPages' => $nPages,
            'current' => $page,
            'datas' => $datas
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $assets);
    }

    public function register()
    {
        $contact = trim(strip_tags($_POST['contact']));
        $nom = trim(strip_tags($_POST['nom']));
        $date = trim(strip_tags($_POST['date']));
        $dure = trim(strip_tags($_POST['dure']));

        $data = [
            'contact' => $contact,
            'nom' => $nom,
            'date_debut' => $date,
            'dure' => $dure
        ];


        $this->abonne->register($data);

        $this->session->set_flashdata('success', true);

        redirect('abonnement');
    }
    public function update()
    {
        $contact = trim(strip_tags($_POST['contact']));
        $nom = trim(strip_tags($_POST['nom']));
        $date = trim(strip_tags($_POST['date']));
        $dure = trim(strip_tags($_POST['dure']));
        $id = trim(strip_tags($_POST['id']));

        $data = [
            'contact' => $contact,
            'nom' => $nom,
            'date_debut' => $date,
            'dure' => $dure
        ];


        $this->abonne->update($data, $id);

        $this->session->set_flashdata('updated', true);

        redirect('abonnement');
    }

    public function delete()
    {
        $id  = trim(strip_tags($_POST['id']));

        $this->abonne->delete($id);

        $this->session->set_flashdata('deleted', true);
    }


    public function search($page  = 1)
    {
        $mot = trim(strip_tags($_POST['recherche']));

        // date
        $date_debut = trim(strip_tags($_POST['date_debut']));
        $heure_debut = trim(strip_tags($_POST['heure_debut']));

        $_POST['date_debut'] = $date_debut;
        $_POST['heure_debut'] = $heure_debut;
        // if ($heure_debut == '' && $date_debut != '')
        //     $heure_debut = '00:00:00';
        // else 
        if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_POST['date_fin']));
        $heure_fin = trim(strip_tags($_POST['heure_fin']));


        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;
        // if ($heure_fin == '' && $date_fin != '')
        //     $heure_fin = '00:00:00';
        // else 
        if ($heure_fin != '' && $date_fin != '')
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '' )
            $heure_fin .= '23:59:59';



        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date;

        $_POST['mot'] = $mot;

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $datas = $this->abonne->search($date_debut , $date_fin , $mot);


        $nPages = ceil(count($datas) / PAGINATION);
        $current = 1;

        $temps = [];
        for ($i =  $start; $i < $start + PAGINATION; $i++) {
            if (isset($datas[$i])) {
                $temps[] = $datas[$i];
            }
        }
        $datas = $temps;

        $assets['title'] = 'Admin';
        $assets['css'] = 'abonnement.css';
        $assets['js'] = 'abonnement.js';


        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('abonnement', [
            'nPages' => $nPages,
            'current' => $page,
            'datas' => $datas
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $assets);
    }

    public function getAdmin()
    {
        $contact = trim(strip_tags($_POST['contact']));

        $data = $this->abonne->getAdmin($contact);

        if (count($data) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $data[0]
            ]);
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }
}
