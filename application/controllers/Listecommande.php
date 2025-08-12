<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Listecommande extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('ListeCommandeModel', 'listecommande');
    $this->load->model("FournisseurModel", "fournisseur_model");
    $this->load->model('Admin_model');
  }


  // ***************** UTILE *********** //
  public function index()
  {
    $this->jail();
    $li['title'] = 'Liste-commande';
    $li['css'] = 'listecommande.css';
    $js['js'] = 'listecommande.js';
    $page = 1;


    $data  = $this->listecommande->getAllListe($page);

    $allpv = $this->listecommande->getAllPv();

    $nPages = ceil(count($this->listecommande->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listecommande', [
      'data' => $data,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

  public function page($page = 1)
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'listecommande.css';
    $js['js'] = 'listecommande.js';

    $data  = $this->listecommande->getAllListe($page);

    $allpv = $this->listecommande->getAllPv();

    $nPages = ceil(count($this->listecommande->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listecommande', [
      'data' => $data,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

  public function search($page = 1)
  {

    // date
    $date_debut = trim(strip_tags($_POST['date_debut']));
    $heure_debut = trim(strip_tags($_POST['heure_debut']));

    $_POST['date_debut'] = $date_debut;
    $_POST['heure_debut'] = $heure_debut;
    // if ($heure_debut == '' && $date_debut != '')
    //   $heure_debut = '00:00:00';
    // else 
    if ($heure_debut != '' && $date_debut != '')
      $heure_debut .= ':00';


    $date_fin = trim(strip_tags($_POST['date_fin']));
    $heure_fin = trim(strip_tags($_POST['heure_fin']));


    $_POST['date_fin'] = $date_fin;
    $_POST['heure_fin'] = $heure_fin;
    // if ($heure_fin == '' && $date_fin != '')
    //   $heure_fin = '00:00:00';
    // else 
    if ($heure_fin != '' && $date_fin != '')
      $heure_fin .= ':00';

    if ($date_debut != '')
      $date_debut .= ' ' . $heure_debut;
    if ($date_fin != '')
      $date_fin .= ' ' . $heure_fin;
    // date


    $mot = strip_tags(trim($_POST['recherche']));

    $_POST['mot'] = $mot;


    $recherche = $this->listecommande->search($date_debut, $date_fin, $mot,  $page);

    $nPages = ceil(count($this->listecommande->search($date_debut, $date_fin, $mot)) / PAGINATION);

    $assets['title'] = 'Liste';
    $assets['css'] = 'listecommande.css';
    $js['js'] = 'listecommande.js';

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listecommande', [
      'data' => $recherche,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }


  public function deleteListe()
  {
    $idcommande = trim(strip_tags($_POST['idcommande']));

    $idcmfacture = '' ; 
    $cmfacture = $this->listecommande->getidCmfacture(  $idcommande ) ; 
    if ( count( $cmfacture ) > 0 ){
      $idcmfacture = $cmfacture[0]->idcmfacture  ; 
    }

    $this->session->set_flashdata('delete', 'success');
    // cmpanier
    $this->listecommande->deletePanier($idcommande);
    // vente
    $this->listecommande->deleteCommande($idcommande);
    // appro
    $this->listecommande->deleteAppro($idcmfacture);
    // depense
    $this->listecommande->deleteDepense($idcommande);


    echo json_encode([
      'success' => true
    ]);
  }

  public function getDetails()
  {
    $idcommande = '';
    if (isset($_POST['idcommande']) && $_POST['idcommande'] != '') {
      $idcommande = strip_tags(trim($_POST['idcommande']));
    }

    // $montant = $this->listecommande->getTotal($facture);
    // $apayer = $this->listecommande->getApayer($facture);


    $data = $this->listecommande->getFact($idcommande);


    // echo '<pre>' ;
    // var_dump( $data ) ; 
    // echo '</pre>' ; die  ; 





    $fournisseur = $data->tel_fournisseur;


    $affichage = '<div class ="row w-100 m-auto ">';
    if ($fournisseur != '') {
      $aboutallFournisseur = $this->fournisseur_model->getFournisseurByNumero($fournisseur);


      $affichage .= "<div class='col-6'>";

      if (count($aboutallFournisseur) == 0) {

        $affichage .= "<p><span  style='display :inline-block ; width :150px ; '> Numéro du fournisseur </span><span  '> :" . $fournisseur . "</span></p>";
      } else {

        $aboutFournisseur = $aboutallFournisseur[0];
        $affichage .= "<p>" . strtoupper($aboutFournisseur->nom_entr) . "</span></p>";

        $affichage .= "<p><span  style='display :inline-block ; width :30px ; '> Tél </span><span  '> : " . $fournisseur . "</span></p>";
      }

      $affichage .= "</div>";
    }









    $affichage .= '</div>';

    if ($fournisseur != '') {
      $affichage .= '<div style="border-top : 1px solid #cfcfcfcf " class ="row w-100  m-auto pt-1 ">';
    } else {
      $affichage .= '<div  class ="row w-100  m-auto pt-1 ">';
    }
    $affichage .= '<div class ="col-4">';
    $affichage .= "<p><span  style='display :inline-block ; width :100px ; '> Montant   </span><span  '> : " . number_three($data->montant_total) . "</span></p>";
    $affichage .= '</div>';
    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :120px ; '> Frais de livraison   </span><span  '> : " . number_three($data->frais) . "</span></p>";
    $affichage .= '</div>';


    $affichage .= '<div  class ="row w-100 ">';
    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :100px ; '> Montant Total   </span><span  '> : " . number_three($data->frais + $data->montant_total) . "</span></p>";
    $affichage .= '</div>';

    $affichage .= '</div>';




    // echo '<pre>' ;
    // var_dump( $data ) ; 
    // echo '</pre>' ; die ;
    $affichage .= '<div class="_tableau">';
    $affichage .= '<table class="table">';
    $affichage .= '<thead class="table-info">';
    $affichage .= '<tr>  
                    <th>Référence</th>
                    <th>Désignation</th>
                     
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Montant total</th>
                  </tr>
                </thead>
              <tbody>';

    foreach ($data->cmpanier as $key => $cmpanier) {
      $affichage .= "<tr>";
      $affichage .= "<td>" . $cmpanier->refProduit . "</td>";
      $affichage .= "<td>" . $cmpanier->designation . "</td>";
      $affichage .= "<td>" . number_three($cmpanier->prixunitaire) . "</td>";
      if ($cmpanier->quantite > 1) {
        if ($cmpanier->denomination) {
          $affichage .= "<td>" . $cmpanier->quantite . " </td>";
        } else {
          $affichage .= "<td>" . $cmpanier->quantite . "</td>";
        }
      } else {
        $affichage .= "<td>" . $cmpanier->quantite . "</td>";
      }
      $affichage .= "<td>" . number_three($cmpanier->prixunitaire * $cmpanier->quantite) . "</td>";
      $affichage .= "</tr>";
    }

    $affichage .= '</tbody>
      </table>
      </div class>';



    echo $affichage;
  }
  // ***************** UTILE *********** //


  public function facture($fact)
  {
    $info = $this->listecommande->getAllInfo($fact);
    $idcommande = '';
    if (count($info) > 0) {
      $idcommande = $info[0]->idcommande;
    }

    $tous = $this->listecommande->getFacture($idcommande);

    $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


    $total['ht'] = $tous->montant_payer;
    $TVA = $total['ht'] * 20 / 100;
    $total['tva'] = $TVA;
    $total['ttc'] = $TVA + $total['ht'];


    // echo '<pre>' ; 
    // var_dump( $tous ) ; 
    // echo '</pre>' ; die  ; 

    $this->load->view('facture', [
      'fact' => $tous,
      'total' => $total,
      'admin' => $admin
    ]);
  }

  public function getInfoFact()
  {
    $idcommande = strip_tags(trim($_POST['idcommande']));

    $res = $this->listecommande->getAllInfo($idcommande);

    if (count($res) > 0) {
      echo json_encode([
        'success' => true,
        'data' => $res[0]->refProduit
      ]);
    } else {
      echo json_encode([
        'success' => false
      ]);
    }
  }
}
