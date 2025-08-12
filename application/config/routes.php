<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/** Routing application */

//REDIRECTION
// *pagination 
$route['prix/(:num)'] = 'Prix/prix';
$route['produit/(:num)'] = 'AjouterProduit/ajouterProduit';
$route['pointVente/(:num)'] = 'PointVente/index';
$route['recherchePrix/(:num)'] = 'Prix/recherchePrix';



$route['Auth'] = 'Auth/index';
$route['pointDeVente'] = 'PointVente/index';
$route['test'] = 'Auth/test';
$route['connexion'] = 'Auth/connexion';
$route['inscription'] = 'Auth/inscription';
$route['inscrire'] = 'Auth/inscrire';
$route['produit'] = 'AjouterProduit/ajouterProduit';
$route['prix'] = 'Prix/prix';
// $route['facture'] = 'AjouterProduit/facture';
// $route['client'] = 'AjouterProduit/client';
$route['facture'] = 'Facture/facture';
$route['client'] = 'Clients';
$route['appro'] = 'Appro/index';
$route['user'] = 'User/index';
$route['vente'] = 'Vente/index';
$route['liste'] = 'Liste';
$route['deconnexion'] = 'Auth/deconnexion';
$route['entreprise'] = 'Auth/entreprise';


//PRODUIT
$route['DonnerProduit'] = 'AjouterProduit/DonnerProduit';
$route['deleteProd'] = 'AjouterProduit/deleteProd';
$route['editProd'] = 'AjouterProduit/editProd';
$route['verifProd'] = 'AjouterProduit/verifProd';
$route['recherche'] = 'AjouterProduit/recherche';

// Client
$route['validationClient'] = 'Clients/validationClient';
$route['registerClient'] = 'Clients/registerClient';
$route['deleteClient'] = 'Clients/deleteClient';
$route['rechercheClient'] = 'Clients/rechercheClient';
$route['editClient'] = 'Clients/editClient';
$route['registerAjout'] = 'AjouterProduit/registerAjout';
$route['enregistrer-clients'] = 'Clients/register';

// Ajout
$route['editAjout'] = 'AjouterProduit/editAjout';
$route['recharcheAjout'] = 'AjouterProduit/recharcheAjout';
$route['registerMat'] = 'AjouterProduit/register';


// Prix
$route['registerPrix'] = 'Prix/registerPrix';
$route['editPrix'] = 'Prix/editPrix';
$route['recherchePrix'] = 'Prix/recherchePrix';
$route['rechercherProd'] = 'Prix/rechercherProd';

// Facture
$route['registerFacture'] = 'Facture/registerFacture';
$route['editFacture'] = 'Facture/editFacture';
$route['rechercheFacture'] = 'Facture/rechercheFacture';



$route['udpate-profil'] = 'Profil/change_profil';

// Approvisionnement 
$route['registerAppro'] = 'Appro/registerAppro';
$route['rechercher_appro'] = 'Appro/rechercher_appro';
$route['deleteAppro'] = 'Appro/deleteAppro';

// point de vente 
$route['ajoutPV'] = 'PointVente/ajoutPV';
$route['recherchePV'] = 'PointVente/recherchePV';
$route['editPv'] = 'PointVente/editPv';
$route['deletePv'] = 'PointVente/deletePv';
$route['verifPv'] = 'PointVente/verifPv';

//USER 
$route['registerUser'] = 'User/registerUser';
$route['deleteUser'] = 'User/deleteUser';
$route['DonnerUser'] = 'User/DonnerUser';
$route['editUser'] = 'User/editUser';
$route['verifUser'] = 'User/verifUser';
$route['rechercheUser'] = 'User/rechercheUser';
$route['mdpUser'] = 'User/mdpUser';

//PROFIL
$route['modifProfil'] = 'Profil/editMdp';
$route['checkProfil'] = 'Profil/checkProfil';
$route['udpateProfil'] = 'Profil/udpateProfil';
$route['profilEdit'] = 'Profil';

//CODEBARRE
$route['codeBarre'] = 'CodeBarre';
$route['codeBarre-num'] = 'CodeBarre/codeBarreNum';
$route['codeBarre-num/(:any)'] = 'CodeBarre/imprimNum/$1';


$route['creatCode'] = 'CodeBarre/creatCode';
$route['registerCode'] = 'CodeBarre/registerCode';
$route['validRef'] = 'CodeBarre/validRef';
$route['deleteCode'] = 'CodeBarre/deleteCode';
$route['rechercheRef'] = 'CodeBarre/rechercheRef';
$route['rechercheDate'] = 'CodeBarre/rechercheDate';
$route['infoCode'] = 'CodeBarre/infoCode';
$route['impression'] = 'CodeBarre/impression';


//VENTE
$route['rechercher_mat_vente'] = 'Vente/rechercher_mat_vente';
$route['getpv'] = 'Vente/getpv';
$route['validate'] = 'Vente/validate';
$route['facturation'] = 'Vente/facturation';

//STOCK
$route['rechercheStock'] = 'Stock/rechercheStock';
$route['Stock-seuil'] = 'Stock/seuil';
$route['Stock-all'] = 'StockAll';
$route['Stock-all/(:any)'] = 'StockAll/$1';

//LISTE
$route['getDetails'] = 'Liste/getDetails';


$route['get-modepaiement'] = 'Mode/get_modepaiement';
$route['verifier-client'] = 'Clients/rechercherClientForFacturation';
// $route['validate'] = "Facture/validate";
$route['search-with-date'] = "Liste/search_date";
$route['get-details'] = "Liste/getDetails";
$route['stock'] = "Stock";


// Facturation pdf
$route['facture/(:any)'] = 'Liste/facture/$1';


//Vente round 2
$route['getAllClient'] = 'Clients/getAllClient';

$route['searchNumSerie'] = 'Vente/searchNumSerie';


//Appro 2.0
$route['verifNumSerie'] = 'Appro/verifNumSerie';
$route['searchApp'] = 'Appro/searchApp';

//Facture 2.0
$route['getInfoFact'] = 'Liste/getInfoFact';

//CodeBarre 2.0
$route['getAllCode'] = 'CodeBarre/getAllCode';

// *filtre 
$route['filtre'] = 'Stock/filtre';
$route['filtre_seuil'] = 'Stock/filtre_seuil';
$route['filtre-all'] = 'StockAll/filtre';
$route['ordonner'] = 'Stock/ordonner';

// 

// ETAT 
$route['etat'] = 'Etat';


$route['CodeBarre/creatCode/(:any)'] = 'CodeBarre/creatCode/$1';



$route['emploi'] = 'Emploi';
$route['emploiMl'] = 'Emploi/index/ml';
$route['admin'] = 'Admin';
$route['Admin/client'] = 'Dashclient';
$route['Admin/tuto'] = 'Dashtuto';
$route['Admin/tutMal'] = 'Dashtuto';
$route['Admin/tutoFr'] = 'Dashtuto/francais';

// abonnement 
$route['abonnement'] = 'Abonnement';


// Unité
$_route['unite'] = 'Unite';


// COMMANDE 
$route['fournisseur'] = 'Fournisseur';
$route['commande'] = 'Commande';
$route['listecommande'] = 'Listecommande';
$route['reception'] = 'Reception';


// Proforma 
$route['proforma'] = 'Proforma';

// transfert 
$route['trasnfert'] = 'Transfert';

// mode de paiement 
$route['mode_de_paiment'] = 'Mode/mode_de_paiment';
$route['enregistrer-mode'] = 'Mode/register';
$route['DonneMode'] = 'Mode/DonneMode';
$route['deleteMode'] = 'Mode/deleteMode';
$route['verify_mode_if_exist'] = 'Mode/verify_mode_if_exist';
$route['editMode'] = 'Mode/editMode';

/*************** CLIENT ****************** */
$route['suivi-client'] = 'Suivi/index';
$route['suivi-client/filtre'] = 'Suivi/search';
$route['suivi-client/details'] = 'Suivi/details';
$route['suivi-client/details/(:any)'] = 'Suivi/details/$1';
$route['suivi-client/details-filtre'] = 'Suivi/detailsSearch';
$route['suivi-client/details-filtre/(:any)'] = 'Suivi/detailsSearch/$1';
/*************** CLIENT ****************** */
