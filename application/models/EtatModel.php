<?php
class EtatModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getdataEtat()
    {
        $entrants = $this->db->select('SUM(montant_payer) as montant ,  vente.Facture , vente.dateVente as date, pointvente.*')
            ->from('vente')
            ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'left')
            // ->join('panier', 'panier.idfacture = vente.idfacture', 'left')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            // ->where('panier.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin'])
            ->group_by('vente.Facture')
            ->get()->result();


        $sortants = $this->db->select('depense.raison , depense.montant , depense.datedepense as date , pointvente.* , commande.*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->join('commande', 'commande.idcommande = depense.idcommande', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin'])
            ->get()->result();

        $etats = [];
        foreach ($entrants as $key => $entrant) {
            $etats[] = $entrant;
        }
        foreach ($sortants as $key => $sortant) {
            $etats[] = $sortant;
        }
        return $etats;
    }
    public function getdataEtat_search($d1 = '', $d2 = '', $pv = 0, $type = 'tout')
    {
        // Entrant 

        $entrants = [];

        if ($type == 'entre' || $type == 'tout') {
            $this->db->select('SUM(montant_payer) as montant ,  vente.Facture , vente.dateVente as date, pointvente.*')
                ->from('vente')
                ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'left')
                ->where('vente.idadmin', $_SESSION['idadmin'])
                ->where('pointvente.idadmin', $_SESSION['idadmin']);

            if ($d1 != '' && $d2 != '') {
                $this->db->where('vente.dateVente >= ', $d1);
                $this->db->where('vente.dateVente <=', $d2);
            } else if ($d1 != '' && $d2 == '') {
                $d1 = '%' . $d1 . '%';
                $this->db->where('vente.dateVente LIKE', $d1);
            } else if ($d1 == '' && $d2 != '') {
                $d2 = '%' . $d2 . '%';
                $this->db->where('vente.dateVente LIKE', $d2);
            }

            if ($pv != 0) {
                $this->db->where('vente.idPointVente', $pv);
            }

            $entrants = $this->db->group_by('vente.Facture')
                ->get()->result();
        }
        // Entrant 


        $sortants = [] ; 
        // Sortant


        if ($type == 'sortie' || $type == 'tout') {
            $this->db->select('depense.raison , depense.montant , depense.datedepense as date , pointvente.*')
                ->from('depense')
                ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left');

            if ($d1 != '' && $d2 != '') {
                $this->db->where('depense.datedepense >= ', $d1);
                $this->db->where('depense.datedepense <=', $d2);
            } else if ($d1 != '' && $d2 == '') {
                $d1 = '%' . $d1 . '%';
                $this->db->where('depense.datedepense LIKE', $d1);
            } else if ($d1 == '' && $d2 != '') {
                $d2 = '%' . $d2 . '%';
                $this->db->where('depense.datedepense LIKE', $d2);
            }

            if ($pv != 0) {
                $this->db->where('depense.idPointVente', $pv);
            }

            $sortants = $this->db->where('depense.idadmin', $_SESSION['idadmin'])
                ->get()->result();
        }


        // Sortant
        $etats = [];

        foreach ($entrants as $key => $entrant) {
            $etats[] = $entrant;
        }
        foreach ($sortants as $key => $sortant) {
            $etats[] = $sortant;
        }
        return $etats;
    }


    public function  getsomme($datas = [])
    {
        $somme = [
            'entrant' => 0,
            'sortant' => 0,
        ];

        foreach ($datas  as  $key =>  $data) {
            if (isset($data->Facture)) {
                $somme["entrant"] += $data->montant;
            } else {
                $somme["sortant"] += $data->montant;
            }
        }
        return $somme;
    }





    public function  getallpv()
    {
        $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv']))
            $this->db->where('idPointVente', $_SESSION['pv']);
        // if ( $pv != '')
        //     $this->db->where('idPointVente' , $pv );

        return  $this->db->get()->result();
    }
    // public function gettotalvente($pv = [])
    // {
    //     foreach ($pv as $key => $pointvente) {
    //         $total = $this->db->select('SUM(montantPaye) as total ')
    //             ->from('vente')
    //             ->join('panier', 'panier.Facture = vente.Facture', 'left')
    //             ->where('vente.idPointVente', $pointvente->idPointVente)
    //             ->where('vente.idadmin', $_SESSION['idadmin'])
    //             ->where('panier.idadmin', $_SESSION['idadmin'])
    //             ->get()->result();

    //         if ($total[0]->total == null) {
    //             $pointvente->totalvente = 0;
    //         } else {
    //             $pointvente->totalvente = $total[0]->total;
    //         }
    //     }
    //     return $pv;
    // }

    // public function gettotaldepense($pv)
    // {
    //     foreach ($pv as $key => $pointvente) {
    //         $total = $this->db->select('SUM(montant) as total ')
    //             ->from('depense')
    //             ->where('depense.idPointVente', $pointvente->idPointVente)
    //             ->where('depense.idadmin', $_SESSION['idadmin'])
    //             ->get()->result();

    //         if ($total[0]->total == null) {
    //             $pointvente->totaldepense = 0;
    //         } else {
    //             $pointvente->totaldepense = $total[0]->total;
    //         }
    //     }

    //     return $pv;
    // }
}
