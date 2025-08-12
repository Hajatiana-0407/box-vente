<?php 
class Notification {
    protected $ci;

    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->library('session');
    }

    public function set_notification($message) {
        $this->ci->session->set_flashdata('notification', $message);
    }

    public function display_notification() {
        if ($this->ci->session->flashdata('notification')) {
            echo '<script>Myalert.added();</script>';
        }
    }
}