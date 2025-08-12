<?php
class Depensesearch extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("EtatModel", 'etat');
        $this->load->model('DepenseModel', 'depense');
    }

    public function jail()
    {
        if (!isset($_SESSION['user_type'])) {
            redirect('connexion');
        }
    }


    public function index()
    {
        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();


        $alldep = $this->depense->getAlldep();

        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'Depensesearch';
        $config["total_rows"] = count($alldep);
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
        $temps = [];
        for ($i = $start; $i < PAGINATION + $start; $i++) {
            if (isset($alldep[$i])) {
                $temps[] = $alldep[$i];
            }
        }
        $datapag['depense'] = $temps;
        $temps = [];


        
        // echo '<pre>';
        // var_dump($datapag);
        // echo '</pre>';
        // die;



        //  somme entrant et sortant 

        $somme = $this->depense->getsomme($alldep);

        //  somme entrant et sortant 

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense_search', [
            'pv' =>  $this->etat->getallpv(),
            'somme' => $somme , 
            'data' => $datapag 
        ]);
        $this->load->view('templates/footer', $assets);
    }

    public function search()
    {

        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();

        // date
        $date_debut = trim(strip_tags($_GET['date_debut']));
        $heure_debut = trim(strip_tags($_GET['heure_debut']));

        $_POST['date_debut'] = $date_debut;
        $_POST['heure_debut'] = $heure_debut;
        if ($heure_debut == '' && $date_debut != '')
            $heure_debut = '00:00:00';
        else if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_GET['date_fin']));
        $heure_fin = trim(strip_tags($_GET['heure_fin']));
        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;
        if ($heure_fin == '' && $date_fin != '')
            $heure_fin = '00:00:00';
        else if ($heure_fin != '' && $date_fin != '')
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '' )
            $heure_fin .= '23:59:59';


        $pv = trim(strip_tags($_GET['lieu']));


        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        // Data Etat
        $dataDep =  $this->depense->getdataDep_search($date_debut, $date_fin, $pv);
        // Data Etat


        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'Depensesearch/search';
        $config["total_rows"] = count($dataDep);
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
        $temps = [];
        for ($i = $start; $i < PAGINATION + $start; $i++) {
            if (isset($dataDep[$i])) {
                $temps[] = $dataDep[$i];
            }
        }
        $datapag['depense'] = $temps;
        $temps = [];


        //  somme entrant et sortant 

        $somme = $this->depense->getsomme($dataDep);

        //  somme entrant et sortant 

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense_search', [
            'data' => $datapag,
            'pv' =>  $this->etat->getallpv(),
            'somme' => $somme
        ]);
        $this->load->view('templates/footer', $assets);
    }
}
