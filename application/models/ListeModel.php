<?php

class ListeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllPv()
    {
        return $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'desc')
            ->get()->result();
    }

    public function getAllListe($page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $idadmin = $_SESSION['idadmin'];
        $this->db->select('*');
        $this->db->from('vente')
            ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'inner')
            ->join('client', 'client.telClient = vente.telClient', 'left')
            ->join('modepaiement', 'modepaiement.idModePaiement  = vente.idModePaiement ', 'left')
            ->join('user', 'user.idUser  = vente.idUser ', 'left')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $this->db->order_by('vente.Facture', 'desc');

        $query = $this->db->get()->result();


        return $query;
    }

    public function getAllInfo($fac)
    {
        $q = $this->db->select('*')
            ->from('vente')
            ->where('Facture', $fac)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $q->result();
    }

    public function getTotal($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantTotal) as Tot FROM panier WHERE  Facture = '$fac' AND idadmin = '$idadmin'")->result();
        return $total[0]->Tot;
    }
    public function getApayer($fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $totalP = $this->db->query("SELECT SUM(montantPaye) as TotP FROM panier WHERE  Facture = '$fac' AND idadmin = '$idadmin'")->result();
        return $totalP[0]->TotP;
    }

    public function getSumPrix($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(remise) as SommeR FROM panier WHERE Facture = '$fac' AND refProduit = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->SommeR;
    }

    public function getSumQte($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(qteProduit) as Qte FROM panier WHERE Facture = '$fac' AND refProduit = '$ref' AND idadmin = '$idadmin'")->result();
        return $total[0]->Qte;
    }

    public function getSumPayer($ref, $fac)
    {
        $idadmin =  $_SESSION['idadmin'];
        $total = $this->db->query("SELECT SUM(montantPaye) as payer FROM panier WHERE Facture = '$fac' AND refProduit = '$ref' AND idadmin = '$idadmin'")->result();
        // var_dump ( $total[0]->payer ) ; die ; 
        return $total[0]->payer;
    }

    public function getFact($idfacture)
    {
        // LE VENTE 
        $ventes = $this->db->select('*')
            ->from('vente')
            ->join('user', 'user.idUser = vente.idUser', 'left')
            ->join('pointvente', 'pointvente.idPointVente  = vente.idPointVente', 'left')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('vente.idfacture', $idfacture)
            ->get()->result();
        // ALL PANIER 
        $paniers = $this->db->select('*')
            ->from('panier')
            ->join('produit', 'produit.idProduit = panier.idProduit', 'left')
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('panier.idfacture', $idfacture)
            ->get()->result();

        if (count($ventes) > 0) {
            $ventes = $ventes[0];
            $ventes->panier = $paniers;
            return $ventes;
        } else {
            return [];
        }
    }

    public function getFacture($idfacture)
    {
        // $idadmin =  $_SESSION['idadmin'];
        // $data = $this->db->query("SELECT DISTINCT(idProduit) FROM panier WHERE idfacture = '$idfacture'  AND idadmin = '$idadmin'")->result();


        // echo '<pre>' ;
        // var_dump( $data ) ; 
        // echo '</pre>' ; die ; 

        // $dataf = [];
        // for ($i = 0; $i < count($data); $i++) {
        //     $res =  $this->db->select('*')
        //         ->from('panier')
        //         ->join('produit', 'produit.idProduit = panier.idProduit', 'left')
        //         ->join('vente', 'vente.idfacture = panier.idfacture', 'left')
        //         ->join('client', 'client.telClient = vente.telClient', 'left')
        //         ->where('panier.idadmin', $_SESSION['idadmin'])
        //         ->where('produit.idadmin', $_SESSION['idadmin'])
        //         ->where('vente.idadmin', $_SESSION['idadmin'])
        //         ->where('panier.idProduit', $data[$i]->idProduit)
        //         ->get()->result();
        //     $dataf[] = $res[0];
        // }

        // return $dataf;

        $ventes = $this->db->select('*')
            ->from('vente')
            ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'left')
            ->join('modepaiement', 'modepaiement.idModePaiement  = vente.idModePaiement ', 'left')
            ->where('vente.idfacture', $idfacture)
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        $paniers =  $this->db->select('*')
            ->from('panier')
            ->join('produit', 'produit.idProduit = panier.idProduit', 'left')
            ->where('panier.idfacture', $idfacture)
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        if (count($ventes) > 0) {
            $ventes = $ventes[0];
            $ventes->paniers = $paniers;

            // client 
            $client = $this->db->select('*')
                ->from('client')
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('telClient', $ventes->telClient)
                ->get()->result();
            if (count($client) > 0) {
                $client = $client[0];
                foreach ($client as $key => $value) {
                    $ventes->$key = $value;
                }
            } else {
            }
        }

        return $ventes;
    }


    public function getUser($id)
    {
        $res = $this->db->select('*')
            ->from('user')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idUser', $id)->get()->result();
        return $res[0];
    }


    public function getGrandTotal($refFacture)
    {
        $res = $this->db->select_sum('montantPaye')
            ->from('panier')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('Facture', $refFacture)
            ->get()->result();
        return $res;
    }

    public function getAllDetails($details)
    {
        $req = $this->db->select('*')
            ->from('panier')
            ->join('vente', 'vente.Facture = panier.Facture', 'left')
            ->join('produit', 'produit.refProduit = panier.refProduit', 'left')
            ->join('prix', 'prix.refProduit = produit.refProduit', 'left')
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('panier.Facture', $details[0]->Facture)
            ->get();
        return $req->result();
    }

    public function getFactureSelected($details)
    {
        $req = $this->db->select('*')
            ->from('panier')
            ->join('vente', 'vente.Facture = panier.Facture', 'left')
            ->join('produit', 'produit.refProduit = panier.refProduit', 'left')
            ->join('prix', 'prix.refProduit = produit.refProduit', 'left')
            ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('panier.Facture', $details)
            ->get();
        return $req->result();
    }

    public function deleteVente($idfacture)
    {
        $this->db->where('idfacture', $idfacture)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('vente');
    }
    public function deletePanier($idfacture)
    {
        $this->db->where('idfacture ', $idfacture)->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('panier');
    }

    public function search($debut = '', $fin = '', $mot = '', $lieu = 0, $page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('vente')
            ->join('panier', 'panier.idfacture = vente.idfacture', 'inner')
            ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'inner')
            ->join('modepaiement', 'modepaiement.idModePaiement = vente.idModePaiement', 'left')
            ->join('client', 'client.telClient = vente.telClient', 'left')
            ->join('user', 'user.idUser  = vente.idUser ', 'left')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            $this->db->like('vente.Facture', $mot);
            // Numero et email 
            $this->db->or_like('panier.numero', $mot);
            $this->db->or_like('panier.imei1', $mot);
            $this->db->or_like('panier.imei2', $mot);
            $this->db->or_like('vente.telClient', $mot);
            $this->db->or_like('client.nomClient', $mot);
            $this->db->or_like('client.r_social', $mot);
            $this->db->or_like('user.prenomUser', $mot);


            if (strpos('ADMIN', strtoupper($mot)) > -1) {
                $this->db->or_where('user.idUser', null);
            }
        }
        if ($lieu != 0) {
            $this->db->where('pointvente.idPointVente ', $lieu);
        }

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('vente.dateVente LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('vente.dateVente like', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('vente.dateVente >=', $debut);
            $this->db->where('vente.dateVente <=', $fin);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        $this->db->order_by('vente.Facture', 'desc')
            ->group_by('vente.Facture', 'desc');
        $query = $this->db->get()->result();


        return $query;
    }
}
