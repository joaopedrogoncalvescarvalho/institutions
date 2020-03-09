<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class AddressModel extends Model
{
    CONST TABLE_NAME = 'tb_address';
    CONST RELATIONS = [
        'city' => [
            'tb_name' => 'tb_cities', 
            'key' => 'id',
            'model' => 'CityModel', 
            'attribute' => 'id_city',
            'type' => 'N_1'
        ],
        'institution' => [
            'tb_name' => 'tb_intitutions', 
            'key' => 'id_address',
            'model' => 'InstitutionModel', 
            'attribute' => 'id',
            'type' => '1_1'
        ]
    ];

    public $id;
    public $id_city;
    public $address;

    public function load($model)
    {
        $this->id = $model->id;
        $this->id_city = $model->id_city;
        $this->address = $model->address;
    }
}