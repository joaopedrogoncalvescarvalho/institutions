<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{
	public function index()
	{
		$this->load->model('AdminModel', 'admin');

		$this->admin->has_session(true);

		$this->admin->findByPk($this->session->{$this->admin::SESSION});

		$this->template->load('dashboards/admin.html', 'contents/admin/admin/index.html', array(
			'title_page' => 'AdminLTE3 - Intituições'
		));
	}

	public function login()
	{
		$this->load->model('AdminModel', 'admin');

		$this->admin->has_session(true, 'login');

		if(!$this->admin->check_form($this->input->post()))
		{
			$this->load->view('dashboards/login.html');
		}
		else
		{
			if(!$this->admin->login($this->input->post()))
			{
				$this->load->view('dashboards/login.html');
				$this->load->view('alerts/toastr.html', array(
					'message' => 'ACESSO NEGADO! :\'('
				));
			}
			else
			{
				header('Location: /admin/admin');
				exit;
			}
		}
	}

	public function closeSession()
	{
		$this->load->model('AdminModel', 'admin');

		$this->admin->has_session(true);

		$this->admin->closeSession();

		header('Location: /admin/admin/login');
		exit;
	}
}
