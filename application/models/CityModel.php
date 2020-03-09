<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class CityModel extends Model
{
    CONST TABLE_NAME = 'tb_cities';
    CONST RELATIONS = [
        'state' => [
            'tb_name' => 'tb_states', 
            'key' => 'id',
            'model' => 'StateModel', 
            'type' => 'N_1'
        ],
        'address' => [
            'tb_name' => 'tb_address', 
            'key' => 'id_city',
            'model' => 'CityModel', 
            'type' => '1_N'
        ]
    ];

    public $id;
    public $name;
    public $id_state;
}