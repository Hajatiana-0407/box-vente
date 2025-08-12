<?php
class Commande extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('FournisseurModel', 'fournisseur');
        $this->load->model('CommandeModel', 'commande');
        $this->load->model('Admin_model');
    }

    // ******************** UTILE ***************************** //
    public function index()
    {

        $this->jail();
        $data['title'] = 'Commande';
        $data['css'] = 'commande.css';
        $js['js'] = 'commande.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['vente' => true]);
        $this->load->view('commande');
        $this->load->view('templates/footer', $js);
    }

    public function getProduit()
    {
        $reference = '';
        if (isset($_POST['reference']) && $_POST['reference'] != '') {
            $reference = trim(strip_tags($_POST['reference']));
        }

        $datas = $this->commande->getProduit($reference);
        if (count($datas) > 0) {
            echo json_encode([
                'success' => true,
                'datas' => $datas[0]
            ]);
        } else {
            echo json_encode([
                'success' => false
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
        $quantite =  $this->vente->getStock($idProduit, $id_pv);
        if (count($prixUnites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prixUnites,
                'quantite' => $quantite
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    public function fournisseur()
    {
        $recherche = '';
        if (isset($_POST['recherche']) && $_POST['recherche'] != '') {
            $recherche = trim(strip_tags($_POST['recherche']));
        }


        $datas = $this->fournisseur->search($recherche);
        echo json_encode([
            'datas' => $datas
        ]);
    }


    public function facturation()
    {
        $numfournisseur = '';
        if (isset($_POST['numfournisseur']) && $_POST['numfournisseur'] != '') {
            $numfournisseur = strip_tags(trim($this->input->post('numfournisseur')));
            $numfournisseur = str_replace(' ', '', $numfournisseur);
        }

        $tva = '';
        if (isset($_POST['tva']) && $_POST['tva'] != '') {
            $tva = strip_tags(trim($this->input->post('tva')));
        }


        $montant_total = '';
        if (isset($_POST['montant_total']) && $_POST['montant_total'] != '') {
            $montant_total = trim(strip_tags($_POST['montant_total']));
        }
        $frais = '';
        if (isset($_POST['frais']) && $_POST['frais'] != '') {
            $frais = trim(strip_tags($_POST['frais']));
        }






        $fournisseur = $this->fournisseur->getFournisseurByNumero($numfournisseur);


        $lastFacture = $this->commande->getLastFacture();

        if (!empty($lastFacture)) {
            $last = $lastFacture[0]->cmfacture;
            $explodeLastFacture = explode("-", $last);
            $numAI = intval($explodeLastFacture[1]);
            $numAI++;
            $numFacture = date("Y") . "-" . str_pad($numAI, 5, 0, STR_PAD_LEFT);
        } else {
            $numFacture = date("Y") . "-" . str_pad("1", 5, 0, STR_PAD_LEFT);
        }




        if (count($fournisseur) > 0) {
            $data = [
                'cmfacture' => $numFacture,
                'tel_fournisseur' => my_trim($fournisseur[0]->tel_fournisseur),
                'idadmin' => $_SESSION['idadmin'],
                'tva' => $tva,
                'montant_total' => $montant_total,
                'frais' => $frais,
            ];
            $idfacture = $this->commande->insertcommande($data);
            echo json_encode([
                'success' => true,
                'facture' => $numFacture,
                'idfacture' => $idfacture,
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }

    public function validate()
    {
        $datas = [];
        if (isset($_POST['data'])) {
            $datas = $_POST['data'];
        }

        // echo '<pre>' ;
        // var_dump( $datas ) ; 
        // echo '</pre>' ; die ;

        foreach ($datas  as $key => $data) {
            $datas[$key]['idadmin'] = $_SESSION['idadmin'];
        }
        $this->commande->insertPanier($datas);
        $this->session->set_flashdata('success', true);
    }


    public function facture($facture = '')
    {
        if ($facture != '') {
            $facture = trim(strip_tags($facture));
        }


        $fact = $this->commande->getFature($facture);

        $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


        $total['ht'] = $fact->montant_total + $fact->frais;
        $TVA = $total['ht'] * 20 / 100;
        $total['tva'] = $TVA;
        $total['ttc'] = $TVA + $total['ht'];


        $this->load->library('Commandefacture') ; 
        $pdf = new Commandefacture("P", "mm", "A4");
        $pdf->AddPage();


        for ($i = 0; $i < $pdf->nbr_page; $i++) {
            $offest = $i * 10;
            $limite = ($i + 1) * 10;
            if ($pdf->nbr_page > 1 && $i != ($pdf->nbr_page - 1)) {
                $pdf->pos_foot = 158;
                $pdf->affiche = false;
            } else {
                $pdf->pos_foot = 100;
                $pdf->affiche = true;
            }

            $pdf->head($fact, $admin);
            $pdf->Ln(8);
            $pdf->corps($fact, $total, $offest,  $limite);
            $pdf->Ln($pdf->pos_foot);
            // $pdf->foot();

            $pdf->pos_foot = 100;
        }


        $pdf->Output();
    }


    // ******************** UTILE ***************************** //
}
