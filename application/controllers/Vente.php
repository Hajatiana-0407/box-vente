<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("VenteModel", "vente");
        $this->load->model("AjoutProduitModel", "produit");
        $this->load->model('ClientsModel', 'client');
        $this->load->model('FactureModel', 'facture');
        $this->load->model('StockModel', 'stock');
        $this->load->model('ListeModel', 'liste');
        $this->load->model('Admin_model');
        $this->load->model("ModeModel", "mode");
    }


    // convertion 
    public   function covertion($all_unite = [], $min_qte = 0)
    {
        $by_unite = null;
        $reste = 0;
        // ajouter la valeur pour l'unité la plus petite


        $data = [];
        if (count($all_unite) > 0) {
            $data = [[
                'unite' => end($all_unite)->denomination,
                'quantite' => $min_qte,
                'reste' => $reste
            ]];
        } else {
            $data = [[
                'unite' => '',
                'quantite' => $min_qte,
                'reste' => $reste
            ]];
        }
        for ($i = count($all_unite) - 2; $i >= 0; $i--) {
            if (isset($all_unite[$i + 1])) {
                $element = $all_unite[$i];
                $unite = $element->denomination;

                // vérification si il y a un reste
                $reste = $min_qte % $all_unite[$i + 1]->formule;
                if ($reste != 0) {
                    if ($reste > 1) {
                        $reste = $reste . ' ' . $all_unite[$i + 1]->denomination . '(s)';
                    } else {
                        $reste = $reste . ' ' . $all_unite[$i + 1]->denomination;
                    }
                }

                $min_qte = intval($min_qte / $all_unite[$i + 1]->formule);

                $by_unite = [
                    'unite' => $unite,
                    'quantite' => $min_qte,
                    'reste' => $reste
                ];

                array_unshift($data, $by_unite);
            }
        }
        return $data;
    }


    public  function stock_texte($unite = [], $id = 0)
    {
        $texte = '';
        $concat = '+';

        for ($i = $id; $i < count($unite); $i++) {
            $element = $unite[$i];

            if ($i == $id) {
                // on affiche rien si la quantité est 0 
                if ($element["quantite"] > 0) {
                    $texte = $element["quantite"] . ' ' . $element["unite"];
                    if ($element["quantite"] > 1) {
                        // pour mettre le s
                        $texte = $element["quantite"] . ' ' . $element["unite"] . '(s)';
                    }
                    // sans unite 
                    if (empty($element["unite"])) {
                        $texte = $element["quantite"];
                    }
                }
            }

            if ($element["reste"] != 0) {
                // reste
                if ($element["quantite"] > 0) {
                    $texte .= $concat . ' ' . $element["reste"];
                } else {
                    $texte .= $element["reste"];
                }
            }
        }
        return $texte;
    }


    public function registreClient() {}



    public function getseries()
    {
        $this->jail();
        $id = $this->input->post('idpv');



        $numseries = $this->vente->getAllNumseie($id);
        if (count($numseries) == 0) {
            echo json_encode([
                'success' => false
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $numseries
            ]);
        }
    }

    public function rechercheClient()
    {
        $this->jail();
        $pv = $this->vente->getAllVente();
        $prod = $this->vente->getAllProd();

        $data['title'] = 'Vente';
        $data['css'] = 'vente.css';
        $js['js'] = 'vente.js';

        $keyword = strip_tags(trim($_GET['recherche']));

        $_POST['post'] = $keyword;
        // * pagination * // 
        $config = array();
        $config["base_url"] = base_url() . 'rechercheClient';
        $config["total_rows"] = count($this->client->searchClients($keyword, '', ''));
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
        $datapag['client'] = $this->client->searchClients($keyword, $config["per_page"], $start);
        // * pagination * // 


        $this->session->set_flashdata('vente_client', 'ok');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['vente' => true]);
        $this->load->view('vente', [
            'pv' => $pv,
            'prod' => $prod,
            'data' => $datapag,
            'post' => $_POST['post']
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function rechercher_mat_vente()
    {
        $this->jail();
        $ref = strip_tags(trim($this->input->post('ref')));
        $pv = strip_tags(trim($this->input->post('pv')));

        $num = trim($ref);

        $res = $this->vente->getVenteNum($num, $pv);

        // echo '<pre>' ; 
        // var_dump( $pv ) ;
        // echo '</pre>' ; die  ; 

        if (count($res) > 0) {
            if ($res[0]->etat_vente != 'Non vendu') {
                echo json_encode([
                    'success' => false,
                    'type' => 'vendu'
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'data' => $res[0]
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'type' => 'unexist'
            ]);
        }


        // $iteration = 0;
        // $data = [];
        // $all = $this->stock->getAll();

        // for ($i = 0; $i < count($all); $i++) {
        //     $qte = $this->stock->getQTE($all[$i]->refProduit, $all[$i]->idPointVente);
        //     $exist = false;
        //     for ($k = 0; $k < count($data); $k++) {
        //         if (ucfirst($data[$k]->refProduit) == ucfirst($all[$i]->refProduit)) {
        //             if ($data[$k]->idPointVente == $all[$i]->idPointVente) {
        //                 $exist = true;
        //             }
        //         }
        //     }
        //     if (!$exist) {
        //         $data[$iteration] = $all[$i];
        //         $data[$iteration]->qte = $qte[0]->qte;
        //         $iteration++;
        //     }
        // }


        // $vente = $this->stock->vente();
        // for ($v = 0; $v < count($vente); $v++) {
        //     for ($i = 0; $i < count($data); $i++) {
        //         if (ucfirst($data[$i]->refProduit) == ucfirst($vente[$v]->refProduit) && $data[$i]->idPointVente == $vente[$v]->idPointVente) {
        //             $data[$i]->qte -= $vente[$v]->qteProduit;
        //         }
        //     }
        // }

        // for ($a = 0; $a < count($data); $a++) {
        //     if (ucfirst($data[$a]->refProduit) == ucfirst($ref) && $data[$a]->adressPv == $pv) {
        //         $quantiter = $data[$a]->qte;
        //     }
        // }
    }

    public function getPriceByOffre()
    {
        $this->jail();
        $ref = $this->input->post('ref');
        $data = $this->v_model->price($ref);
        echo json_encode(['prix' => $data[0]->prixOffre, 'jour' => $data[0]->dureeOffre, 'id' => $data[0]->idOffre]);
    }

    public function getpv()
    {
        $this->jail();
        $pv = $this->vente->selectPv();
        echo json_encode($pv);
    }
    public function getPriceByMateriel()
    {
        $ref = trim($this->input->post('ref'));
        $data = $this->v_model->getMatByRef($ref);

        if ($_SESSION['user_type'] == 'commercial') {
            $idCom = $_SESSION['idCommercial'];
            $res = $this->v_model->getPvOfCom($idCom);
            $res = $res[0]->adressePointVente;
        } else {

            $idCom = $_SESSION['idAdminPv'];
            $res = $this->v_model->getPvOfAdminPv($idCom);
            $res = $res[0]->adressePointVente;
        }

        $data_balance_actu = $this->m_stock->getBalanceOfSpecificPV($res);

        $mat = $data_balance_actu[0]->materiel;

        if (!empty($mat)) {
            $reste = $mat[0]->reste;
        } else {
            $reste = 0;
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->stock = $reste;
        }

        echo json_encode([
            'data' => $data,
        ]);
    }

    public function getmodepaiement()
    {
        echo json_encode($this->v_model->getMP());
    }

    public function pv()
    {
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'commercial') {
                $pv = $this->poste->selectPostByIdCommercial($_SESSION['idCommercial']);
            } elseif ($_SESSION['user_type'] == 'adminPv') {
                $pv = $this->adminPv->selectAdminPvByIdAdminPv($_SESSION['idAdminPv']);
            }
        }

        echo json_encode($pv);
    }

    public function get()
    {
        $reference = $this->input->post('reference');
        $designation = $this->input->post('designation');
        $prix = $this->input->post('prix');
        $quantiter = $this->input->post('quantiter');
        $montant = $this->input->post('montant');
        $numClient = $this->input->post('numClient');
        $modePay = $this->input->post('modePay');
        $adressePointVente = $this->input->post('adressePointVente');
        $idMateriel = $this->input->post('idMateriel');
    }

    public function validate()
    {
        $datas = [];
        if (isset($_POST['data'])) {
            $datas = $_POST['data'];
        }

        foreach ($datas  as $key => $data) {
            $datas[$key]['idadmin'] = $_SESSION['idadmin'];
        }
        $this->vente->insertPanier($datas);
        $this->session->set_flashdata('success', true);
    }

    public function facturation()
    {
        $numClient = '';
        if (isset($_POST['numClient']) && $_POST['numClient'] != '') {
            $numClient = strip_tags(trim($this->input->post('numClient')));
            $numClient = str_replace(' ', '', $numClient);
        }

        $tva = '';
        if (isset($_POST['tva']) && $_POST['tva'] != '') {
            $tva = strip_tags(trim($this->input->post('tva')));
        }
        $id_pointdevente = '';
        if (isset($_POST['id_pointdevente']) && $_POST['id_pointdevente'] != '') {
            $id_pointdevente = (int) strip_tags(trim($this->input->post('id_pointdevente')));
        }

        $montant_total = '';
        if (isset($_POST['montant_total']) && $_POST['montant_total'] != '') {
            $montant_total = trim(strip_tags($_POST['montant_total']));
        }

        $montant_payer = '';
        if (isset($_POST['montant_payer']) && $_POST['montant_payer'] != '') {
            $montant_payer = trim(strip_tags($_POST['montant_payer']));
        }
        $idmode = '';
        if (isset($_POST['idmode']) && $_POST['idmode'] != '') {
            $idmode = trim(strip_tags($_POST['idmode']));
        }
        $frais = '';
        if (isset($_POST['frais']) && $_POST['frais'] != '') {
            $frais = trim(strip_tags($_POST['frais']));
        }


        $lieu_livraison_path = '';

        // GESTION DES FICHIERS
        $upload_dir = FCPATH . 'public/upload/clients/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (isset($_FILES['lieu_livraison']) && $_FILES['lieu_livraison']['error'] == 0) {
            $ext = pathinfo($_FILES['lieu_livraison']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('lieu_livraison-') . '.' . $ext;
            $dest = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['lieu_livraison']['tmp_name'], $dest)) {
                $lieu_livraison_path = 'public/upload/clients/' . $filename;
            }
        }


        $idClient = $this->client->getclientByNumero($numClient);


        $lastFacture = $this->vente->getLastFacture();

        if (!empty($lastFacture)) {
            $last = $lastFacture[0]->Facture;
            $explodeLastFacture = explode("-", $last);
            $numAI = intval($explodeLastFacture[1]);
            $numAI++;
            $numFacture = date("Y") . "-" . str_pad($numAI, 5, 0, STR_PAD_LEFT);
        } else {
            $numFacture = date("Y") . "-" . str_pad("1", 5, 0, STR_PAD_LEFT);
        }


        if (count($idClient) > 0) {
            if (isset($_SESSION['id_user'])) {
                $data = [
                    'Facture' => $numFacture,
                    'telClient' => my_trim($idClient[0]->telClient),
                    'idUser' => $_SESSION['id_user'],
                    'idPointVente' => $id_pointdevente,
                    'idadmin' => $_SESSION['idadmin'],
                    'tva' => $tva,
                    'montant_total' => $montant_total,
                    'montant_payer' => $montant_payer,
                    'idModePaiement' => $idmode,
                    'frais' => $frais , 
                    'lieu_livraison' => $lieu_livraison_path
                ];
            } else {
                $data = [
                    'Facture' => $numFacture,
                    'telClient' => my_trim($idClient[0]->telClient),
                    'idPointVente' => $id_pointdevente,
                    'idadmin' => $_SESSION['idadmin'],
                    'tva' => $tva,
                    'montant_total' => $montant_total,
                    'montant_payer' => $montant_payer,
                    'idModePaiement' => $idmode,
                    'frais' => $frais , 
                    'lieu_livraison' => $lieu_livraison_path
                ];
            }
        } else {
            if (isset($_SESSION['id_user'])) {
                $data = [
                    'Facture' => $numFacture,
                    'telClient' => $numClient,
                    'idUser' => $_SESSION['id_user'],
                    'idPointVente' => $id_pointdevente,
                    'idadmin' => $_SESSION['idadmin'],
                    'tva' => $tva,
                    'montant_total' => $montant_total,
                    'montant_payer' => $montant_payer,
                    'idModePaiement' => $idmode,
                    'frais' => $frais , 
                    'lieu_livraison' => $lieu_livraison_path
                ];
            } else {
                $data = [
                    'Facture' => $numFacture,
                    'telClient' => $numClient,
                    'idPointVente' => $id_pointdevente,
                    'idadmin' => $_SESSION['idadmin'],
                    'tva' => $tva,
                    'montant_total' => $montant_total,
                    'montant_payer' => $montant_payer,
                    'idModePaiement' => $idmode,
                    'frais' => $frais , 
                    'lieu_livraison' => $lieu_livraison_path
                ];
            }
        }

        $idfacture = $this->vente->insertVente($data);
        echo json_encode([
            'facture' => $numFacture,
            'idfacture' => $idfacture,
        ]);
    }

    public function verifyClient()
    {
        $num = $this->input->post('num-client');
        $data = $this->v_model->getClientByNum($num);

        if (count($data) > 0) {
            echo json_encode([
                'exist' => true,
                'data' => $data,
            ]);
        } else {
            echo json_encode([
                'exist' => false,
                'data' => [],
            ]);
        }
    }


    public function recupLignes()
    {
        $key = $this->input->post('key');
        $data = $this->v_model->getLigneWithKey($key);

        $nPagesLigne = ceil($this->v_model->countLigneWithKey($key) / LIMITE);
        $currentLigne = 1;


        $affichage = "<table class='table table-striped mt-3'>";
        $affichage .= "<thead class='bg-primary'>";
        $affichage .= "<tr><th></th><th class='text-white'>Référence ligne</th><th class='text-white'>Numéro de ligne</th><th class='text-white'>Capacité</th></tr>";
        $affichage .= "</thead>";
        $affichage .= "</tbody>";

        for ($i = 0; $i < count($data); $i++) {
            $affichage .= "<tr>";
            $affichage .= "<td><input type='checkbox' class='checkbox' data-numligne='" . $data[$i]->numeroPuce . "' data-cap='" . $data[$i]->capacite . "' status='off' data-ref='" . $data[$i]->refLigne . "'></td>";
            $affichage .= "<td>" . $data[$i]->refLigne . "</td>";
            $affichage .= "<td>" . $data[$i]->numeroPuce . "</td>";
            $affichage .= "<td>" . $data[$i]->capacite . "</td>";

            $affichage .= "</tr>";
        }

        $affichage .= "</tbody>";
        $affichage .= "</table>";


        if ($nPagesLigne > 1) {
            $affichage .= '
            <div class="__pagination">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li>';

            if ($currentLigne == 1) {
                $affichage .= '
                                <span class="__disabled" aria-label="Previous">
                                    <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                </span>';
            } else {
                $affichage .= '
                                <button onclick="paginateLigne(event,this)" type="button" data-href="';
                $affichage .= base_url("vente/ligne/filtre/" . ($currentLigne - 1));
                $affichage .= '" aria-label="Previous" data-key="' . $key . '">
                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                </button>
                                ';
            }

            $affichage .= '</li>';

            for ($i = 1; $i <= $nPagesLigne; $i++) {
                $affichage .= '<li class="';

                if ($i == $currentLigne) {
                    $affichage .= 'active';
                }

                $affichage .= '">

                                <button onclick="paginateLigne(event,this)" type="button" data-href="' . base_url('vente/ligne/filtre/' . $i) . '" data-key="' . $key . '">' . $i . '</button>

                            </li>';
            }

            $affichage .= '<li>';

            if ($currentLigne == $nPagesLigne) {
                $affichage .= '
                            <span class="__disabled" aria-label="Next">
                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                            </span>';
            } else {

                $affichage .= '
                                <button onclick="paginateLigne(event,this)" type="button" data-href="' . base_url('vente/ligne/filtre/' . ($currentLigne + 1)) . '" aria-label="Next" data-key="' . $key . '">
                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                </button>
                            ';
            }
            $affichage .=
                '</li>
                    </ul>
                </nav>
            </div>';
        }


        echo $affichage;
    }

    public function searchNumSerie()
    {
        $num = strip_tags(trim($_POST['num']));
        $pv = strip_tags(trim($_POST['pv']));


        $res = $this->vente->getNumSerie($pv, $num);

        $iteration = 0;
        $data = [];
        $all = $this->stock->getAll();

        for ($i = 0; $i < count($all); $i++) {
            $qte = $this->stock->getQTE($all[$i]->refProduit, $all[$i]->idPointVente);
            $exist = false;
            for ($k = 0; $k < count($data); $k++) {
                if (ucfirst($data[$k]->refProduit) == ucfirst($all[$i]->refProduit)) {
                    if ($data[$k]->idPointVente == $all[$i]->idPointVente) {
                        $exist = true;
                    }
                }
            }
            if (!$exist) {
                $data[$iteration] = $all[$i];
                $data[$iteration]->qte = $qte[0]->qte;
                $iteration++;
            }
        }
        $quantiter  = 0;
        for ($a = 0; $a < count($data); $a++) {
            if ($data[$a]->adressPv == $pv) {
                $quantiter = $data[$a]->qte;
            }
        }

        if (count($res) > 0 || $res[0]->etat_vente == 'Nom Vendu') {
            echo json_encode([
                'success' => $res[0]->etat_vente,
                'data' => $res,
                'qte' => $quantiter
            ]);
        } else {
            echo json_encode([
                'success' => 'qsdqsdsq',
                'data' => ''
            ]);
        }
    }



    public function panierAffiche($page = '')
    {
        $pv = $this->input->post('pv');
        if (isset($_SESSION['pv'])) {
            $pv = $_SESSION['pv'];
        }

        if ($pv == '') {
            $point_de_vente = $this->vente->get_the_pv();
        } else {
            $point_de_vente = $this->vente->get_the_pv($pv);
        }





        if (count($point_de_vente) == 0) {
            $pv_panier = '';
        } else {
            $pv_panier = $point_de_vente[0];
        }





        if (isset($_POST['recherche'])) {
            $keyword = strip_tags(trim($_POST['recherche']));
            if ($keyword == 'Recherche des Numeros :') {
                $keyword = '';
            }
        } else {
            $keyword  = '';
        }


        if ($page == '') {
            $page = 0;
        }

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }



        $iteration = 0;
        $data = [];
        $all = $this->stock->getAll();






        for ($i = 0; $i < count($all); $i++) {
            $qte = $this->stock->getQTE($all[$i]->refProduit, $all[$i]->idPointVente);
            $exist = false;
            for ($k = 0; $k < count($data); $k++) {
                if (ucfirst($data[$k]->refProduit) == ucfirst($all[$i]->refProduit)) {
                    if ($data[$k]->idPointVente == $all[$i]->idPointVente) {
                        $exist = true;
                    }
                }
            }
            if (!$exist) {
                $data[$iteration] = $all[$i];
                $data[$iteration]->qte = $qte[0]->qte;
                $iteration++;
            }
        }



        if ($keyword != '') {
            $recherche = [];
            for ($i = 0; $i < count($data); $i++) {
                if (strpos(ucfirst($data[$i]->refProduit), ucfirst($keyword)) > -1 ||   strpos(ucfirst($data[$i]->designation), ucfirst($keyword)) > -1 ||  strpos(ucfirst($data[$i]->adressPv), ucfirst($keyword)) > -1) {
                    if (isset($_SESSION['pv'])) {
                        if ($data[$i]->idPointVente == $_SESSION['pv'])
                            $recherche[] = $data[$i];
                    } else {
                        $recherche[] = $data[$i];
                    }
                }
            }
        }




        $temp = [];
        if ($pv_panier != '' && $keyword != '') {
            for ($i = 0; $i < count($recherche); $i++) {
                if ($recherche[$i]->idPointVente == $pv_panier->idPointVente) {
                    $temp[] = $recherche[$i];
                }
            }
            $recherche = $temp;
        } else if ($pv_panier != '') {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]->idPointVente == $pv_panier->idPointVente) {
                    $temp[] = $data[$i];
                }
            }
            $data = $temp;
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->numserie = $this->stock->getAllNumSerie($data[$i]->refProduit, $data[$i]->idPointVente);
        }

        $temporairement = [];
        for ($i = 0; $i < count($data); $i++) {
            if (count($data[$i]->numserie) != 0) {
                $temporairement[] = $data[$i];
            }
        }
        $data = $temporairement;


        $vente = $this->stock->vente();
        for ($v = 0; $v < count($vente); $v++) {
            for ($i = 0; $i < count($data); $i++) {
                if (ucfirst($data[$i]->refProduit) == ucfirst($vente[$v]->refProduit) && $data[$i]->idPointVente == $vente[$v]->idPointVente) {
                    $data[$i]->qte -= $vente[$v]->qteProduit;
                }
            }
        }




        // $sousP = $this->appro->getSousPr($date, $ref);

        if ($keyword == "") {
            $nbr = count($data);
        } else if ($pv_panier == '') {
            $nbr = count($recherche);
        }

        $stock = [];


        if ($keyword == '') {
            for ($i =  $start; $i < $start + PAGINATION; $i++) {
                if ($data[$i] != '') {
                    if (isset($_SESSION['pv'])) {
                        $pv = $_SESSION['pv'];
                        if ($data[$i]->idPointVente == $pv) {
                            $stock[] = $data[$i];
                        }
                    } else {
                        if ($data[$i]->idPointVente ==  $pv_panier->idPointVente) {
                            $stock[] = $data[$i];
                        }
                    }
                }
            }
        } else {
            for ($i =  $start; $i < $start + PAGINATION; $i++) {
                if (count($recherche) != 0) {
                    if ($recherche[$i] != null)
                        $stock[] = $recherche[$i];
                } else {
                    $stock = $data;
                }
            }
        }




        if ($keyword != '') {
            $temporaire = [];
            if (count($recherche) == 0) {
                for ($i = 0; $i < count($stock); $i++) {
                    foreach ($stock[$i]->numserie as $num) {
                        if ($num->numero_serie == $keyword) {
                            $temporaire[] = $stock[$i];
                        }
                    }
                }
                $stock = $temporaire;
            }
        }

        for ($i = 0; $i < count($stock); $i++) {
            $prix = $this->vente->getPrixPanier($stock[$i]->refProduit);
            $stock[$i]->prix_unitaire = $prix->prixProduit;
        }

        if ($nbr <= PAGINATION) {
            echo json_encode([
                'data' => $stock,
                'page' => $page,
                'pagin' => 'Non',
                'nbr' => $nbr,
                'pv' => $point_de_vente,
                'idpv' => $pv_panier->idPointVente
            ]);
        } else {
            $nbr_data = ceil($nbr / PAGINATION);
            echo json_encode([
                'data' => $stock,
                'page' => $page,
                'pagin' => 'oui',
                'nbr' => $nbr_data,
                'pv' => $point_de_vente,
                'idpv' => $pv_panier->idPointVente
            ]);
        }
    }



    // ******************** UTILE ***************************** //
    public function index()
    {
        $this->jail();
        // $this->jail() ; 
        $data['title'] = 'Vente';
        $data['css'] = 'vente.css';
        $js['js'] = 'vente.js';

        $pv = $this->vente->getAllVente();

        $prod = $this->vente->getAllProd();

        $modes = $this->mode->AllMode();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['vente' => true]);


        if ($this->session->userdata('show_stock_alert')) {
            // var_dump('abony') ; die  ; 
            $this->session->unset_userdata('show_stock_alert');
            $appros = $this->stock->getAll_seuil();
            $stocks = $this->stock->getStock($appros);

            $all_pv = $this->stock->pv_stock();


            $data_to_send =   [
                'pv' => $pv,
                'prod' => $prod,
                'modes' => $modes,
                'time_alert' => true
            ];

            if (count($stocks) > 0) {
                $data_to_send['stock_alerts'] = $stocks;
            }
            $this->load->view('vente', $data_to_send);
        } else {
            $this->load->view('vente', [
                'pv' => $pv,
                'prod' => $prod,
                'modes' => $modes,
            ]);
        }

        $this->load->view('templates/footer', $js);
    }

    public function getMode()
    {
        $modes = $this->mode->AllMode();

        echo json_encode([
            'mode' => $modes
        ]);
    }


    public function getStock()
    {
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST["idProduit"] != '') {
            $idProduit = trim(strip_tags($_POST['idProduit']));
        }
        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST["id_pv"] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }

        $quantite =  $this->vente->getStock($idProduit, $id_pv);

        if ($quantite > 0) {
            echo json_encode([
                'success' => true,
                'quantite' => $quantite
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }


    public function recheche_prix()
    {
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST['idProduit'] != '') {
            $idProduit = trim(strip_tags($_POST['idProduit']));
        }
        $prix = $this->vente->getPrixUnite($idProduit);
        if (count($prix) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prix[0],
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $prix,
            ]);
        }
    }
    public function getPrixUnite()
    {
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST['idProduit'] != '') {
            $idProduit = trim(strip_tags($_POST['idProduit']));
        }

        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST['id_pv'] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }


        $prixUnites = $this->vente->getPrixUnite($idProduit);




        // verification du quantiter disponnible 
        // $quantite =  $this->vente->getStock($idProduit, $id_pv);

        echo '<pre>';
        var_dump($prixUnites);
        echo '</pre>';
        die;
        if (count($prixUnites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prixUnites,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $prixUnites,
            ]);
        }
    }
    public function getStock__()
    {
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST['idProduit'] != '') {
            $idProduit = trim(strip_tags($_POST['idProduit']));
        }

        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST['id_pv'] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }


        $prixUnites = $this->vente->getPrixUnite($idProduit);




        // verification du quantiter disponnible 
        $quantite =  $this->vente->getStock($idProduit, $id_pv);

        echo '<pre>';
        var_dump($prixUnites);
        echo '</pre>';
        die;
        if (count($prixUnites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prixUnites,
                'quantite' => $quantite
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $prixUnites,
                'quantite' => $quantite
            ]);
        }
    }

    public function unite()
    {
        // commande 
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST['idProduit'] != '') {
            $idProduit = trim(strip_tags($_POST['idProduit']));
        }


        $unites = $this->vente->unites($idProduit);

        if (count($unites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $unites,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => []
            ]);
        }
    }

    public function tiquet($reference)
    {
        $info = $this->liste->getAllInfo($reference);
        $idfacture = '';
        if (count($info) > 0) {
            $idfacture = $info[0]->idfacture;
        }

        $tous = $this->liste->getFacture($idfacture);

        $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


        $total['ht'] = $tous->montant_payer;
        $total['ht'] += $tous->frais;
        $TVA = $total['ht'] * 20 / 100;
        $total['tva'] = $TVA;
        $total['ttc'] = $TVA + $total['ht'];

        $this->load->library('Pdftiquet');

        $pdf = new Pdftiquet("P", "mm", [80, 100]);

        $pdf->set_admin($admin);
        $pdf->set_facture($tous);
        $pdf->set_total($total);
        $pdf->AddPage();

        // Appeler la méthode Body pour ajouter le contenu principal
        $pdf->Body();

        $pdf->Output();
    }

    public function facture($fact)
    {
        $info = $this->liste->getAllInfo($fact);
        $idfacture = '';
        if (count($info) > 0) {
            $idfacture = $info[0]->idfacture;
        }

        $fact = $this->liste->getFacture($idfacture);

        $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


        $total['ht'] = $fact->montant_payer;
        $total['ht'] += $fact->frais;
        $TVA = $total['ht'] * 20 / 100;
        $total['tva'] = $TVA;
        $total['ttc'] = $TVA + $total['ht'];


        $this->load->library('Facturevente');


        $pdf = new Facturevente("P", "mm", "A4");
        $pdf->set_admin($admin);
        $pdf->set_facture($fact);
        $pdf->set_total($total);
        $pdf->AddPage();
        $pdf->corps();
        $pdf->LastPage();

        $pdf->Output();
    }


    // ******************** UTILE ***************************** //
}
