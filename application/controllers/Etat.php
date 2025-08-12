<?php
class Etat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("EtatModel", 'etat');
    }
    public function index()
    {

        $asset['title'] = 'Etat';
        $asset['css'] = 'etat.css';
        $asset['js'] = 'etat.js';

        // Data Etat
        $dataEtats =  $this->etat->getdataEtat();
        // Data Etat

        // echo "<pre>" ;
        // var_dump( $dataEtats ) ; 
        // echo "</pre>" ; die ; 

        $page = 1;


        $nPages = ceil(count($dataEtats) / PAGINATION);
        $current = $page;

        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
            if (isset($dataEtats[$i])) {
                $temps[] = $dataEtats[$i];
            }
        }
        $datapag['etat'] = $temps;
        $temps = [];


        //  somme entrant et sortant 

        $somme = $this->etat->getsomme($dataEtats);

        //  somme entrant et sortant 

        $this->load->view('templates/header', $asset);
        $this->load->view('templates/sidebar_entr', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('etat', [
            'data' => $datapag,
            'pv' =>  $this->etat->getallpv(),
            'somme' => $somme,
            'nPages' => $nPages,
            "current" => $current
        ]);
        $this->load->view('templates/footer', $asset);
    }

    public function page($page = 1)
    {

        $asset['title'] = 'Etat';
        $asset['css'] = 'etat.css';
        $asset['js'] = 'etat.js';

        // Data Etat
        $dataEtats =  $this->etat->getdataEtat();
        // Data Etat


        $nPages = ceil(count($dataEtats) / PAGINATION);
        $current = $page;

        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
            if (isset($dataEtats[$i])) {
                $temps[] = $dataEtats[$i];
            }
        }
        $datapag['etat'] = $temps;
        $temps = [];


        //  somme entrant et sortant 

        $somme = $this->etat->getsomme($dataEtats);

        //  somme entrant et sortant 

        $this->load->view('templates/header', $asset);
        $this->load->view('templates/sidebar_entr', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('etat', [
            'data' => $datapag,
            'pv' =>  $this->etat->getallpv(),
            'somme' => $somme,
            'nPages' => $nPages,
            "current" => $current
        ]);
        $this->load->view('templates/footer', $asset);
    }


    public function search($page = 1)
    {

        $asset['title'] = 'Etat';
        $asset['css'] = 'etat.css';
        $asset['js'] = 'etat.js';

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


        $pv = trim(strip_tags($_POST['lieu']));
        $_POST['lieu'] = $pv  ; 

        $type = trim(strip_tags($_POST['type']));
        $_POST['type'] = $type ; 


        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        // Data Etat
        $dataEtats =  $this->etat->getdataEtat_search($date_debut, $date_fin, $pv , $type );
        // Data Etat

        $nPages = ceil(count($dataEtats) / PAGINATION);
        $current = $page;


        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
            if (isset($dataEtats[$i])) {
                $temps[] = $dataEtats[$i];
            }
        }
        $datapag['etat'] = $temps;
        $temps = [];

        // echo '<pre>' ; 
        // var_dump( $datapag ); 
        // echo '</pre>' ; die ; 


        //  somme entrant et sortant 

        $somme = $this->etat->getsomme($dataEtats);

        //  somme entrant et sortant 

        $this->load->view('templates/header', $asset);
        $this->load->view('templates/sidebar_entr', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('etat', [
            'data' => $datapag,
            'pv' =>  $this->etat->getallpv(),
            'somme' => $somme ,
            'nPages' => $nPages , 
            'current' => $current 
        ]);
        $this->load->view('templates/footer', $asset);
    }
}
