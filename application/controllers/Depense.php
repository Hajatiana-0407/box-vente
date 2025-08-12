<?php
class Depense extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DepenseModel', 'depense');
        $this->load->model('PointVente_model', 'pv');
        $this->load->model("EtatModel", 'etat');
    }

    public function jail()
    {
        if (!isset($_SESSION['user_type'])) {
            redirect('connexion');
        } 
    }

    public function index($page = 1)
    {
        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();


        $pv = $this->pv->getAllPv();

        $nPages = ceil($this->depense->get_count() / PAGINATION);
        $current = 1;

        $datapag['depense'] = $this->depense->get_authors($current);


        $alldep = $this->depense->getAlldep();
        $somme = $this->depense->getsomme($alldep);

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar_entr', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense', [
            'data' => $datapag,
            'pv' => $pv,
            'somme' => $somme,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $assets);
    }

    public function page($page = 1)
    {
        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();


        $pv = $this->pv->getAllPv();

        $nPages = ceil($this->depense->get_count() / PAGINATION);
        $current = $page;

        // * pagination * // 
        // $config = array();
        // $config["base_url"] = base_url() . 'depense';
        // $config["total_rows"] = $this->depense->get_count();
        // $config["per_page"] = PAGINATION;
        // $config['page_query_string'] = TRUE;
        // $config['query_string_segment'] = 'page';
        // $config['use_page_numbers'] = TRUE;
        // $this->pagination->initialize($config);

        // $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $datapag["links"] = $this->pagination->create_links();

        if ((int)$page == 0) {
            $start = (int)$page * (int)$config["per_page"];
        } else {
            $start = ((int)$page - 1) * (int)$config["per_page"];
        }
        $datapag['depense'] = $this->depense->get_authors($current);



        $alldep = $this->depense->getAlldep();
        $somme = $this->depense->getsomme($alldep);

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar_entr', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense', [
            'data' => $datapag,
            'pv' => $pv,
            'somme' => $somme,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $assets);
    }


    public function register()
    {
        $montant = strip_tags(trim($_POST['montant']));
        $raison = strip_tags(trim($_POST['raison']));
        $pv = strip_tags(trim($_POST['pv']));
        $date = strip_tags(trim($_POST['date']));
        $heure = strip_tags(trim($_POST['heure']));

        $user = '';

        if ($_SESSION['user_type'] != 'admin') {
            $user = $_SESSION['id_user'];
        }


        $the_date = '';

        if ($date == '') {
            $the_date = date("Y-m-d H:i:s");
        } else {
            if ($heure == '') {
                $the_date = $date . ' 00:00:00';
            } else {
                $the_date = $date . ' ' . $heure . ':' . date("s");
            }
        }




        if ($raison != '' && $montant != '') {


            $data = [
                'raison' => $raison,
                'montant' => $montant,
                'idadmin' => $_SESSION['idadmin'],
                'idPointVente ' => $pv,
                'datedepense' => $the_date,
                'idUser' => $user
            ];
            $this->depense->register($data);
            $this->session->set_flashdata('register', true);
        }
        redirect('depense');
    }

    public function deleteit()
    {
        $id = trim(strip_tags($_POST['id']));

        $this->depense->deleteit($id);
        $this->session->set_flashdata('delete', true);

        echo json_encode([
            'success' => true
        ]);
    }

    public function edit()
    {
        $id = trim(strip_tags($_POST['id']));
        $montant = trim(strip_tags($_POST['montant_']));
        $raison = trim(strip_tags($_POST['raison_']));


        if ($montant != '' && $raison != "" &&  $id != '') {
            $data = [
                'montant' => $montant,
                'raison' => $raison
            ];

            $this->depense->edit($id, $data);
            $this->session->set_flashdata('edition', 'ok');
            redirect('Depense');
        }
        redirect('depense');
    }

    // public function filter()
    // {
    //     $keyword = strip_tags(trim($_GET['recherche']));
    //     $date  = strip_tags(trim($_GET['date']));

    //     $_POST['date'] = $date;
    //     $_POST['key'] = $keyword;



    //     // * pagination * // 
    //     $config = array();
    //     $config["base_url"] = base_url() . 'Depense/filter';
    //     $config["total_rows"] = count($this->depense->searchdepense($keyword, $date));
    //     $config["per_page"] = PAGINATION;
    //     $config['enable_query_strings'] = TRUE;
    //     $config['page_query_string'] = TRUE;
    //     $config['query_string_segment'] = 'page';
    //     $config['reuse_query_string'] = TRUE;
    //     $config['use_page_numbers'] = TRUE;
    //     $this->pagination->initialize($config);

    //     $page = isset($_GET['page']) ? $_GET['page'] : 0;

    //     $datapag["links"] = $this->pagination->create_links();

    //     if ((int)$page == 0) {
    //         $start = (int)$page * (int)$config["per_page"];
    //     } else {
    //         $start = ((int)$page - 1) * (int)$config["per_page"];
    //     }
    //     $datapag['depense'] = $this->depense->searchdepense($keyword, $date,  $config["per_page"], $start);
    //     // * pagination * // 


    //     $pv = $this->pv->getAllPv();

    //     $assets['css'] = 'depense.css';
    //     $assets['title'] = 'Depense';
    //     $assets['js'] = 'depense.js';
    //     $this->jail();

    //     $this->load->view('templates/header', $assets);
    //     $this->load->view('templates/sidebar_entr', ["liste" => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('depense', [
    //         'data' => $datapag,
    //         'pv' => $pv
    //     ]);
    //     $this->load->view('templates/footer', $assets);
    // }

    public function search($page = 1)
    {

        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();

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
        $_POST['lieu'] = $pv ; 


        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        // Data Etat
        $dataDep =  $this->depense->getdataDep_search($date_debut, $date_fin, $pv);
        // Data 
        
        // echo '<pre>' ;
        // var_dump( $dataDep ) ; 
        // echo '</pre>' ; die ; 


        $nPages = ceil(count($dataDep) / PAGINATION);
        $current = $page;

        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
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
        $this->load->view('templates/sidebar_entr', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense', [
            'data' => $datapag,
            'pv' =>  $this->etat->getallpv(),
            'somme' => $somme , 
            'nPages' => $nPages ,
            'current' => $current  
        ]);
        $this->load->view('templates/footer', $assets);
    }
}
