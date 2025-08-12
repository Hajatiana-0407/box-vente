<?php

class Facture extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('FactureModel', 'facture_m');
    }

     

    public function facture()
    {
        $this->jail();
        $data['title'] = 'Facture';
        $data['css'] = 'facture.css';
        $js['js'] = 'facture.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['facture' => true]);
        $this->load->view('templates/tete');
        $this->load->view('facture', [
            'uniter' => $this->facture_m->getUniter(),
            // 'facture' => $this->facture_m->getAllFacture()
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function registerFacture()
    {
        $reference = trim($this->input->post('reference'));
        $designation = trim($this->input->post('designation'));
        $idClient = trim($this->input->post('idClient'));
        $prix = trim($this->input->post('prix'));
        $quantiter = trim($this->input->post('quantiter'));
        $idUniter = trim($this->input->post('unite'));
        $montant = trim($this->input->post('montant'));

        $this->facture_m->insertFacture([
            "refProduit" => $reference,
            "designation" => $designation,
            "prix" => $prix,
            "quantiter" => $quantiter,
            "idUnite" => $idUniter,
            "montant" => $montant,
            "idClient" => $idClient,
        ]);
        $this->session->set_flashdata('success', 'Ajout réussie');

        redirect('facture');
    }

    public function deleteFacture()
    {
        $id = $this->input->post('id');

        $data = $this->facture_m->delete($id);

        echo json_encode([
            'success' => true,
            'error' => '',
            'data' => $data,
        ]);
    }

    public function editFacture()
    {
        $reference = trim($this->input->post('reference_modif'));
        $designation = trim($this->input->post('designation_modif'));
        $idFacture_modif = trim($this->input->post('idFacture_modif'));
        $prix = trim($this->input->post('prix_modif'));
        $quantiter = trim($this->input->post('quantiter_modif'));
        $idUniter = trim($this->input->post('idUnite_modif'));
        $montant = trim($this->input->post('montant_modif'));
        $idClient_modif = trim($this->input->post('idClient_modif'));

        $data = [
            'refProduit' => $reference,
            'designation' => $designation,
            'prix' => $prix,
            'quantiter' => $quantiter,
            'idUnite' => $idUniter,
            'montant' => $montant,
            'idClient' => $idClient_modif,
        ];

        $this->facture_m->updateFacture($idFacture_modif, $data);
        $this->session->set_flashdata('modif', 'ok');
        redirect('facture');
    }

    public function validate()
    {
        $idCom = $_SESSION['idCommercial'];
        $option = $this->input->post('option');
        /** alaina ny farany facture */
        $dataFacture = $this->facture_m->getLastFacture();
        $numFacture = date("Y") . "-" . str_pad("1", 3, 0, STR_PAD_LEFT);

        if (count($dataFacture) > 0) {
            $lastFacture = $dataFacture[0]->Facture;
            $explodeLastFacture = explode("-", $lastFacture);
            $numAI = intval($explodeLastFacture[1]);
            $numAI++;
            $numFacture =  date("Y") . "-" . str_pad($numAI, 3, 0, STR_PAD_LEFT);
        }

        if ($option == 'panier') {
            $tableau = $this->input->post('panier');
            for ($i = 0; $i < count($tableau); $i++) {
                $this->facture_m->addToPanier($tableau[$i]['ref'], $tableau[$i]['quantite'], $tableau[$i]['prix'], $tableau[$i]['montant'], $tableau[$i]['unite'], $numFacture);
            }
            echo json_encode([
                'facture' => $numFacture,
            ]);
        } else if ($option == 'vm') {
            $ref = $this->input->post('ref');
            $qte = $this->input->post('qte');
            $prix = $this->input->post('prix');
            $montant = $this->input->post('montant');

            $numClient = $this->input->post('numClient');
            $modep = $this->input->post('modep');
            $remise = $this->input->post('remise');
            $numFact = $this->input->post('numFacture');

            $getFactureIfExist = $this->facture_m->getFactureIfExist($numFact);

            $clientIdOrNot = $this->facture_m->getClientID($numClient);
            $clientDirect = '';
            if ($clientIdOrNot == '') {
                $clientDirect = $numClient;
            }

            if (count($getFactureIfExist) == 0) {
                $this->facture_m->addToVM(RIGHT_NOW, $this->facture_m->getClientID($numClient), $modep, $numFact, $remise, $clientDirect);
                // redirect('facture/'.$numFact);
                echo json_encode([
                    'href' => 'facture/' . $numFact,
                ]);
            }
        }
    }

    public function facturation($ref)
    {
        $facturation = $this->facture_m->getFacturation($ref);

        $this->load->view('test_pdf', [
            'facture' => $facturation,
        ]);
    }
}
