<?php
class TransfertMOdel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($data)
    {
        $this->db->insert('transfert', $data);
        return $this->db->insert_id();
    }

    public function getAuthor($page = '')
    {
        // LIMITE 
        if ((int)$page == 0) {
            $realOffset = (int)$page * LIMITE;
        } else {
            $realOffset = ((int)$page - 1) * LIMITE;
        }

        $this->db->select('*')
            ->from('transfert')
            ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
            ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
            ->join('produit', 'produit.idProduit = transfert.idProduit', 'left')
            ->join('unite', 'unite.idunite = transfert.idunite', 'left')
            ->where('transfert.idadmin', $_SESSION['idadmin']);


        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->get()->result();
    }
    public function search($date_debut, $date_fin, $mot, $etat , $page = '')
    {
        // LIMITE 
        if ((int)$page == 0) {
            $realOffset = (int)$page * LIMITE;
        } else {
            $realOffset = ((int)$page - 1) * LIMITE;
        }

        $this->db->select('*')
            ->from('transfert')
            ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
            ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
            ->join('produit', 'produit.idProduit = transfert.idProduit', 'left')
            ->join('unite', 'unite.idunite = transfert.idunite', 'left')
            ->where('transfert.idadmin', $_SESSION['idadmin']);

        if ($date_debut != '' && $date_fin == '') {
            $this->db->like('transfert.date_transfert', $date_debut);
        }
        if ($date_debut == '' && $date_fin != '') {
            $this->db->like('transfert.date_transfert', $date_fin);
        }
        if ($date_debut != '' && $date_fin != '') {
            $this->db->where('transfert.date_transfert >=', $date_debut);
            $this->db->where('transfert.date_transfert <=', $date_fin);
        }
        if ($mot != '') {
            $this->db->like('produit.refProduit', $mot);
            $this->db->or_like('produit.designation', $mot);
        }

        if ($etat  != "0") {
            if ( $etat == "recu") {
                $this->db->where('transfert.reception_transfert','1');
            }else {
                $this->db->where('transfert.reception_transfert','0');
            }
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $realOffset);
        }
        return $this->db->get()->result();
    }

    public function getByid($idtransfert)
    {
        return $this->db->select('*')
            ->from('transfert')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idtransfert', $idtransfert)
            ->get()->result();
    }

    public function delete($idtransfert)
    {
        $this->db->where('idadmin', $_SESSION['idadmin'])->where('idtransfert', $idtransfert)->delete('transfert');
    }
    public function receive($idtransfert)
    {
        $this->db->where('idadmin', $_SESSION['idadmin'])->where('idtransfert', $idtransfert)->update('transfert', ['reception_transfert' => 1]);
    }
}
