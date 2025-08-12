<?php
class SuiviModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * tout les donnée
     *
     * @param string $page
     * @return void
     */
    public function get_authors($page = '')
    {
        if ((int)$page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else {
            $realOffset = ((int)$page - 1) * PAGINATION;
        }
        $this->db->select('client.*, 
                   SUM(vente.montant_payer) AS total_montant, 
                   COUNT(vente.idfacture) AS nbr_ventes')
            ->from('client')
            ->join('(SELECT * FROM vente) AS vente', 'vente.telClient = client.telClient', 'left')
            ->where('client.idadmin', $_SESSION['idadmin'])
            ->group_by('client.idClient')  // Groupement par client pour calculer les totaux par client
            ->order_by('client.idClient', 'desc');

        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->get()->result();
    }


    /**
     * recherche 
     *
     * @param string $keyword
     * @param string $page
     * @return void
     */
    public function search($keyword = '', $page = '')
    {
        if ((int)$page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else {
            $realOffset = ((int)$page - 1) * PAGINATION;
        }
        $this->db->select('client.*, 
                   SUM(vente.montant_payer) AS total_montant, 
                   COUNT(vente.idfacture) AS nbr_ventes')
            ->from('client')
            ->join('(SELECT * FROM vente) AS vente', 'vente.telClient = client.telClient', 'left')
            ->where('client.idadmin', $_SESSION['idadmin']);

        if ($keyword != '') {
            $this->db->like('client.nomClient', $keyword);
            $this->db->or_like('client.prenomClient', $keyword);
            $this->db->or_like('client.adresseClient', $keyword);
            $this->db->or_like('client.telClient', $keyword);
            $this->db->or_like('client.r_social', $keyword);
            $this->db->or_like('client.emailClient', $keyword);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }

        return $this->db->group_by('client.idClient')  // Groupement par client pour calculer les totaux par client
            ->order_by('client.idClient', 'desc')->get()->result();
    }

    /**
     * detailes sur les activite du client 
     *
     * @param string $debut
     * @param string $fin
     * @param string $mot
     * @param integer $lieu
     * @param string $page
     * @return void
     */
    public function details($telClient = '',  $page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('vente')
            ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'inner')
            ->join('modepaiement', 'modepaiement.idModePaiement = vente.idModePaiement', 'left')
            ->join('client', 'client.telClient = vente.telClient', 'left')
            ->join('user', 'user.idUser  = vente.idUser ', 'left')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);

        if ($telClient != '') {
            $this->db->where('vente.telClient', $telClient);
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        $this->db->order_by('vente.Facture', 'desc')
            ->group_by('vente.Facture', 'desc');
        $query = $this->db->get()->result();
        return $query;
    }


    /**
     * Recherche dans detailes 
     *
     * @param string $debut
     * @param string $fin
     * @param string $mot
     * @param integer $lieu
     * @param string $telClient
     * @param string $page
     * @return void
     */
    public function detailsSearch($debut = '', $fin = '', $mot = '', $lieu = 0, $telClient = '' , $page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('vente')
            ->join('pointvente', 'pointvente.idPointVente = vente.idPointVente', 'inner')
            ->join('modepaiement', 'modepaiement.idModePaiement = vente.idModePaiement', 'left')
            ->join('client', 'client.telClient = vente.telClient', 'left')
            ->join('user', 'user.idUser  = vente.idUser ', 'left')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            ->where('vente.telClient', $telClient)
            ->where('pointvente.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            $this->db->like('vente.Facture', $mot);
            $this->db->or_like('client.nomClient', $mot);
            $this->db->or_like('client.r_social', $mot);
            $this->db->or_like('client.telClient', $telClient);
            $this->db->or_like('user.prenomUser', $mot);

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
