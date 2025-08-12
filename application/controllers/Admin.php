<?php

use PhpParser\Node\Expr\PostDec;

use function PHPUnit\Framework\containsOnly;

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdministrateurModel', 'admin');
        $this->load->model('Admin_model', 'auth');
    } 

    public function index()
    {
        $data['title'] = 'Admin';
        $data['css'] = 'connexionstyle3.css';
        $js['js'] = 'dashboard.js';
        $this->load->view(TEMPLATEADMIN . 'header', $data);
        $this->load->view('administrateurconn');
        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }
    public function connexion()
    {
        $pass = trim(strip_tags($_POST['pass']));
        $identifiant = trim(strip_tags($_POST['mail']));

        $admin = $this->admin->getAdminByIdentifiant($identifiant);

        if (count($admin) > 0) {
            $test = de_hash_it($pass,  $admin[0]->password);

            if ($test) {

                $_SESSION['user_type']  = 'administrateur';
                $_SESSION['user']  = 'administrateur';
                $_SESSION['id_']  = $admin[0]->id_admin;
                $_SESSION['mode'] = $admin[0]->mode;

                redirect('Admin/client');
                
            } else {
                $this->session->set_flashdata('error', true);
                redirect('admin');
            }
        } else {
            $this->session->set_flashdata('error', true);
            redirect('admin');
        }
    }


    public function editProfil()
    {
        $assets['title'] = 'Admin';
        $assets['css'] = 'dashboard.css';
        $assets['js'] = 'dashboard.js';


        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('adminprofil');
        $this->load->view(TEMPLATEADMIN . 'footer', $assets);
    }


    public function upadteprof()
    {
        $an_pass = trim(strip_tags($_POST['an_pass']));
        $an_mail = trim(strip_tags($_POST['an_mail']));
        $newmail = trim(strip_tags($_POST['mail']));
        $newpass = trim(strip_tags($_POST['pass']));

        if ($an_mail != '' && $an_pass != '') {
            $admin = $this->admin->getAdminByIdentifiant($an_mail);
            if (count($admin) == 0) {
                $this->session->set_flashdata('error', true);
                redirect('Admin/editProfil');
            } else {
                $test = de_hash_it($an_pass,  $admin[0]->password);
                if ($test) {
                    if ( $newmail != ''){
                        $data = [
                            "identifiant" => $newmail , 
                            'password' => hash_it($newpass ) 
                        ] ; 
                    }
                    else {
                        $data = [
                            'password' =>hash_it($newpass ) 
                        ] ; 
                    }

                    $this->admin->changeprofil( $data ,  $an_mail ) ; 
                    $this->session->set_flashdata('success', true);
                    redirect('Admin/editProfil');
                } else {
                    $this->session->set_flashdata('error', true);
                    redirect('Admin/editProfil');
                }
            }
        } else {
            redirect('Admin/editProfil');
        }
    }


    public function reactive( ){
        $idAdmin = '' ; 
        if ( isset( $_POST['idAdmin']) && $_POST['idAdmin']){
            $idAdmin = trim( strip_tags( $_POST['idAdmin'])) ; 
        }

        $this->admin->reactive( $idAdmin ) ; 
        $this->session->set_flashdata('reactive' , true ) ; 

    }

    
}
