<?php

use PHPUnit\Framework\Constraint\Count;
use SebastianBergmann\LinesOfCode\Counter;

class ReceptionModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search($recherche = '')
    {
        $commandes = $this->db->select('*')
            ->from('commande')
            ->join('fournisseur' , 'fournisseur.tel_fournisseur = commande.tel_fournisseur' , 'left')
            ->where('commande.cmfacture', $recherche)
            ->where('commande.idadmin', $_SESSION['idadmin'])
            ->get()->result();
        if (count($commandes) > 0) {
            $commandes = $commandes[0];
            $idcommande = $commandes->idcommande;

            $paniers = $this->db->select('*')
                ->from('cmpanier')
                ->join('unite', 'unite.idunite = cmpanier.idunite', 'left')
                ->join('produit', 'produit.idProduit = cmpanier.idProduit', 'left')
                ->where('cmpanier.idcommande', $idcommande)
                ->get()->result();
            $commandes->paniers = $paniers;
        }
        return $commandes;
    }

    public function getUnite ( $idProduit ){
        return $this->db->select('*')
                ->from('unite')
                ->where('idProduit' , $idProduit )
                ->where('idadmin' , $_SESSION['idadmin'])
                ->get()->result() ; 
    }

    public function pointvente( ){
        return $this->db->select('*')
                    ->from('pointvente')
                    ->where('idadmin' , $_SESSION['idadmin'])
                    ->get()->result() ; 
    }
}
