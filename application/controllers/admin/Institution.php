<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institution extends CI_Controller 
{
    public function index()
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);

        $this->load->model('InstitutionModel', 'institution');

        if($this->institution->check_form($this->input->post()))
        {
            $this->institution->save($this->input->post());

            header('Location: /admin/institution');
            exit;
        }
        else
        {
            $this->admin->findByPk($this->session->{$this->admin::SESSION});

            $this->load->model('CategoryModel', 'category');
            $this->category->listAll();

            $this->load->model('CityModel', 'city');
            $this->city->listAll();

            $this->load->model('StateModel', 'state');
            $this->state->listAll();

            $this->load->model('NatureModel', 'nature');
            $this->nature->listAll();

            $this->institution->listEndRelations();

            $this->template->load('dashboards/admin.html', 'contents/admin/institution/index.html', array(
                'css' => ['/protected/adminLTE/packs/toastr/build/toastr.css'],
                'js' => [
                    '/protected/adminLTE/packs/toastr/toastr.js', 
                    '/protected/contents/admin/institution/index.js'
                ]
            ));
        }
    }

    public function delete()
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);
        
        $this->load->model('InstitutionModel', 'intitution');

        $idInstitution = $this->input->post('idInstitution');
        
        $this->intitution->delete($idInstitution);
    }

    public function loadFull($id)
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);

        $data = $this->sql->select('
            SELECT
                I.id,
                I.indice,
                I.name,
                I.id_category,
                I.name_of_responsible_of_institution,
                I.name_of_responsible_of_social_area,
                I.phone,
                I.email,
                I.coverage_area,
                I.id_nature,
                I.id_address,
                A.address,
                S.id AS id_state,
                CI.id AS id_city
            FROM tb_institutions I
                JOIN tb_address A ON A.id = I.id_address
                JOIN tb_cities CI ON CI.id = A.id_city
                JOIN tb_states S ON S.id = CI.id_state
            WHERE
                I.id = :id
            GROUP BY I.id
        ', array(
            ':id' => $id
        ));

        echo json_encode($data);
    }

    public function updateCategory()
    {
        $this->load->model('AdminModel', 'admin');

        $this->admin->has_session(true);

        $data = $this->input->post();

        $this->load->model('InstitutionModel', 'institution');

        $this->institution->updateCategory($data);
    }

}