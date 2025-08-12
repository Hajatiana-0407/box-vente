<?php
class StockAll extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('StockAllModel', 'stockall');
    }

    /**
     * page par defaut 
     *
     * @return void
     */
    public function index()
    {
        $this->jail();
        $assets['title'] = 'Stock';
        $assets['css'] = 'stock.css';
        $js['js'] = 'stock.js';

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $appros = $this->stockall->getAll($page);
        $stocks = $this->stockall->getStock($appros);

        $all_pv = $this->stockall->pv_stock();

        $lien = $this->pagination('stock', count($this->stockall->getAll()));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockall', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * Recherche
     *
     * @return void
     */
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

        $appros = $this->stockall->getAll_search($keyword,  $page);
        $stocks = $this->stockall->getStock($appros);
        $all_pv = $this->stockall->pv_stock();


        // convertire 
        // foreach ($stocks as $key => $stock) {
        //     $unites = $stock->unites;
        //     $min_qte = $stock->stock;
        //     // donner les qte correspondant a chaque unite
        //     $unite_convert = $this->covertion($unites, $min_qte);
        //     $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        // }

        $lien = $this->pagination_search('Stock/search', count($this->stockall->getAll_search($keyword)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stock' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockall', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * Filtre
     *
     * @return void
     */
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

        $appros = $this->stockall->getAll_filtre($type, $filter,  $page);
        $stocks = $this->stockall->getStock($appros);
        $all_pv = $this->stockall->pv_stock();

        $lien = $this->pagination_search('filtre-all', count($this->stockall->getAll_filtre($type, $filter)));

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['stockall' => true]);
        $this->load->view('templates/tete');
        $this->load->view('stockall', [
            'datas' => $stocks,
            'lien' => $lien,
            'pv' => $all_pv
        ]);
        $this->load->view('templates/footer', $js);
    }
}
