<?php

class FactureModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function verifUniter()
    {
        $query = $this->db->select('nomUniter')->from('uniter')->get();
        return $query->result();
    }

    public function getUniter()
    {
        return $this->db->query("SELECT * FROM uniter ORDER BY idUniter ASC")->result();
    }

    public function insertFacture($data)
    {
        $this->db->insert('facture', $data);
    }

    public function getAllFacture()
    {
        $query = $this->db->select('facture.*, client.*, uniter.*')
            ->from('facture')
            ->join('client', 'client.idClient = facture.idClient', 'left')
            ->join('uniter', 'uniter.idUniter = facture.idUnite')
            ->order_by('facture.idFacture', 'DESC')
            ->get();

        return $query->result();
    }

    public function getFacturation($Facture)
    {
        $results = [];
        $queryFacture = $this->db->select('facture.*, client.*, modepaiement.*')
            ->from('facture')
            ->join('client', 'client.idClient = facture.idClient', 'left')
            ->join('modepaiement', 'modepaiement.idMode = facture.idMode', 'left')
            ->where('facture.Facture', $Facture)
            ->order_by('facture.idFacture', 'DESC')
            ->get();
        $factures = $queryFacture->result();

        foreach ($factures as $key => $facture) {
            $queryPannier = $this->db->select('facture.*, panier.*, ajoutproduit.*')
                ->from('facture')
                ->join('panier', 'panier.Facture = facture.Facture')
                ->join('ajoutproduit', 'ajoutproduit.refProduit = panier.refProduit')
                ->where('facture.idFacture', $facture->idFacture)
                ->order_by('facture.idFacture', 'DESC')
                ->get();
            $panniers = $queryPannier->result();
            $results = ['facture' => $facture, 'panniers' => $panniers];
        }

        return $results;
    }

    public function delete($id)
    {
        $this->db->where('Facture', $id);
        $this->db->delete('panier');


        $this->db->where('Facture', $id);
        $this->db->delete('facture');
    }

    public function updateFacture($id, $data)
    {
        $this->db->where('idFacture', $id);
        $this->db->update('facture', $data);
        return $this->db->affected_rows() > 0;
    }

    // public function searchFacture($keyword)
    // {
    //     return $this->db->query("SELECT * FROM facture INNER JOIN uniter ON facture.idUnite = uniter.idUniter WHERE uniter.nomUniter LIKE '%$keyword%' INNER JOIN client ON client.idClient = facture.idClient WHERE client.nomClient LIKE '%$keyword%' OR facture.refProduit LIKE '%$keyword%' OR facture.designation LIKE '%$keyword%' OR facture.prix LIKE '%$keyword%' OR facture.quantiter LIKE '%$keyword%' OR facture.montant LIKE '%$keyword%'")->result();
    // }

    public function searchFacture($table, $keyword, $fields)
    {
        $req = "SELECT * FROM $table WHERE ";
        for ($i = 0; $i < count($fields); $i++) {
            $req .= $fields[$i] . " LIKE '%" . $keyword . "%' OR ";
        }

        $req = trim($req, "OR ");

        $data = $this->db->query($req)->result();

        return $data;
    }

    public function getLastFacture()
    {
        return $this->db->query("SELECT * FROM facture ORDER BY idFacture DESC")->result();
    }

    public function addToPanier($idMateriel, $qteMateriel, $prixMateriel, $montantTotal, $unite, $facture)
    {
        $this->db->query("INSERT INTO panier(refProduit,qteProduit,prixProduit,montantTotal,unite,Facture) VALUES('$idMateriel','$qteMateriel','$prixMateriel','$montantTotal','$unite','$facture')");
    }
    public function getFactureIfExist($fact)
    {
        return $this->db->select('*')->from('facture')->where('Facture', $fact)->get()->result();
    }
    public function addToVM($date, $idClt, $idMDP, $facture, $remise, $cltDirect)
    {
        $this->db->query("INSERT INTO facture(dateVente,idClient,clientDirect,idMode,Facture,remise) VALUES('$date','$idClt','$cltDirect','$idMDP','$facture','$remise')");
    }

    public function getClientID($num)
    {
        $data = $this->db->query("SELECT * FROM client WHERE telClient='$num'")->result();
        if (count($data) > 0) {
            return $data[0]->idClient;
        } else {
            return '';
        }
    }
}
