<?php
class FournisseurModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($data)
    {
        $this->db->insert('fournisseur', $data);
        return $this->db->insert_id();
    }

    public function getAll($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }
        $this->db->select('*')
            ->from('fournisseur')
            ->where('idadmin', $_SESSION['idadmin']);

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->order_by('idfournisseur', 'desc')
            ->get()->result();
    }
    public function search($key = '',  $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }
        $this->db->select('*')
            ->from('fournisseur')
            ->where('idadmin', $_SESSION['idadmin']);

        if ($key != '') {
            $this->db->like('nom_entr', $key);
            $this->db->or_like('adresse_fournisseur', $key);
            $this->db->or_like('tel_fournisseur', $key);
            $this->db->or_like('nif_fournisseur', $key);
            $this->db->or_like('stat_fournisseur', $key);
            $this->db->or_like('mail_fournisseur', $key);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->order_by('idfournisseur', 'desc')
            ->get()->result();
    }

    public function delete($idfournisseur)
    {
        $this->db->where('idfournisseur', $idfournisseur)->delete('fournisseur');
    }
    public function edit($data, $idfournisseur)
    {
        $this->db->where('idfournisseur', $idfournisseur)->update('fournisseur', $data);
    }

    public function duplicatenom($nom = '', $id_not = '')
    {
        $this->db->select('*')
            ->from('fournisseur')
            ->where('nom_entr', $nom)
            ->where('idadmin', $_SESSION['idadmin']);
        if ($id_not != '') {
            $this->db->where('idfournisseur <>', $id_not);
        }
        return $this->db->get()->result();
    }
    public function duplicatemail($mail = '', $id_not = '')
    {
        $this->db->select('*')
            ->from('fournisseur')
            ->where('mail_fournisseur', $mail)
            ->where('mail_fournisseur <>', '')
            ->where('idadmin', $_SESSION['idadmin']);
        if ($id_not != '') {
            $this->db->where('idfournisseur <>', $id_not);
        }
        return $this->db->get()->result();
    }
    public function duplicatetel($telephone = '', $id_not = '')
    {
        $this->db->select('*')
            ->from('fournisseur')
            ->where('tel_fournisseur', $telephone)
            ->where('idadmin', $_SESSION['idadmin']);
        if ($id_not != '') {
            $this->db->where('idfournisseur <>', $id_not);
        }
        return $this->db->get()->result();
    }

    public function getFournisseurByNumero($numero)
    {
        return $this->db->select('*')
            ->from('fournisseur')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('tel_fournisseur', $numero)
            ->get()->result();
    }
    public function getFournisseurByid($id)
    {
        return $this->db->select('*')
            ->from('fournisseur')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idfournisseur ', $id)
            ->get()->result();
    }

    public function deleteCommande($telephone)
    {

        // selectionnon tout les commande pour supprimer le panier lier 
        $commandes = $this->db->select('*')
            ->from('commande')
            ->where('tel_fournisseur', $telephone)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();

        // panier commande
        $idcmpanier = [];
        foreach ($commandes as $key => $commande) {

            $cmpaniers = $this->db->select('*')
                    ->from('cmpanier')
                    ->where('idadmin' , $_SESSION['idadmin'])
                    ->where('idcommande', $commande->idcommande)
                    ->get()->result() ;
                    
            foreach ($cmpaniers  as $key => $panier) {
                $idcmpanier[] = $panier ;
            }

            $this->db->where('idadmin', $_SESSION['idadmin'])
                ->where('idcommande', $commande->idcommande)
                ->delete('cmpanier');
            $this->db->where('idadmin', $_SESSION['idadmin'])
                ->where('idcommande', $commande->idcommande)
                ->delete('depense');
        }




        // commande 
        $this->db->where('idadmin', $_SESSION['idadmin'])
            ->where('tel_fournisseur', $telephone)
            ->delete('commande');


        // delete appro et depense 
        foreach ($idcmpanier as $key => $idcm) {
            $this->db->where('idadmin' , $_SESSION['idadmin'])
                    ->where('idcmfacture' , $idcm->idcmfacture )
                    ->delete('appro') ; 

        }

    }
}
