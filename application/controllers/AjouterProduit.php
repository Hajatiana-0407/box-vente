 <?php
    use PhpParser\Node\Expr\PostDec;
    defined('BASEPATH') or exit('No direct script access allowed');

    class AjouterProduit extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model("AjoutProduitModel", "produit");
            $this->load->model('PrixModel', 'p_model');
            $this->load->model('UniteModel', 'unite');
        }



        public function ajouterProduit()
        {
            // * pagination * // 
            $config = array();
            $config["base_url"] = base_url() . 'produit';
            $config["total_rows"] = $this->produit->get_count();
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
            $datapag['produit'] = $this->produit->get_authors($config["per_page"], $start);


            // * pagination * // 
            $this->jail();
            $data['title'] = 'Produit';
            $data['css'] = 'ajout.css';
            $js['js'] = 'ajout.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['client' => true]);
            $this->load->view('templates/tete');
            $this->load->view('ajouterProduit', [
                'data' => $datapag
            ]);
            $this->load->view('templates/footer', $js);
        }
        public function register()
        {
            $reference = '';
            if (isset($_POST['referenceMat']) && $_POST['referenceMat'] != '') {
                $reference = ucfirst(strip_tags(trim($_POST['referenceMat'])));
            }

            $designation = '';
            if (isset($_POST['designationMat']) && $_POST['designationMat'] != '') {
                $designation = strip_tags(trim($_POST['designationMat']));
            }
            $type = '';
            if (isset($_POST['type']) && $_POST['type'] != '') {
                $type = strip_tags(trim($_POST['type']));
            }
            $fiche = '';
            if (isset($_POST['fiche']) && $_POST['fiche'] != '') {
                $fiche = strip_tags(trim($_POST['fiche']));
            }

            $unite_parent = '';
            if (isset($_POST['unite_parent']) && $_POST['unite_parent'] != '') {
                $unite_parent =  trim(strip_tags($_POST['unite_parent']));
            }
            $seuil = '';
            if (isset($_POST['seuil']) && $_POST['seuil'] != '') {
                $seuil =  trim(strip_tags($_POST['seuil']));
            }
            $seuil_min = '';
            if (isset($_POST['seuil_min']) && $_POST['seuil_min'] != '') {
                $seuil_min =  trim(strip_tags($_POST['seuil_min']));
            }
            $identification_seul = '';
            if (isset($_POST['seul_unite']) && $_POST['seul_unite'] != '') {
                $identification_seul =  trim(strip_tags($_POST['seul_unite']));
            }


            $idunite_seuil = 0;

            $filename = $_FILES['photo']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $unique_name = time() . '_' . uniqid('produit_', true) . '_' . $_SESSION['idadmin'] . '.' . $ext;

            $photo = move_uploaded_file($_FILES['photo']['tmp_name'], 'public/upload/' . $unique_name);

            $ref = $this->produit->verifRefProd($reference);

            if (count($ref) > 0) {
                $this->session->set_flashdata('designation', 'Ajout réussie');
            } else {
                
                $data_insert = [
                    "refProduit" => $reference,
                    "designation" => $designation,
                    "type" => $type,
                    "fiche" => $fiche,
                    'idadmin' => $_SESSION['idadmin'],
                    'seuil' => $seuil,
                    'seuil_min' => $seuil_min
                ];

                if ($ext != '') {
                    $data_insert["photo"] = 'upload/' . $unique_name;
                } else {
                    $data_insert["photo"] = 'upload/';
                }
                $idproduit = $this->produit->insertProduit($data_insert);



                // ajouter l'idunite du seuli d'alert dans le produit inserer
                $this->produit->updateUniteseuil($idproduit,  $idunite_seuil);
                $this->session->set_userdata('produit_add', 'reussi');

                // var_dump( $idunite_seuil ) ; die  ; 
            }

            redirect('produit');
        }
        public function DonnerProduit()
        {
            $id = $this->input->post('idProduit');
            $data = $this->produit->getProduitById($id);


            // verification s'i deje utilise
            $is_used = $this->produit->is_used($id);


            if (count($data) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $data[0],
                    'is_used' => $is_used
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                ]);
            }
        }

        public function deleteProd()
        {
            $id = $this->input->post('id');

            $this->jail();

            

            // prix 
            $this->produit->deletePrix($id);

            // // // vente et panier
            $this->produit->deleteVente($id);

            // // // commande et panier
            // $this->produit->deletecommande($id);

            // // // Proforma et panier
            $this->produit->deleteProforma($id);
            
            // // // Transfert
            $this->produit->deleteTransfert($id);

            // // appro et depense liée a cette approvisionnement
            $this->produit->deleteAppro($id);

            // // effacer l'unite
            // $this->unite->deleteByproduit($id);


            // Produit
            $this->produit->deleteProduit($id);


            $this->session->set_userdata('delete', 'Erreur de l\'ajout');

            echo json_encode([
                'success' => true,
                'exist' => false,
            ]);
        }

        public function editProd()
        {
            $photo = $_FILES['photo'];
            $id = $this->input->post('id');



            // $iduniter = $_POST['idunite'];

            // $idunites = explode(',', $iduniter);


            $unite_parent = '';
            if (isset($_POST['unite_parent']) && $_POST['unite_parent'] != '') {
                $unite_parent = trim(strip_tags($_POST['unite_parent']));
            }
            $reference = '';
            if ($this->input->post('reference_modif') != null) {
                $reference = strip_tags(trim($this->input->post('reference_modif')));
            }
            $designation = '';
            if ($this->input->post('designation_modif') != null) {
                $designation = strip_tags(trim($this->input->post('designation_modif')));
            }

            $type = '';
            if ($this->input->post('type') != null) {
                $type = strip_tags(trim($this->input->post('type')));
            }

            if ($this->input->post('fiche_modif') != null) {
                $fiche = strip_tags(trim($this->input->post('fiche_modif')));
            }


            $seuil_modif = '';
            if (isset($_POST['seuil_modif']) && $_POST['seuil_modif'] != '') {
                $seuil_modif =  trim(strip_tags($_POST['seuil_modif']));
            }
            $seuil_min_modif = '';
            if (isset($_POST['seuil_min_modif']) && $_POST['seuil_min_modif'] != '') {
                $seuil_min_modif =  trim(strip_tags($_POST['seuil_min_modif']));
            }
            $identification_seuil = '';
            if (isset($_POST['seul_unite_modif']) && $_POST['seul_unite_modif'] != '') {
                $identification_seuil =  trim(strip_tags($_POST['seul_unite_modif']));
            }



            $new_idunite = 0;
            if (isset($idunites[$identification_seuil])) {
                $new_idunite = $idunites[$identification_seuil];
            }
            $data = [];




            if ($photo['name'] == '' || $photo['size'] == 0) {
                $data = [
                    'refProduit' => $reference,
                    'designation' => $designation,
                    'seuil_min' => $seuil_min_modif,
                    'seuil' => $seuil_modif,
                    'type' => $type ,
                    'fiche' => $fiche 
                ];
            } else {
                $filename = $_FILES['photo']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $unique_name = time() . '_' . uniqid('produit_', true) . '_' . $_SESSION['idadmin'] . '.' . $ext;

                move_uploaded_file($_FILES['photo']['tmp_name'], 'public/upload/' . $unique_name);
                $data = [
                    'refProduit' => $reference,
                    'designation' => $designation,
                    'photo' => 'upload/' . $unique_name,
                    'seuil_min' => $seuil_min_modif,
                    'seuil' => $seuil_modif,
                    'type' => $type ,
                    'fiche' => $fiche 
                ];
            }


            $this->produit->updateProduit($id, $data);
            $this->session->set_flashdata('edit', 'fini');
            redirect('produit');
        }

        public function verifProd()
        {
            $id = strip_tags(trim($this->input->post('id')));
            $reference = strip_tags(trim($this->input->post('reference')));

            $data = $this->produit->verifyIfProdExiste($id, $reference);

            if (count($data) > 0) {
                $response['referenceExiste'] = true;
                $response['success'] = false;
            } else {
                $response = ['success' => true];
            }
            echo json_encode($response);

            // $tab = [];

            // for ($i = 0; $i < count($dataId); $i++) {
            //     array_push($tab, $dataId[$i]->refProduit);
            //     array_push($tab, $dataId[$i]->designation);
            // }


            // if (in_array($reference, $tab)) {
            //     
            // }
            // if (in_array($designation, $tab)) {
            //     $response['designationExiste'] = true;
            //     $response['success'] = false;
            // }

        }

        public function recherche()
        {
            $keyword = trim(strip_tags($_GET['recherche']));

            $_POST['post'] = $keyword;


            $config = array();
            $config["base_url"] = base_url() . 'recherche';
            $config["total_rows"] = count($this->produit->verifRefProd($keyword, '', ''));
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
            $datapag['produit'] = $this->produit->verifRefProd($keyword, $config["per_page"], $start);

            $data['title'] = 'Produit';
            $data['css'] = 'ajout.css';
            $js['js'] = 'ajout.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['client' => true]);
            $this->load->view('templates/tete');
            $this->load->view('ajouterProduit', [
                'data' => $datapag,
                'post' =>  $_POST['post']
            ]);
            $this->load->view('templates/footer', $js);
        }

        public function rechercherUniteProd()
        {
            $ref = $this->input->post('ref');
            $result = $this->produit->rechercherUniteProd($ref);

            $data = [
                'data' => $result[0],
                'success' => true
            ];

            echo json_encode($data);
        }
        public function imprimer()
        {
            // echo '<pre>' ;
            $data = $this->produit->imprimer();

            $this->load->view('imprim_produit', ['data' => $data]);
        }
    }
