<?php

use function PHPUnit\Framework\containsOnly;

class Transfert extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();die;
        $this->load->model('TransfertModel', 'transfert');
        $this->load->model('VenteModel', 'vente');
        $this->load->model('ApproModel', 'appro');

    }

    public function receive()
    {
        $idtransfert = '';
        if (isset($_POST['idtransfert']) &&  $_POST['idtransfert'] != '') {
            $idtransfert = trim(strip_tags($_POST['idtransfert']));
        }

        $data = $this->transfert->getByid($idtransfert);


        // echo '<pre>' ;
        // var_dump( $data ) ; 
        // echo '</pre>' ; die  ; 

        if (count($data) > 0) {
            $data = $data[0];
            $data_appro = [
                'idProduit' => $data->idProduit , 
                'quantite' => $data->qunatite_transfert , 
                'numero' => $data->qunatite_transfert , 
                'imei1' => $data->imei1 , 
                'imei2' => $data->imei2 , 
                'couleur' => $data->couleur , 
                'idPointVente' => $data->idPointVente_destination , 
                'idadmin' => $_SESSION['idadmin'] , 
                'idtransfert' =>$data->idtransfert , 
            ] ;

            if (!$data->reception_transfert) {
                $this->appro->insertAppro( $data_appro) ; 
                $this->transfert->receive($idtransfert);
                echo json_encode([
                    'success' => true
                ]);
            } else {
                echo json_encode([
                    'success' => false
                ]);
            }
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }

}
