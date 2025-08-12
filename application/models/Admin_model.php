<?php
class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert('admin', $data);

        return $this->db->insert_id() ; 
    }
    public function insertModeEspece( $idadmin )
    {
        $this->db->insert('modepaiement', [
            'denom' => 'Espèce' , 
            'numeroCompte' =>'-' , 
            'idadmin' => $idadmin 
        ]);

        return $this->db->insert_id() ; 
    }

    public function verifyPassword($id)
    {
        $this->db->select('pass');
        $this->db->from('admin');
        $this->db->where('idAdmin', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function verifyEmail($id, $email)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('idAdmin', $id)->like('mail', $email);
        $query = $this->db->get();
        return $query->result();
    }

    public function connexion($email)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('mail', $email);
        $query = $this->db->get();
        return $query->result();
    }

    public function verifbymail($mail)
    {
        $admin =  $this->db->select('*')
            ->from('admin')
            ->where('mail', $mail)
            ->get()->result();
        if ( count( $admin ) == 0 ){
            $admin = $this->db->select('*')
                        ->from('user')
                        ->where('mail' , $mail )
                        ->get()->result() ; 
        }

        return $admin ; 
    }

    public function getuseractive($id)
    {
        return $this->db->select('*')
            ->from('admin')
            ->where('idAdmin', $id)
            ->get()->result();
    }
    public function getgetuseractive_user($id)
    {
        return $this->db->select('*')
            ->from('user')
            ->where('idUser ', $id)
            ->get()->result();
    }

    public function EditEnt($data, $id)
    {
        $this->db->where('idAdmin', $id)->update('admin',  $data);
    }

    public function getAbonnement($contact)
    {
        $this->db->select('*')
            ->from('abonnement');
            if ( $contact != null ){

                return $this->db->where("REPLACE(contact, ' ', '') = ", str_replace(' ', '', $contact), FALSE)
                ->get()->result();
            }else {
                
                return $this->db->where("REPLACE(contact, ' ', '') = ", $contact  ,  FALSE)
                ->get()->result();
            }
    }
}
