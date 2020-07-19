<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institution extends CI_Controller
{
    public function index()
    {
        $this->load->model('InstitutionModel', 'institution');

        $this->institution->listPublic();
        
        $this->template->load('dashboards/index.html', 'contents/institution/index.html');
    }

}