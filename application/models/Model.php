<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model
{
	public $list = array();

	public function setData($data = array())
	{
		if(count($data) > 1)
		{
			foreach($data as $i => $row)
			{
				$object = new $this;

				unset($object->list);

				foreach ($row as $key => $value)
				{		
					$object->{$key} = $value;
				}
				$this->list[$i] = $object;
			}
		}
		else
		{
			foreach($data as $i => $row)
			{
				$object = new $this;

				unset($object->list);

				foreach ($row as $key => $value)
				{		
					$this->{$key} = $value;

					$object->{$key} = $value;
				}

				$this->list[$i] = $object;
			}
		}
	}

	public function listAll()
	{
		$this->setData($this->sql->select('SELECT * FROM ' . $this::TABLE_NAME . ' ORDER BY id'));
	}

	public function findByPk($id, $typeResult = 'object')
	{
		if($typeResult === 'object')
			$this->setData($this->sql->select('SELECT * FROM ' . $this::TABLE_NAME . ' WHERE id = :id', [':id' => $id]));
		else if($typeResult === 'array')
			return $this->sql->select('SELECT * FROM ' . $this::TABLE_NAME . ' WHERE id = :id', [':id' => $id]);
		else
			throw new Exception('Select type invalid!');
	}

	public function check_form($data)
	{
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_data($data);

		if(empty($data))
			return false;

		if(empty($this::FORM_VALIDATION))
			return true;

		foreach(array_keys($data) as $key)
		{
			foreach($this::FORM_VALIDATION as $field)
			{
				if($field[0] == $key)
					$this->form_validation->set_rules($field[0], $field[1], $field[2], $field[3]);
			}
		}

		$this->form_validation->set_error_delimiters('
			<div class="alert alert-warning alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fas fa-exclamation-triangle"></i>', '</div>');

		return $this->form_validation->run();
	}

	public function has_session($redirect = false, $page = '!login')
	{
		if(!$redirect)
		{
			if($_SESSION[$this::SESSION] != null && $_SESSION[$this::SESSION] > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if($page == '!login')
			{
				if($_SESSION[$this::SESSION] == null || $_SESSION[$this::SESSION] == 0 || !isset($_SESSION[$this::SESSION]))
				{
					header('Location: /admin/admin/login');
					exit;
				}
			}
			else
			{
				if(isset($_SESSION[$this::SESSION]) && $_SESSION[$this::SESSION] != null && $_SESSION[$this::SESSION] > 0)
				{
					header('Location: /admin/admin');
					exit;
				}
			}
		}
	}
}