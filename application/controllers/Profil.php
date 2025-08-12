<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProfilModel', 'prof_m');
        $this->load->model('AdministrateurModel', 'admin');
        $this->load->model('Admin_model', 'auth');
    }



    public function index()
    {
        $this->jail();
        $data['title'] = 'Modification Profil';
        $data['css'] = 'profil.css';
        $data['js'] = 'profil.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['profile' => true]);
        $this->load->view('templates/tete');
        $this->load->view('profil');
        $this->load->view('templates/footer', $data);
    }

    public function editmail()
    {
        $mail = '';
        if (isset($_POST['email']) && $_POST['email'] != '') {
            $mail = strip_tags(trim($this->input->post('email')));
        }
        $verif = $this->auth->verifbymail($mail);
        $this->prof_m->updateAdminmail($mail);
        
        $this->session->set_userdata('updated', true);
        $this->redirect();
    }

    private function redirect()
    {
        redirect('Profil');
    }

    public function edit()
    {
        $pass = trim(strip_tags($this->input->post('ancienMdp')));
        $newpass =  trim(strip_tags($this->input->post('nouveauMdp')));

        $this->jail();
        if ($_SESSION['user_type'] == 'admin') {
            $user = $this->prof_m->getTheConnected($_SESSION['idadmin']);
            if (count($user) > 0) {
                $resultat_test_mdp = de_hash_it($pass, $user[0]->pass);
                if ($resultat_test_mdp) {
                    $this->prof_m->change_mdp($_SESSION['idadmin'], hash_it($newpass));
                    $this->session->set_userdata('updated', true);
                    $this->redirect();
                } else {
                    $this->session->set_userdata('error_mdp', 'mdp');
                    $this->redirect();
                }
            } else {
                $this->redirect();
            }
        } else {
            $user = $this->prof_m->getTheConnected_user($_SESSION['id_user']);

            if (count($user) > 0) {
                $resultat_test_mdp = de_hash_it($pass, $user[0]->pass);
                if ($resultat_test_mdp) {
                    $this->prof_m->change_mdp_user($_SESSION['id_user'], hash_it($newpass));
                    $this->session->set_userdata('updated', true);
                    $this->redirect();
                } else {
                    $this->session->set_userdata('error_mdp', 'mdp');
                    $this->redirect();
                }
            } else {
                $this->redirect();
            }
        }
    }

    public function checkProfil()
    {
        if ($_SESSION['user_type'] == 'admin') {
            $pass = strip_tags(trim($this->input->post('ancienMdp')));
            $mail = strip_tags(trim($this->input->post('ancienEmail')));

            $id = 1;

            $verMail = $this->prof_m->getInfoByUser($mail);

            $data = $this->prof_m->getPassByUser($id);

            $ver = de_hash_it($pass, $data[0]->pass);

            if ($ver == true && count($verMail) > 0) {
                echo json_encode([
                    'success' => true,
                    'id' => $id,
                    'option' => 'admin',
                ]);
            } elseif ($ver == false && count($verMail) >= 0) {
                echo json_encode([
                    'success' => false,
                    'id' => $id,
                    'option' => 'admin',
                ]);
            }
        } else {
            $pass = strip_tags(trim($this->input->post('ancienMdp')));
            $mail = strip_tags(trim($this->input->post('ancienEmail')));
            $id = $_SESSION['id_user'];

            $data = $this->prof_m->getPass($id);

            $ver = de_hash_it($pass, $data[0]->pass);

            $verMail = $this->prof_m->getInfo($mail);

            if ($ver == true && count($verMail) > 0) {
                echo json_encode([
                    'success' => true,
                    'id' => $id,
                ]);
            } elseif ($ver == false && count($verMail) >= 0) {
                echo json_encode([
                    'success' => false,
                    'id' => $id,
                ]);
            }
        }
    }
    public function verification()
    {
        $this->jail();
        $pass = '';
        if (isset($_POST['pass']) && $_POST['pass'] != '') {
            $pass = strip_tags(trim($this->input->post('pass')));
        }
        $mail = '';
        if (isset($_POST['mail']) && $_POST['mail'] != '') {
            $mail = strip_tags(trim($this->input->post('mail')));
        }
        if ($_SESSION['user_type'] == 'admin') {
            $idadmin = $_SESSION['idadmin'];
            $datas = [];
            if ($mail != '' && $pass == '') {
                $datas = $this->prof_m->getAdminMail($mail);
            } else if ($mail == '' && $pass != '') {
                $datas = $this->prof_m->getAdmin();
                if (count($datas) > 0) {
                    if (!de_hash_it($pass, $datas[0]->pass)) {
                        $datas = [];
                    }
                }
            }
            if (count($datas) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $datas
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'data' => $datas
                ]);
            }
        } else {
            $datas = [];
            if ($mail != '' && $pass == '') {
                $datas = $this->prof_m->getuserMail($mail);
            } else if ($mail == '' && $pass != '') {
                $id = $_SESSION['id_user'];
                $datas = $this->prof_m->getuser($id);
                if (count($datas) > 0) {
                    if (!de_hash_it($pass, $datas[0]->pass)) {
                        $datas = [];
                    }
                }
            }
            if (count($datas) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $datas
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'data' => $datas
                ]);
            }
        }
    }

    public function udpateProfil()
    {
        if ($_SESSION['user_type'] == 'admin') {
            $email = strip_tags(trim($this->input->post('nouvelleMail')));
            $pass = strip_tags(trim($this->input->post('nouveauMdp')));
            $id = strip_tags(trim($this->input->post('id')));

            if (!empty($email) && empty($pass)) {
                $data = [
                    'mail' => $email,
                ];
                $this->prof_m->edit($data, $id);
            } elseif (!empty($pass) && empty($email)) {
                $data = [
                    'pass' => hash_it($pass),
                ];
                $this->prof_m->edit($data, $id);
            } elseif (!empty($email) && !empty($pass)) {
                $data = [
                    'pass' => hash_it($pass),
                    'mail' => $email,
                ];
                $this->prof_m->edit($data, $id);
            }


            echo json_encode([
                'success' => true
            ]);
        } else {
            $pass = strip_tags(trim($this->input->post('nouveauMdp')));
            $id = $_SESSION['id_user'];

            $data = [
                'pass' => hash_it($pass),
            ];

            $this->prof_m->editUser($data, $id);

            echo json_encode([
                'success' => true
            ]);
        }
    }


    public function updateMode()
    {

        $mode = strip_tags(trim($this->input->post('mode')));

        if ($_SESSION['user_type'] == 'administrateur') {
            $this->admin->updateMode($mode, 1);
        } elseif ($_SESSION['user_type'] == 'admin') {
            $this->prof_m->updateModeAdmin($mode, $_SESSION['idadmin']);
        } else {
            $this->prof_m->updateModeUser($mode, $_SESSION['id_user']);
        }

        $_SESSION['mode'] = $mode;

        echo json_encode([
            'success' => $_SESSION['mode']
        ]);
    }
}
