<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Liste extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("ClientsModel", "Clients_model");
    $this->load->model('ListeModel', 'liste');
    $this->load->model('Admin_model');
  }


  // ***************** UTILE *********** //
  public function index()
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'liste.css';
    $js['js'] = 'liste.js';
    $page = 1;


    $data  = $this->liste->getAllListe($page);

    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('liste', [
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
    $li['css'] = 'liste.css';
    $js['js'] = 'liste.js';

    $data  = $this->liste->getAllListe($page);

    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('liste', [
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
    $lieu = '';

    $_POST['mot'] = $mot;
    $_POST['lieu'] = $lieu;


    $recherche = $this->liste->search($date_debut, $date_fin, $mot, $lieu, $page);
    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->search($date_debut, $date_fin, $mot, $lieu)) / PAGINATION);

    $assets['title'] = 'Liste';
    $assets['css'] = 'liste.css';
    $js['js'] = 'liste.js';

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('liste', [
      'data' => $recherche,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }


  public function deleteListe()
  {
    $idfacture = trim(strip_tags($_POST['idfacture']));

    $this->session->set_flashdata('delete', 'success');
    // panier
    $this->liste->deletePanier($idfacture);
    // vente
    $this->liste->deleteVente($idfacture);

    echo json_encode([
      'success' => true
    ]);
  }
  // ***************** UTILE *********** //
  public function getDetails()
  {
    $idfacture = '';
    if (isset($_POST['idfacture']) && $_POST['idfacture'] != '') {
      $idfacture = strip_tags(trim($_POST['idfacture']));
    }
    $data = $this->liste->getFact($idfacture);
    $client = $data->telClient;

    $affichage = '<div class ="row w-100 m-auto ">';
    if ($client != '') {
      $aboutallClient = $this->Clients_model->getClientByNumClient($client);


      // CSS pour gérer le responsive
      $affichage .= "<style>
.client-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    flex-wrap: wrap; /* permet de passer à la ligne si pas assez de place */
}
.client-infos {
    flex: 1;
    min-width: 200px; /* largeur minimale avant que ça saute */
}
.client-image {
    flex-shrink: 0;
}
.client-image img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 8px;
    border: 1px solid #ccc;
    object-fit: cover;
}
/* Sur écran de moins de 600px, on centre l'image en dessous */
@media (max-width: 600px) {
    .client-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }
    .client-image {
        margin-top: 10px;
    }
}
</style>";

      $affichage .= "<div class='col-6'>";
      $affichage .= "<div class='client-wrapper'>"; // Flexbox pour infos + image

      // Bloc infos
      $affichage .= "<div class='client-infos'>";

      if (count($aboutallClient) == 0) {
        $affichage .= "<p><span style='display:inline-block; width:150px;'>Numéro du client</span> : " . $client . "</p>";
      } else {
        $aboutClient = $aboutallClient[0];
        $affichage .= "<p><span style='display:inline-block; width:120px;'>Tél client</span> : " . $client . "</p>";

        if ($aboutClient->nomClient != '') {
          $affichage .= "<p><span style='display:inline-block; width:120px;'>Nom</span> : " . strtoupper($aboutClient->nomClient) . "</p>";
          $affichage .= "<p><span style='display:inline-block; width:120px;'>Prénom</span> : " . ucfirst($aboutClient->prenomClient) . "</p>";
        } else {
          $affichage .= "<p><span style='display:inline-block; width:120px;'>Entreprise</span> : " . strtoupper($aboutClient->r_social) . "</p>";
        }
      }
      $affichage .= "</div>"; // fin infos

      // Bloc image
      if (!empty($aboutClient->image_profil)) {
        $imgUrl = base_url($aboutClient->image_profil);
        $affichage .= "<div class='client-image mb-3'>
                      <img src='" . $imgUrl . "' alt='Profil'>";

        $affichage .= "</div>";
      }
      $affichage .= "<div>";
      // Liens vers les photocopies CIN si présentes
      if (!empty($aboutClient->cin_recto)) {
        $cinRectoUrl = base_url($aboutClient->cin_recto);
        $affichage .= "<a href='" . $cinRectoUrl . "' target='_blank' style='display:inline-block;margin-right:10px; font-size:12px'>Voir CIN recto</a>";
      }
      if (!empty($aboutClient->cin_verso)) {
        $cinVersoUrl = base_url($aboutClient->cin_verso);
        $affichage .= "<a href='" . $cinVersoUrl . "' target='_blank' style='display:inline-block;margin-top:0px; font-size:12px'>Voir CIN verso</a>";
      }
      $affichage .= "</div>";

      $affichage .= "</div>"; // fin wrapper
      $affichage .= "</div>"; // fin col-6

    }









    $affichage .= '</div>';

    if ($client != '') {
      $affichage .= '<div style="border-top : 1px solid #cfcfcfcf " class ="row w-100  m-auto pt-1 ">';
    } else {
      $affichage .= '<div  class ="row w-100  m-auto pt-1 ">';
    }
    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :110px ; '> Montant total  </span><span  '> : " . number_three($data->montant_total) . "</span></p>";
    $affichage .= '</div>';

    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ;  '> Remise  </span><span > : " . number_three($data->montant_total - $data->montant_payer) . "</span></p>";

    $affichage .= '</div>';
    $affichage .= '</div>';
    $affichage .= '<div  class ="row w-100  m-auto pt-1 ">';
    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :140px  ; '> Frais de livraison  </span><span > : " . number_three($data->frais) . "</span></p>";

    $affichage .= '</div>';
    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :140px ; font-weight : 600 ; '> Montant à payer  </span><span   text-decoration : underline'> : " . number_three($data->montant_payer + $data->frais) . "</span></p>";

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
                    <th>Numéro de série</th>
                    <th>IMEI</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Montant total</th>
                    <th>Montant à payer</th>
                    <th>Remise</th>
                  </tr>
                </thead>
              <tbody>';

    foreach ($data->panier as $key => $panier) {
      $affichage .= "<tr>";
      $affichage .= "<td>" . $panier->refProduit . "</td>";
      $affichage .= "<td>" . $panier->designation . "</td>";

      if ($panier->numero != '') {
        $affichage .= "<td>" . $panier->numero . "</td>";
        $affichage .= "<td>" . $panier->imei1 . " </td>";
      } else {
        $affichage .= "<td>--</td>";
        $affichage .= "<td>--</td>";
      }
      $affichage .= "<td>" . number_three($panier->prixunitaire) . "</td>";
      if ($panier->quantite > 1) {
        if (isset($panier->denomination)) {
          $affichage .= "<td>" . $panier->quantite . " </td>";
        } else {
          $affichage .= "<td>" . $panier->quantite . "</td>";
        }
      } else {
        $affichage .= "<td>" . $panier->quantite . "</td>";
      }
      $affichage .= "<td>" . number_three($panier->prixunitaire * $panier->quantite) . "</td>";
      $affichage .= "<td>" . number_three($panier->prixunitaire * $panier->quantite - $panier->remise) . "</td>";
      $affichage .= "<td>" . number_three($panier->remise) . "</td>";
      $affichage .= "</tr>";
    }

    $affichage .= '</tbody>
      </table>
      </div class>';

    $affichage .= '<div class ="_boutton " >';
  $affichage .= "<div class='bg-success p-2 text-center text-light ' style ='border-radius:1px'>";
  // Affichage du lieu (adresse du client)
  $lieu = isset($aboutClient->adresseClient) ? $aboutClient->adresseClient : '';
  $affichage .= "<p class='m-0'><span  style='  '>Lieu </span><span  '> : " . $lieu . "</span></p>";
  $affichage .= "</div>";

    echo $affichage;
  }

  public function facture($fact)
  {
    $info = $this->liste->getAllInfo($fact);
    $idfacture = '';
    if (count($info) > 0) {
      $idfacture = $info[0]->idfacture;
    }

    $tous = $this->liste->getFacture($idfacture);

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
    $idfacture = strip_tags(trim($_POST['idfacture']));

    $res = $this->liste->getAllInfo($idfacture);

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
