<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Model.php');

class CategoryModel extends Model
{
    CONST TABLE_NAME = 'tb_categories';
    CONST RELATIONS = [
        'institution' => [
            'tb_name' => 'tb_institutions', 
            'key' => 'id_category',
            'model' => 'InstitutionModel', 
            'type' => '1_N'
        ]
    ];
    CONST FORM_VALIDATION = [];

    public $id;
    public $name;
    public $parent;

    private $lineage = [];

    public function findTop()
    {
        return $this->sql->select('SELECT * FROM tb_categories WHERE parent is null ORDER BY id');
    }

    public function save($data)
    {
        if(empty($data['id']))
        {
            $this->sql->query('INSERT INTO tb_categories(indice, name, parent) VALUES (:indice, :name, :parent);', array(
                ':indice' => $data['indice'],
                ':name' => $data['name'],
                ':parent' => empty((int)$data['parent']) ? null : (int)$data['parent']
            ));
        }
        else
        {
            $this->sql->query('UPDATE tb_categories SET indice = :indice, name = :name WHERE id = :id;', array(
                ':indice' => $data['indice'],
                ':name' => $data['name'],
                ':id' => $data['id']
            ));
        }
    }

    public function delete($id)
    {
        $this->listAll();

        $this->setLineage($id);

        $this->sql->query('DELETE FROM tb_categories WHERE id IN (' . implode(',', $this->lineage) . ')', []);
    }

    private function setLineage($id)
    {
        $this->lineage[] = $id;

        foreach($this->list as $item)
        {
            if($item->parent == $id)
                $this->setLineage($item->id);
        }
    }

    public function hasLineage($id)
    {
        foreach($this->list as $item)
        {
            if($item->parent == $id)
                return true;
        }
        return false;
    }

}