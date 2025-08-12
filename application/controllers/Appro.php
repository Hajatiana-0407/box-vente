<?php

class Appro extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApproModel', 'appro');
        $this->load->model('PointVente_model', 'pv');
        $this->load->model('CodeModel', 'code');
        $this->load->model('UniteModel', 'unite');
        $this->load->model('FournisseurModel', 'fournisseur');
        $this->load->model('ExportationModel', 'exportation');
    }



    // ************ utile *************** //
    public function index()
    {
        $page = 1;
        $this->jail();

        $nPages = ceil($this->appro->get_count() / PAGINATION);
        $current = 1;

        $datas = $this->appro->get_authors($page);

        $fournisseurs = $this->fournisseur->getAll();

        $data['title'] = 'Approvisionnement';
        $data['css'] = 'appro.css';
        $js['js'] = 'appro.js';

        $pv = $this->pv->getAllPv();
        $mat = $this->appro->getAllMat();



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('appro', [
            'datas' => $datas,
            'pv' => $pv,
            'mat' => $mat,
            'nPages' => $nPages,
            'fournisseurs' => $fournisseurs,
            'current' => 1
        ]);
        $this->load->view('templates/footer', $js);
    }


    public function search($page = 1)
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
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '')
            $heure_fin .= '23:59:59';



        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date;
        $mot = strip_tags(trim($_POST['recherche']));
        $_POST['mot'] = $mot;

        $recherche = $this->appro->searchDate($page,  $date_debut, $date_fin, $mot);

        $nPages = ceil($this->appro->searchDate($page,  $date_debut, $date_fin, $mot, true) / PAGINATION);
        $current = $page;

        $pv = $this->pv->getAllPv();
        $data['title'] = 'Appro';
        $data['css'] = 'appro.css';
        $js['js'] = 'appro.js';

        $mat = $this->appro->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ["appro" => true]);
        $this->load->view('templates/tete');
        $this->load->view('appro', [
            'datas' => $recherche,
            'pv' => $pv,
            'mat' => $mat,
            'nPages' => $nPages,
            'current' => $current
        ]);

        $this->load->view('templates/footer', $js);
    }
    public function page($page = 1)
    {
        $this->jail();

        $nPages = ceil($this->appro->get_count() / PAGINATION);
        $current = $page;

        $datas = $this->appro->get_authors($page);

        $data['title'] = 'Approvisionnement';
        $data['css'] = 'appro.css';
        $js['js'] = 'appro.js';

        $pv = $this->pv->getAllPv();
        $mat = $this->appro->getAllMat();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('appro', [
            'datas' => $datas,
            'pv' => $pv,
            'mat' => $mat,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $js);
    }


    public function recherche_produit()
    // vente 
    {
        $ref = '';
        if (isset($_POST['ref']) && $_POST['ref'] != '') {
            $ref = htmlspecialchars(trim($_POST['ref']));
        }
        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST['id_pv'] != '') {
            $id_pv = htmlspecialchars(trim($_POST['id_pv']));
        }

        $produit = $this->appro->getProduitByRef($ref);

        if (count($produit)) {
            $produit = $produit[0] ; 
            if ( $produit->type == 'telephone' ){
                $series = $this->appro->getAllNumero($produit->idProduit , $id_pv );
                echo json_encode([
                    'success' => true,
                    'produit' => $produit,
                    'type' => 'reference', 
                    'series' => $series  
                ]);
            }else {
                echo json_encode([
                    'success' => true,
                    'produit' => $produit,
                    'type' => 'reference'
                ]);
            }
            
        } else {
            $data = $this->appro->getProduitByNumero($ref  , $id_pv );

            if (count($data)) {
                echo json_encode([
                    'success' => true,
                    'produit' => $data[0],
                    'type' => 'numero'
                ]);
            } else {
                echo json_encode(['success' => false, 'data' => '']);
            }
        }
    }


    public function registerAppro()
    {
        $idProduit = '';
        if (isset($_POST['idProduit']) && $_POST['idProduit'] != '') {
            $idProduit = trim(strip_tags($this->input->post('idProduit')));
        }

        $pv = '';
        if (isset($_POST['pv']) && $_POST['pv'] != '') {
            $pv = trim(strip_tags($this->input->post('pv')));
        }
        $fournisseur = '';
        if (isset($_POST['fournisseur']) && $_POST['fournisseur'] != '') {
            $fournisseur = trim(strip_tags($_POST['fournisseur']));
        }
        $prix = '';
        if (isset($_POST['prix']) && $_POST['prix'] != '') {
            $prix = trim(strip_tags($_POST['prix']));
        }
        $quantite = 1 ;
        if (isset($_POST['quantite']) && $_POST['quantite'] != '') {
            $quantite = trim(strip_tags($_POST['quantite']));
        }



        $couleur = '--';
        if (isset($_POST['couleur']) && $_POST['couleur'] != '') {
            $couleur = trim(strip_tags($_POST['couleur']));
        }
        $numSerie = '';
        if (isset($_POST['numSerie']) && $_POST['numSerie'] != '') {
            $numSerie = trim(strip_tags($_POST['numSerie']));
        }
        $imei1 = '';
        if (isset($_POST['imei1']) && $_POST['imei1'] != '') {
            $imei1 = trim(strip_tags($_POST['imei1']));
        }

        $imei2 = '';
        if (isset($_POST['imei2']) && $_POST['imei2'] != '') {
            $imei2 = trim(strip_tags($_POST['imei2']));
        }

        if ($quantite > 0) {
            $montant = $prix * $quantite;
        } else {
            $montant = $prix;
        }

        if (empty($pv)) {
            $this->session->set_flashdata('adrres', 'Ajout réussie');
        } else {

            if ($pv != '' && $idProduit != '') {
                $date = date("Y/m/d H:i:s");
                $data = [
                    'idProduit' => $idProduit,
                    'idPointVente' => $pv,
                    'dateAppro' => $date,
                    'idadmin' => $_SESSION['idadmin'],
                    'prix_unitaire' => $prix,
                    'montant' => $montant,
                    'quantite' => $quantite,
                    'numero' => $numSerie,
                    'imei1' => $imei1,
                    'imei2' => $imei2,
                    'couleur' => $couleur
                ];

                if ($fournisseur != 0 && $fournisseur != '') {
                    $data['idfournisseur'] = $fournisseur;
                }


                $idappro = $this->appro->insertAppro($data);

                // if ( $fournisseur != 0 && $fournisseur !=''){
                $this->appro->depenseAppro($prix, $pv, '', $idappro);
                // }
                $this->session->set_flashdata('added', 'ok');
            } else {
                $this->session->set_flashdata('erreur', 'Veuillez vérifier les données que vous avez saisies.');
            }
        }
        redirect('appro');
    }

    public function cmregister()
    {
        $this->jail();
        $idPointVente = '';
        if (isset($_POST['idPointVente']) && $_POST['idPointVente'] != '') {
            $idPointVente = trim(strip_tags($_POST['idPointVente']));
        }
        $idcommande = '';
        if (isset($_POST['idcommande']) && $_POST['idcommande'] != '') {
            $idcommande = trim(strip_tags($_POST['idcommande']));
        }
        $montant_total = '';
        if (isset($_POST['montant_total']) && $_POST['montant_total'] != '') {
            $montant_total = trim(strip_tags($_POST['montant_total']));
        }
        $idfournisseur = '';
        if (isset($_POST['idfournisseur']) && $_POST['idfournisseur'] != '') {
            $idfournisseur = trim(strip_tags($_POST['idfournisseur']));
        }
        $frais = '';
        if (isset($_POST['frais']) && $_POST['frais'] != '') {
            $frais = trim(strip_tags($_POST['frais']));
        }

        $teste = $this->appro->teste_commande($idcommande);

        if (count($teste) == 0) {
            $this->appro->depenseAppro(($montant_total + $frais), $idPointVente, $idcommande);

            $datas = [];
            if (isset($_POST['datas'])) {
                $datas = $_POST['datas'];
            }

            foreach ($datas as $key => $data) {
                $datas[$key]["idPointVente"] = $idPointVente;
                $datas[$key]["idadmin"] = $_SESSION['idadmin'];
            }

            $this->appro->commande_recue($idcommande);
            $this->appro->insert_batch($datas);
        }

        $_POST["reception"] = true;

        $this->session->set_flashdata('added', 'ok');
        // echo json_encode([
        //     'success' => true 
        // ]) ; 
    }

    public function deleteAppro()
    {
        $id = $this->input->post('idappro');

        $data = $this->appro->deleteAppro($id);
        $this->appro->deleteDepense($id);

        $this->session->set_flashdata('delete', 'Ajout réussie');

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }





    private function convertion($idunite, $qte)
    {
        // convertire la quantite d'une unite en l'unite assicier le plus bas 
        $test = true;
        while ($test && $idunite != '') {
            $response = $this->unite->convertion($idunite);
            if (count($response) > 0) {
                $idunite = $response[0]->idunite;
                $qte = $qte * $response[0]->formule;
            } else {
                $test = false;
            }
        };

        return (int)$qte;
    }


    public function verifyNumserie()
    {
        $numero = '';
        if (isset($_POST['numero']) && $_POST['numero'] != '') {
            $numero = trim(strip_tags($_POST['numero']));
        }
        $data = [];
        if ($numero != '') {
            $data = $this->appro->verifyNumserie($numero);
        }

        if (count($data) == 0 && $numero != '') {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }
    public function verifyImei()
    {
        $imei = '';
        if (isset($_POST['imei']) && $_POST['imei'] != '') {
            $imei = trim(strip_tags($_POST['imei']));
        }

        $data = [];
        if ($imei != '') {
            $data = $this->appro->verifyImei($imei);
        }

        if (count($data) == 0 && $imei != '') {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }






    // ************ utile *************** //
}
