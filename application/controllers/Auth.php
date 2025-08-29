<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('UserModel',  'user');
    }

    public function test()
    {
        $this->load->view('test_pdf', []);
    }

    public function index()
    {
        $error = null;
        if ($this->session->error) {
            $error = $this->session->error;
        }

        $this->load->view('templates/header', [
            "title" => 'Connexion',
            "css" => 'connexionstyle.css'
        ]);
        $this->load->view('connexion', [
            "error" => $error
        ]);

        $this->load->view('templates/footer', [
            "js" => 'fonction.js'
        ]);
    }
    public function inscription()
    {
        $error = null;
        if ($this->session->error) {
            $error = $this->session->error;
        }

        redirect('Auth');

        $this->load->view('templates/header', [
            "title" => 'Inscription',
            "css" => 'connexionstyle.css'
        ]);
        $this->load->view('inscription', [
            "error" => $error
        ]);

        $this->load->view('templates/footer', [
            "js" => 'fonction.js'
        ]);
    }

    public function inscrire()
    {
        $nom   =   trim(strip_tags($_POST['nom']));
        $prenom   =   trim(strip_tags($_POST['prenom']));
        $mail   =   trim(strip_tags($_POST['mail']));
        $pass   =   trim(strip_tags($_POST['pass']));
        $pass_conf   =   trim(strip_tags($_POST['pass_conf']));
        $entreprise   =   trim(strip_tags($_POST['entreprise']));
        $tel   =   trim(strip_tags($_POST['tel']));


        $verif = $this->Admin_model->verifbymail($mail);


        if (count($verif) > 0) {
            echo json_encode([
                'success' => false,
                'type' => 'exist'
            ]);
        } else {
            if ($pass == $pass_conf) {
                $data = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'mail' => $mail,
                    'pass' => hash_it($pass),
                    'entreprise' => $entreprise,
                    'teladmin' => $tel,
                    'dateinscription' => date('Y-m-d H:i:s')
                ];
                $id_admin = $this->Admin_model->insert($data);

                $this->Admin_model->insertModeEspece($id_admin);

                echo json_encode([
                    'success' => true,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'type' => 'pass'
                ]);
            }
        }
    }

    public function connexion()
    {
        $id = 1;
        $email = strip_tags(trim($this->input->post('mail')));
        $pass = strip_tags(trim($this->input->post('pass')));

        // $password = $this->Admin_model->verifyPassword($id);
        // $email_check = $this->Admin_model->verifyEmail($id, $email);

        // $mdp = de_hash_it($pass, $password[0]->pass);
        $this->form_validation->set_rules('mail', 'mail', 'required|valid_email', [
            'required' => 'ce champ est obligatoire',
            'valid_email' => 'L\'email doit être sous la forme example@gmail.com'

        ]);
        $this->form_validation->set_rules('pass', ' pass', 'required', [
            'required' => 'Ce champ est obligatoire'
        ]);

        if ($this->form_validation->run()) {
            $email = strip_tags($this->input->post('mail'));
            $pass = strip_tags($this->input->post('pass'));


            $activ = 'admin';
            $connect = $this->Admin_model->connexion($email);
            if (count($connect) == 0) {
                $connect = $this->user->connexion($email);
                $activ = 'user';
            }

            if (count($connect)) {
                $resultat_test_mdp = de_hash_it($pass, $connect[0]->pass);
                if (isset($email) && $resultat_test_mdp === true) {
                    $this->session->set_userdata('show_stock_alert', true);
                    if ($activ == 'admin') {
                        $_SESSION['user_type'] = 'admin';
                        $_SESSION['email'] = $email;
                        $_SESSION['idadmin'] = $connect[0]->idAdmin;
                        $_SESSION['let_test'] = true;
                        $_SESSION['time_rest'] = '';
                        $_SESSION['abonne'] = true;
                        $_SESSION['mode'] = $connect[0]->mode;

                    } else {
                        $_SESSION['user_type'] = 'user';
                        $_SESSION['email'] = $email;
                        $_SESSION['pv'] = $connect[0]->idPointVente;
                        $_SESSION['id_user'] = $connect[0]->idUser;
                        $_SESSION['idadmin'] = $connect[0]->idadmin;
                        $_SESSION['let_test'] = true;
                        $_SESSION['abonne'] = true;
                        $_SESSION['time_rest'] = '';
                        $_SESSION['mode'] = $connect[0]->mode;
                    }
                    redirect('vente') ; 
                } else {
                    $this->session->set_flashdata('error', 'Mot de passe ou l\'email incorrect. Veuillez réessayer.');
                    redirect('Auth');
                }
            } else {
                $this->session->set_flashdata('error', 'Mot de passe ou l\'email incorrect. Veuillez réessayer.');
                redirect('Auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Mot de passe ou l\'email incorrect. Veuillez réessayer.');
            redirect('Auth');
        }
    }

    public function deconnexion()
    {
        session_destroy();
        redirect('Auth');
    }

    public function getuseractive()
    {
        $name = '';
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'admin') {
                $idadmin  = $_SESSION['idadmin'];
                $user = $this->Admin_model->getuseractive($idadmin);
                if (count($user) > 0) {
                    $name = ucfirst($user[0]->prenom);
                }
            } else if ($_SESSION['user_type'] == 'user') {
                $iduser = $_SESSION['id_user'];
                $user = $this->Admin_model->getgetuseractive_user($iduser);

                if (count($user) > 0) {
                    $name = ucfirst($user[0]->prenomUser);
                }
            }
        }


        echo json_encode($name);
    }


    // **************************  //


    public function entreprise()
    {
        // $this->jail()  ; 
        $asset['title'] = 'Mon entreprise';
        $asset['css'] = 'entreprise.css';
        $asset['js'] = 'entreprise.js';





        $entreprise = [];
        $id = 0;
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
            $id = $_SESSION['idadmin'];
            $entreprise = $this->Admin_model->getuseractive($id);
        }



        $this->load->view('templates/header', $asset);
        $this->load->view('templates/sidebar_entr', ["entreprise" => true]);
        $this->load->view('templates/tete');
        $this->load->view('entreprise', [
            'data' => $entreprise
        ]);
        $this->load->view('templates/footer', $asset);
    }

    public function EditEnt()
    {
        $nom =  trim(strip_tags($_POST['entreprise']));
        $telephone =  trim(strip_tags($_POST['telephone']));
        $nif =  trim(strip_tags($_POST['nif']));
        $stat =  trim(strip_tags($_POST['stat']));
        $adresse =  trim(strip_tags($_POST['adresse']));

        $filename = $_FILES['photo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $unique_name = time() . '_' . uniqid('logo_', true) . '_' . $_SESSION['idadmin'] . '.' . $ext;

        $photo = move_uploaded_file($_FILES['photo']['tmp_name'], 'public/upload/logo/' . $unique_name);



        if ($_FILES['photo']['name'] != '') {
            $data = [
                'entreprise' => $nom,
                'logo' => 'public/upload/logo/' . $unique_name,
                'tel' => $telephone,
                'nif' => $nif,
                'stat' => $stat,
                'adresse' => $adresse
            ];
        } else {
            $data = [
                'entreprise' => $nom,
                'tel' => $telephone,
                'nif' => $nif,
                'stat' => $stat,
                'adresse' => $adresse
            ];
        }

        $this->Admin_model->EditEnt($data, $_SESSION['idadmin']);

        $this->session->set_flashdata('success', true);
        redirect('entreprise');
    }
    // **************************  //
}
