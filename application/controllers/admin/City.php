<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class City extends CI_Controller 
{
    public function loadByState()
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);
        
        $idState = $_GET['idState'];

        $data = $this->sql->select('SELECT * FROM tb_cities WHERE id_state = :id_state', [':id_state' => $idState]);

        echo json_encode($data);
    }

}