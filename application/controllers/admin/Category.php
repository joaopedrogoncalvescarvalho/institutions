<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller 
{
    public function index()
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);

        $this->load->model('CategoryModel', 'category');
        
        if($this->category->check_form($this->input->post()))
        {
            $this->category->save($this->input->post());

            header('Location: /admin/category');
            exit;
        }
        else
        {
            $this->admin->findByPk($this->session->{$this->admin::SESSION});

            $this->category->listAll();

            $this->template->load('dashboards/admin.html', 'contents/admin/category/index.html', array(
                'title_page' => 'AdminLTE3 - Intituições - Cetegorias',
                'js' => ['/protected/contents/admin/category/index.js']
            ));
        }
    }

    public function delete()
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);

        $this->load->model('CategoryModel', 'category');

        $id = $_POST['idCategory'];

        $this->category->delete($id);
        
        return true;
    }

}