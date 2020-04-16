<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class AdminModel extends Model
{
    CONST TABLE_NAME = 'tb_admins';
    CONST RELATIONS = [];
    CONST SESSION = 's3s5i0n4d1M1n';

    CONST FORM_VALIDATION = array(
        0 => ['name', 'name', ['required', 'minlength[10]', 'max_length[100]', 'regex_match[/[a-zA-Z\u00C0-\u00FF ]+/i]/'], null],
        1 => ['nickname', 'nickname', ['required', 'min_length[5]', 'max_length[20]', 'regex_match[/[A-Za-z0-9]{5,20}$/]'], 
            [
                'regex_match' => 'Use apenas letras e números'
            ]
        ],
        2 => ['password', 'password', ['required', 'min_length[6]', 'max_length[20]', 'regex_match[/[A-Za-z0-9]{6,20}$/]'], 
            [
                'regex_match' => 'Use apenas letras e números'
            ]
        ]
    ); 

    public $id;
    public $name;
    public $nickname;
    public $password;
    public $inclusion;

    public function login($data)
    {
        $this->setData($this->sql->select('SELECT id, password FROM ' . $this::TABLE_NAME . ' WHERE nickname = :nickname', array(
                ':nickname' => $data['nickname']
            ))
        );

        if($this->id !== null && $this->id > 0)
        {
            if(password_verify($data['password'], $this->password))
            {
                $this->session->set_userdata([$this::SESSION => $this->id]);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function closeSession()
    {
        $this->session->set_userdata([$this::SESSION => null]);
    }

}