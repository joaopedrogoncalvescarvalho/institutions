<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class InstitutionModel extends Model
{
    CONST TABLE_NAME = 'tb_institutions';
    CONST RELATIONS = array(
        'category' => [
            'tb_name' => 'tb_categories', 
            'key' => 'id',
            'attribute' => 'id_category',
            'model' => 'CategoryModel', 
            'type' => 'N_1'
        ],
        'nature' => [
            'tb_name' => 'tb_natures', 
            'key' => 'id',
            'attribute' => 'id_nature',
            'model' => 'NatureModel', 
            'type' => '1_1'
        ], 
        'address' => [
            'tb_name' => 'tb_address', 
            'key' => 'id',
            'attribute' => 'id_address',
            'model' => 'AddressModel', 
            'type' => '1_1'
        ], 
    );
    const FORM_VALIDATION = array(); 

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
            $executation = $this->sql->query(
                'INSERT INTO tb_address
                    (address, id_city)
                VALUES
                    (:address, :id_city)', array(
                ':address' => $data['address'],
                ':id_city' => $data['id_city']
            ));

            $this->sql->query('
                INSERT INTO tb_institutions
                    (indice, name, id_nature, name_of_responsible_of_institution, name_of_responsible_of_social_area, id_address, phone, email, coverage_area, id_category) 
                VALUES 
                    (:indice, :name, :id_nature, :name_of_responsible_of_institution, :name_of_responsible_of_social_area, :id_address, :phone, :email, :coverage_area, :id_category)', array(
                ':indice' => !empty($data['indice']) ? $data['indice'] : null, 
                ':name' => $data['name'], 
                ':id_nature' => $data['id_nature'], 
                ':name_of_responsible_of_institution' => $data['name_of_responsible_of_institution'], 
                ':name_of_responsible_of_social_area' => $data['name_of_responsible_of_social_area'], 
                ':id_address' => $executation['status']->lastInsertId(),
                ':phone' => $data['phone'], 
                ':email' => $data['email'], 
                ':coverage_area' => $data['coverage_area'], 
                ':id_category' => $data['id_category']
            ));
        }
        else
        {
            //var_dump($data['id_nature']); exit;
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
        ', []);
    }
}