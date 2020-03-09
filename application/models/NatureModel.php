<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class NatureModel extends Model
{
    CONST TABLE_NAME = 'tb_natures';
    CONST RELATIONS = [
        'institution' => [
            'tb_name' => 'tb_institutions', 
            'key' => 'id_nature',
            'attribute' => 'id',
            'model' => 'InstitutionModel', 
            'type' => '1_1'
        ]
    ];

    public $id;
    public $name;
}