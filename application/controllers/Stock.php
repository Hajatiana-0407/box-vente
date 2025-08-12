<?php

use SebastianBergmann\CodeCoverage\Util\Percentage;

defined('BASEPATH') or exit('No direct script access allowed');
class Stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('StockModel', 'stock');
        $this->load->model('AjoutProduitModel', 'produit');
        $this->load->model('ExportationModel', 'exportation');
    }
    public function index()
    {
        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $iteration = 0;
        // $data = [];
        $appros = $this->stock->getAll($page);
        $stocks = $this->stock->getStock($appros);

 


        $all_pv = $this->stock->pv_stock();


        $lien = $this->pagination('stock', count($this->stock->getAll()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stock', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function link_creation($datas = [], $nbrPage = 0, $current = 1)
    {
        $data_text = ' ';
        foreach ($datas as $key => $data) {
            $data_text .= 'data-' . $key . '="' . $data . '" ';
        }
        $lien = '';
        if ($nbrPage > 1) {
            $lien = '<div class="__pagination"><nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center pagination-sm">
                            <li class="page-item" >';

            if ($current == 1) {
                $lien .= '<span class="page-link __disabled" aria-label="Previous"><small aria-hidden="true">
                            <i class="fa-solid fa-angle-left"></i></small>
                        </span>';
            } else {
                $lien .= '<a class="page-link my_link" data-page="' . ($current - 1) . '" ' . $data_text . '  href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span></a>';
            }


            $lien .= '</li>';
            for ($i = 1; $i <= $nbrPage; $i++) {
                if ($i == $current)
                    $lien .= '<li class="page-item active" >';
                else
                    $lien .= '<li class="page-item "  >';
                $lien .= '<a class="page-link my_link"  data-page="' . $i . '" href="#" ' . $data_text . ' >' . $i . '</a>
                </li>';
            }


            $lien .= '<li>';
            if ($current == $nbrPage) {
                $lien .= '<span class="page-link __disabled" aria-label="Next">
                    <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                </span>';
            } else {
                $lien .= '<a class="page-link my_link" href="#" data-page="' . ($current + 1) . '" ' . $data_text . ' aria-label="Next">
                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                    </a>';
            }
            $lien .= '</li>';
            return $lien;
        }
        return '';
    }
    public function numeroSerie($page = 1)
    {
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST['idProduit'] != '') {
            $idProduit = trim(strip_tags($_POST['idProduit']));
        }
        $idPointVente = '';
        if (isset($_POST['idPointVente']) && $_POST['idPointVente'] != '') {
            $idPointVente = trim(strip_tags($_POST['idPointVente']));
        }

        $data = [
            'idProduit' => $idProduit,
            'idPointVente' => $idPointVente
        ];

        $nPages = ceil(count($this->stock->getAllNum($idProduit, $idPointVente)) / PAGINATION);
        $numero_de_serie = $this->stock->getAllNum($idProduit, $idPointVente, $page  );

        $lien = $this->link_creation($data, $nPages, $page);


        if ( count( $numero_de_serie )){
            echo json_encode([
                'success' => true  , 
                'data' => $numero_de_serie , 
                'lien' => $lien ,
            ]) ; 

        }else {
            echo json_encode([
                'success' => false  
            ]) ; 
        }
    }

    public function seuil()
    {
        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $iteration = 0;
        // $data = [];
        $appros = $this->stock->getAll_seuil($page);

        $stocks = $this->stock->getStock($appros);

        $all_pv = $this->stock->pv_stock();


        // convertire 
        // foreach ($stocks as $key => $stock) {
        //     $unites = $stock->unites;
        //     $min_qte = $stock->stock;
        //     // donner les qte correspondant a chaque unite
        //     $unite_convert = $this->covertion($unites, $min_qte);
        //     $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        // }



        $lien = $this->pagination('Stock-seuil', count($this->stock->getAll_seuil()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockseuil', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function search()
    {
        $keyword = '';
        if (isset($_GET['recherche']) && $_GET['recherche'] != '') {
            $keyword = strip_tags(trim($_GET['recherche']));
        }
        $_POST['post'] = $keyword;


        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_search($keyword,  $page);
        $stocks = $this->stock->getStock($appros);
        $all_pv = $this->stock->pv_stock();


        // convertire 
        // foreach ($stocks as $key => $stock) {
        //     $unites = $stock->unites;
        //     $min_qte = $stock->stock;
        //     // donner les qte correspondant a chaque unite
        //     $unite_convert = $this->covertion($unites, $min_qte);
        //     $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        // }

        $lien = $this->pagination_search('Stock/search', count($this->stock->getAll_search($keyword)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stock', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function search_seuil()
    {
        $keyword = '';
        if (isset($_GET['recherche']) && $_GET['recherche'] != '') {
            $keyword = strip_tags(trim($_GET['recherche']));
        }
        $_POST['post'] = $keyword;


        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_search_seuil($keyword,  $page);
        $stocks = $this->stock->getStock($appros);
        $all_pv = $this->stock->pv_stock();


        // convertire 
        // foreach ($stocks as $key => $stock) {
        //     $unites = $stock->unites;
        //     $min_qte = $stock->stock;
        //     // donner les qte correspondant a chaque unite
        //     $unite_convert = $this->covertion($unites, $min_qte);
        //     $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        // }

        $lien = $this->pagination_search('Stock/search', count($this->stock->getAll_search($keyword)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockseuil', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function imprimer()
    {
        $appros = $this->stock->getAll();
        $stocks = $this->stock->getStock($appros);

        $all_pv = $this->stock->pv_stock();


        // convertire 
        foreach ($stocks as $key => $stock) {
            $unites = $stock->unites;
            $min_qte = $stock->stock;
            // donner les qte correspondant a chaque unite
            $unite_convert = $this->covertion($unites, $min_qte);
            $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        }

        // echo '<pre>' ;
        // var_dump( $stocks ) ; 
        // echo '</pre>' ; die  ; 


        $this->load->view('imprim_stock', ['data' =>  $stocks]);
    }




    public function filtre()
    {
        $filter = [];
        $type = '';

        $order = [
            'asc' => 'desc',
            'desc' => 'asc'
        ];


        if (isset($_GET['type']) && $_GET['type'] != '') {
            $type = trim(strip_tags($_GET['type']));
        }

        $pv = [];
        if (isset($_GET['filter'])) {
            $pv = $this->input->get('filter');
            $filter[$type] = $pv;
        }

        $design = '';
        if (isset($_GET['design'])) {
            $design = $_GET['design'];
            $filter[$type] = $design;
            $_POST[$type] = $order[$design];
        }
        $ref = '';
        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
            $filter[$type] = $ref;
            $_POST[$type] = $order[$ref];
        }

        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_filtre($type, $filter,  $page);
        $stocks = $this->stock->getStock($appros);
        $all_pv = $this->stock->pv_stock();


        // convertire 
        // foreach ($stocks as $key => $stock) {
        //     $unites = $stock->unites;
        //     $min_qte = $stock->stock;
        //     // donner les qte correspondant a chaque unite
        //     $unite_convert = $this->covertion($unites, $min_qte);
        //     $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        // }

        $lien = $this->pagination_search('filtre', count($this->stock->getAll_filtre($type, $filter)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stock', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function filtre_seuil()
    {
        $filter = [];
        $type = '';

        $order = [
            'asc' => 'desc',
            'desc' => 'asc'
        ];


        if (isset($_GET['type']) && $_GET['type'] != '') {
            $type = trim(strip_tags($_GET['type']));
        }

        $pv = [];
        if (isset($_GET['filter'])) {
            $pv = $this->input->get('filter');
            $filter[$type] = $pv;
        }

        $design = '';
        if (isset($_GET['design'])) {
            $design = $_GET['design'];
            $filter[$type] = $design;
            $_POST[$type] = $order[$design];
        }
        $ref = '';
        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
            $filter[$type] = $ref;
            $_POST[$type] = $order[$ref];
        }

        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stock->getAll_filtre_seuil($type, $filter,  $page);
        $stocks = $this->stock->getStock($appros);
        $all_pv = $this->stock->pv_stock();


        // convertire 
        // foreach ($stocks as $key => $stock) {
        //     $unites = $stock->unites;
        //     $min_qte = $stock->stock;
        //     // donner les qte correspondant a chaque unite
        //     $unite_convert = $this->covertion($unites, $min_qte);
        //     $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        // }

        $lien = $this->pagination_search('filtre', count($this->stock->getAll_filtre($type, $filter)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockseuil', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }



    public function getSousPr($page = '')
    {
        if ($page == '') {
            $page = 0;
        }

        $pagin = 3;


        $date = trim(strip_tags($_POST['date']));
        $ref = trim(strip_tags($_POST['ref']));

        $pv = '';
        if (isset($_POST['pv'])) {
            $pv = $_POST['pv'];
        }

        $sousP = $this->stock->getSousPr($date, $ref, $pv);

        $nbr = count($sousP);




        if ((int)$page == 0) {
            $start = (int)$page * $pagin;
        } else {
            $start = ((int)$page - 1) * $pagin;
        }

        if ($nbr <= $pagin) {
            echo json_encode([
                'data' => $sousP,
                'page' => $page,
                'pagin' => 'Non',
                'nbr' => $nbr
            ]);
        } else {

            $sousPaginer = $this->stock->getSousPrMPaginer($ref, $date, $pagin, $start, $pv);
            $nbr_data = ceil($nbr / $pagin);
            echo json_encode([
                'data' => $sousPaginer,
                'soup' => $sousP,
                'page' => $page,
                'pagin' => 'oui',
                'nbr' => $nbr_data
            ]);
        }
    }
}
