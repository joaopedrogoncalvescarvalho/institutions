<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class StateModel extends Model
{
    CONST TABLE_NAME = 'tb_states';
    CONST RELATIONS = [
        'city' => [
            'tb_name' => 'tb_cities', 
            'key' => 'id_state',
            'attribute' => 'id',
            'model' => 'CityModel', 
            'type' => '1_N'
        ]
    ];

    public $id;
    public $name;
    public $uf;
    
}