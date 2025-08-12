<?php

class PrixModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    // *  pagination 

    public function get_count()
    {
        $prix = $this->db->select('*')
            ->from('prix')
            ->join('produit', 'produit.idProduit = prix.idProduit')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->order_by('prix.idPrix', 'desc')
            ->group_by('prix.groupe')
            ->get()->result();
        return count($prix);
    }

    public function get_authors($limit, $start)
    {
        $prix = $this->db->select('*' )
            ->from('prix')
            ->join('produit', 'produit.idProduit = prix.idProduit', 'left')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->order_by('prix.idPrix', 'desc')
            ->group_by('prix.groupe')
            ->limit($limit, $start)
            ->get()->result();



        return $prix;
    }


    public function verifier_prix_materiel($mat)
    {
        $idadmin = $_SESSION['idadmin'];
        return $this->db->query("SELECT * FROM prix  INNER JOIN produit ON prix.idProduit = produit.idProduit WHERE produit.refProduit LIKE '%$mat%'  AND prix.idadmin = '$idadmin' ")->result();
    }

    public function verifRefProd($ref)
    {
        $query = $this->db->select('*')
            ->from('produit')
            ->where('refProduit', $ref)
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }

    public function deleteItem($idProduit, $groupe)
    {
        $this->db->where('idProduit', $idProduit);
        $this->db->where('groupe', $groupe);
        $this->db->delete('prix');
    }


    public function insertPrix($data)
    {
        $this->db->insert('prix', $data);

        return $this->db->insert_id();
    }
    public function insert_bacth($data)
    {
        $this->db->insert_batch('prix', $data);
    }



    public function searchPrix($keyword = '', $limit = '', $start = 1)
    {
        $keyword = '%' . $keyword . '%';
        $this->db->select('*')
            ->from('prix')
            ->join('produit', 'prix.idProduit = produit.idProduit')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('produit.refProduit LIKE', $keyword)
            ->or_where('produit.designation LIKE', $keyword)
            ->or_where('produit.fiche LIKE', $keyword)
            ->or_where('prix.prixProduit LIKE', $keyword)
            ->or_where('prix.dateAjoutPrix LIKE', $keyword)
            ->order_by('prix.idPrix', 'DESC')
            ->group_by('prix.groupe');
        if ($limit != '') {
            $this->db->limit($limit, $start);
        }
        $prix =  $this->db->get()->result();
        return $prix;
    }


    public function selectUniterByProduit($idProduit)
    {
        $this->db->select('*');
        $this->db->from('prix')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->join('produit', 'prix.idProduit = produit.idProduit', 'left')
            ->join('groups', 'groups.id_group = produit.id_group', 'left')
            ->join('uniter', 'uniter.idUniter = prix.idUniter', 'left');

        $this->db->where('prix.idProduit', $idProduit);

        $this->db->order_by('idPrix ', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }
    public function getPrixByuniter($idunit)
    {
        $query = $this->db->select('*')
            ->from('prix')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('produit.idadmin', $_SESSION['idadmin'])
            ->where('prix.idUniter', $idunit)
            ->order_by('idPrix ', 'DESC')
            ->get();
        return $query->result();
    }


    public function getLastgroupe($idProduit = '')
    {
        $this->db->select('groupe')
            ->from('prix')
        ;

        if ($idProduit != '') {
            $this->db->where('idProduit', $idProduit);
        }
        $groups =  $this->db->order_by('idPrix', 'desc')->get()->result();
        if (count($groups) > 0) {
            return $groups[0]->groupe;
        }
        return '';
    }


    // *************************************************************** //

    // *************************************************************** //
}
