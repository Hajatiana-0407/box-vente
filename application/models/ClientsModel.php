<?php
class ClientsModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    // *  pagination 
    public function get_count()
    {
        $query = $this->db->select('*')
            ->from('client')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idClient', 'desc')
            ->get()->result();

        return count($query);
    }

    public function get_authors($limit, $start)
    {
        $query = $this->db->select('*')
            ->from('client')
            ->join('user', 'user.idUser = client.idUser ', 'left')
            ->where('client.idadmin', $_SESSION['idadmin'])
            ->limit($limit, $start)
            ->order_by('client.idClient', 'desc')
            ->get();

        return $query->result();
    }
    // *  pagination 

    public function getALLclients()
    {
        $query = $this->db->select('*')
            ->from('client')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idClient', 'DESC')
            ->get();
        return $query->result();
    }

    public function insertClients($data)
    {
        $this->db->insert('client', $data);
    }

    public function editClient($id)
    {
        $this->db->where('idClient', $id)->where('idadmin', $_SESSION['idadmin']);
        $query = $this->db->get('client');
        return $query->execute();
    }

    public function getClientByNum($num)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM client WHERE telClient LIKE '%$num%' AND idadmin = '$idadmin'")->result();
        return $data;
    }
    public function getClientByNumClient($num)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM client WHERE telClient LIKE '%$num%' AND idadmin = '$idadmin'")->result();
        return $data;
    }

    public function getClientByNumForFacturation($num)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM client WHERE telClient LIKE '%$num%' AND idadmin = '$idadmin'")->result();
        return $data;
    }

    public function updateclient($data, $id)
    {
        $this->db->where('idClient', $id);
        $this->db->update('client', $data);
        return $this->db->affected_rows() > 0;
    }

    public function getclientById($id)
    {
        $query = $this->db->select('*')
            ->from('client')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idClient', $id)
            ->get();
        return $query->result();
    }

    public function getclientByNumero($num)
    {
        $query = $this->db->select('*')->from('client')->where('idadmin', $_SESSION['idadmin'])->where('telClient', $num)->get();
        return $query->result();
    }

    public function getclientByMail($mail)
    {
        $query = $this->db->select('*')->from('client')->where('idadmin', $_SESSION['idadmin'])->where('emailClient', $mail)->get();
        return $query->result();
    }

    public function delete($id)
    {
        $this->db->where('idClient ', $id);
        $this->db->delete('client');
    }

    public function searchClients($keyword, $limit, $start)
    {
        $this->db->select('*')
            ->from('client')
            ->join('user', 'user.idUser = client.idUser ', 'left');
        $this->db->where('client.idadmin', $_SESSION['idadmin']);

        if ($keyword != '') {
            $this->db->like('client.nomClient', $keyword);
            $this->db->or_like('client.prenomClient', $keyword);
            $this->db->or_like('client.adresseClient', $keyword);
            $this->db->or_like('client.telClient', $keyword);
            $this->db->or_like('client.r_social', $keyword);
        }


        if ($limit != '') {
            $this->db->limit($limit, $start);
        }
        $this->db->order_by('client.idClient', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function verifyIfClientInUse($id)
    {
        $res = $this->db->select('*')->from('client')->where('idadmin', $_SESSION['idadmin'])->where('idClient', $id)->get()->result();
        return $res;
    }

    public function verify_client($numero)
    {
        $data = $this->db->select("*")->from("client")->where('idadmin', $_SESSION['idadmin'])->where("telClient", $numero)->get()->result();
        return $data;
    }

    public function verify_num_clients($numero)
    {
        $data = $this->db->select("*")->from("client")->where('idadmin', $_SESSION['idadmin'])->where("telClient", $numero)->get()->result();
        return $data;
    }

    public function getALLClientWithCriteria($critere)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM client WHERE idClient <> '$critere' AND idadmin = '$idadmin'")->result();
        return $data;
    }

    public function verify_client_js($num, $mail)
    {
        $data = $this->db->select('*')
            ->from('client')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('telClient', $num)
            ->where('emailClient', $mail)
            ->get()->result();
        return $data;
    }
    public function verify_client_js_num($num)
    {
        $data = $this->db->select('*')
            ->from('client')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('telClient', $num)
            ->get()->result();
        return $data;
    }
}
