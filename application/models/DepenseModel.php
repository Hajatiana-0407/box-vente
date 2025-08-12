<?php
class DepenseModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_count()
    {
        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('pointvente.idPointVente', $_SESSION['pv']);
        }
        $data = $this->db->get()->result();
        return count($data);
    }
    public function getAlldep() 
    {
        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('pointvente.idPointVente', $_SESSION['pv']);
        }
        $data = $this->db->order_by('depense.iddepense ', 'desc')
            ->get()->result();
        return $data;
    }

    public function getsomme($datas)
    {

        $somme = 0;
        foreach ($datas as $key => $data) {
            if ($data->montant) {
                $somme += $data->montant;
            }
        }

        return $somme;
    }


    public function get_authors($page = 1 )
    {

        // LIMITE 
        if($page == 0){
            $realOffset = $page * PAGINATION;
        }else{
            $realOffset = ($page - 1) * PAGINATION;
        }


        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->join('user', 'user.idUser = depense.idUser', 'left')
            ->join('commande' , 'commande.idcommande = depense.idcommande' , 'left')
            ->where('depense.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('pointvente.idPointVente', $_SESSION['pv']);
        }
        $data = $this->db->order_by('depense.iddepense ', 'desc')
            ->limit(PAGINATION  , $realOffset)
            ->get()->result();

            // echo '<pre>';
            // var_dump( $data ) ; 
            // echo '</pre>' ; die ; 
        return $data;
    }

    public function register($data)
    {
        $this->db->insert('depense', $data);
        return $this->db->insert_id();
    }
    public function deleteit($id)
    {
        $this->db->where('idadmin', $_SESSION['idadmin'])->where('iddepense', $id)->delete('depense');
    }

    public function edit($id, $data)
    {
        $this->db->where('iddepense', $id)->update('depense', $data);
    }


    public function searchdepense($keyword = '', $date = "", $limit = "", $offset = "")
    {
        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);
            if (isset($_SESSION['pv'])) {
                $this->db->where('pointvente.idPointVente', $_SESSION['pv']);
            }

        if ($keyword != '') {
            $keyword = '%' . $keyword . '%';

            $this->db->where('pointvente.adressPv LIKE ', $keyword);
            $this->db->or_where('depense.raison LIKE ', $keyword);
        }

        if ($date != '') {
            $date = '%' . $date . '%';
            $this->db->where('depense.datedepense LIKE ', $date);
        }
        if ($limit != '')
            $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function getdataDep_search($d1 = '', $d2 = '', $pv = 0)
    {

        // Sortant

        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->join('commande', 'commande.idcommande = depense.idcommande', 'left')
            ->join('user', 'user.idUser = depense.idUser', 'left');
            if (isset($_SESSION['pv'])) {
                $this->db->where('pointvente.idPointVente', $_SESSION['pv']);
            }

        if ($d1 != '' && $d2 != '') {
            $this->db->where('depense.datedepense >= ', $d1);
            $this->db->where('depense.datedepense <=', $d2);
        } else if ($d1 != '' && $d2 == '') {
            $d1  ='%'. $d1 . '%' ; 
            $this->db->where('depense.datedepense LIKE', $d1);
        } else if ($d1 == '' && $d2 != '') {
            $d2  ='%'. $d2 . '%' ; 
            $this->db->where('depense.datedepense LIKE', $d2);
        }

        if ($pv != 0) {
            $this->db->where('depense.idPointVente', $pv);
        }



        $depenses = $this->db->order_by('iddepense' ,'desc')->where('depense.idadmin', $_SESSION['idadmin'])
            ->get()->result();

        return $depenses;
    }
}
