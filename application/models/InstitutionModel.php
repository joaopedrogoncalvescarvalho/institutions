<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class InstitutionModel extends Model
{
    CONST TABLE_NAME = 'tb_institutions';
    
    const FORM_VALIDATION = array(
        ['name', 'name', ['required', 'min_length[10]', 'max_length[200]', 'regex_match[/[a-zA-Z\u00C0-\u00FF ]+/i]/'], [
            'required' => 'Campo obrigátorio',
            'minlength' => 'Campo deve conter no mínino 10 caracteres',
            'maxlength' => 'Campo deve conter no mínino 200 caracteres',
            'regex_match' => 'Evite caracteres especiais'
        ]],
        ['name_of_responsible_of_institution', 'name_of_responsible_of_institution', ['required', 'min_length[6]', 'max_length[100]', 'regex_match[/[a-zA-Z\u00C0-\u00FF ]+/i]/'], [
            'required' => 'Campo obrigátorio',
            'minlength' => 'Campo deve conter no mínino 6 caracteres',
            'maxlength' => 'Campo deve conter no máximo 100 caracteres',
            'regex_match' => 'Evite caracteres especiais'
        ]],
        ['name_of_responsible_of_social_area', 'name_of_responsible_of_social_area', ['required', 'min_length[6]', 'max_length[200]', 'regex_match[/[a-zA-Z\u00C0-\u00FF ]+/i]/'], [
            'required' => 'Campo obrigátorio',
            'minlength' => 'Campo deve conter no mínino 6 caracteres',
            'maxlength' => 'Campo deve conter no máximo 200 caracteres',
            'regex_match' => 'Evite caracteres especiais'
        ]],
        ['phone', 'phone', ['required'], []],
        ['email', 'email', ['required', 'valid_email'], [
            'required' => 'Campo obrigátotio',
            'valid_email' => 'Endereço de e-mail inválido'
        ]],
        ['address', 'address', ['required', 'min_length[10]', 'max_length[200]'], [
            'required' => 'Campo obrigátorio',
            'minlength' => 'Campo deve conter no mínino 10 caracteres',
            'maxlength' => 'Campo deve conter no máximo 200 caracteres',
        ]],
        ['coverage_area', 'coverage_area', ['required'], [
            'required' => 'Campo obrigátorio'
        ]],
        ['id_nature', 'id_nature', ['required'], [
            'required' => 'Campo obrigátorio'
        ]]
    ); 

    public $id;
    public $indice;
    public $name;
    public $id_nature;
    public $name_of_responsible_of_institution;
    public $name_of_responsible_of_social_area;
    public $id_address;
    public $phone;
    public $email;
    public $coverage_area;
    public $id_category;
    public $inclusion;

    public function save($data)
    {
        if(empty($data['id']))
        {
            $result = $this->sql->query(
                'INSERT INTO tb_address
                    (address, id_city)
                VALUES
                    (:address, :id_city)', array(
                ':address' => $data['address'],
                ':id_city' => $data['id_city']
            ));

            $result = $this->sql->query('
                INSERT INTO tb_institutions
                    (indice, name, id_nature, name_of_responsible_of_institution, name_of_responsible_of_social_area, id_address, phone, email, coverage_area, id_category) 
                VALUES 
                    (:indice, :name, :id_nature, :name_of_responsible_of_institution, :name_of_responsible_of_social_area, :id_address, :phone, :email, :coverage_area, :id_category)', array(
                ':indice' => !empty($data['indice']) ? $data['indice'] : null, 
                ':name' => $data['name'], 
                ':id_nature' => $data['id_nature'], 
                ':name_of_responsible_of_institution' => $data['name_of_responsible_of_institution'], 
                ':name_of_responsible_of_social_area' => $data['name_of_responsible_of_social_area'], 
                ':id_address' => $result['status']->lastInsertId(),
                ':phone' => $data['phone'], 
                ':email' => $data['email'], 
                ':coverage_area' => $data['coverage_area'], 
                ':id_category' => $data['id_category']
            ));
        }
        else
        {
            $this->sql->query('
                UPDATE tb_institutions I 
                JOIN tb_address A ON A.id = I.id_address 
                SET 
                    I.indice = :indice, 
                    I.name = :name, 
                    I.id_nature = :id_nature, 
                    I.name_of_responsible_of_institution = :name_of_responsible_of_institution, 
                    I.name_of_responsible_of_social_area = :name_of_responsible_of_social_area, 
                    I.phone = :phone, 
                    I.email = :email, 
                    I.coverage_area = :coverage_area, 
                    A.address = :address, 
                    A.id_city = :id_city 
                WHERE I.id = :id', array(
                    ':indice' => !empty($data['indice']) ? $data['indice'] : null, 
                    ':name' => $data['name'], 
                    ':id_nature' => $data['id_nature'], 
                    ':name_of_responsible_of_institution' => $data['name_of_responsible_of_institution'], 
                    ':name_of_responsible_of_social_area' => $data['name_of_responsible_of_social_area'], 
                    ':phone' => $data['phone'], 
                    ':email' => $data['email'], 
                    ':coverage_area' => $data['coverage_area'], 
                    ':address' => $data['address'],
                    ':id_city' => $data['id_city'],
                    ':id'=> $data['id']
            ));
        }
    }

    public function listEndRelations()
    {
        $this->list = $this->sql->select('
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
            GROUP BY I.id
            ORDER BY I.indice, I.name, I.id 
        ', []);
    }

    public function delete($idInstitution)
    {
        $this->sql->query('DELETE FROM tb_institutions WHERE id = :id', [':id' => $idInstitution]);
    }

    public function updateCategory($data)
    {
        $this->sql->query('UPDATE tb_institutions SET id_category = :idCategory WHERE id = :idInstitution', array(
            ':idCategory' => $data['idCategory'],
            ':idInstitution' => $data['idInstitution']
        ));
    }

    public function listPublic()
    {
        $this->setData($this->sql->select("
            SELECT
                I.indice,
                I.name,
                C.name AS category,
                I.name_of_responsible_of_institution,
                I.name_of_responsible_of_social_area,
                I.phone,
                I.email,
                I.coverage_area,
                A.address,
                S.uf,
                CI.name AS city,
                N.name AS nature
            FROM tb_institutions I
                JOIN tb_address A ON A.id = I.id_address
                JOIN tb_cities CI ON CI.id = A.id_city
                JOIN tb_states S ON S.id = CI.id_state
                JOIN tb_natures N ON N.id = I.id_nature 
                JOIN tb_categories C ON C.Id = I.id_category 
            WHERE 
                I.indice LIKE :indice AND 
                I.name LIKE :name AND 
                I.name_of_responsible_of_institution LIKE :name_of_responsible_of_institution AND 
                I.name_of_responsible_of_social_area LIKE :name_of_responsible_of_social_area AND 
                I.email LIKE :email AND 
                A.address LIKE :address AND 
                I.phone LIKE :phone AND
                I.coverage_area LIKE :coverage_area AND
                S.uf LIKE :uf AND 
                CI.name LIKE :city AND 
                N.name LIKE :nature AND 
                C.name LIKE :category
            GROUP BY I.id 
            ORDER BY I.indice, I.name, I.id
            LIMIT 10
            OFFSET :offset", [
            ':offset' => isset($_GET['page']) ? ((int)$_GET['page'] - 1)*10 : 0,
            ':indice' => '%' . (isset($_GET['indice']) ? $_GET['indice'] : "") . '%',
            ':name' => '%' . (isset($_GET['name']) ? $_GET['name'] : "") . '%',
            ':name_of_responsible_of_institution' => '%' . (isset($_GET['name_of_responsible_of_institution']) ? $_GET['name_of_responsible_of_institution'] : "") . '%',
            ':name_of_responsible_of_social_area' => '%' . (isset($_GET['name_of_responsible_of_social_area']) ? $_GET['name_of_responsible_of_social_area'] : "") . '%',
            ':email' => '%' . (isset($_GET['email']) ? $_GET['email'] : "") . '%',
            ':address' => '%' . (isset($_GET['address']) ? $_GET['address'] : "") . '%',
            ':phone' => '%' . (isset($_GET['phone']) ? $_GET['phone'] : "") . '%',
            ':coverage_area' => '%' . (isset($_GET['coverage_area']) ? $_GET['coverage_area'] : "") . '%',
            ':uf' => '%' . (isset($_GET['uf']) ? $_GET['uf'] : "") . '%',
            ':city' => '%' . (isset($_GET['city']) ? $_GET['city'] : "") . '%',
            ':nature' => '%' . (isset($_GET['nature']) ? $_GET['nature'] : "") . '%',
            ':category' => '%' . (isset($_GET['category']) ? $_GET['category'] : "") . '%'
        ]));
    }
}