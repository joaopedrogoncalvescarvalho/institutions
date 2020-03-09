<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class City extends CI_Controller 
{
    public function loadByState($id_state)
    {
        $data = $this->sql->select('SELECT * FROM tb_cities WHERE id_state = :id_state', [':id_state' => $id_state]);

        echo json_encode($data);
    }

}