<?php

namespace Hqend\Models;

class Member extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'member';
    }

    public static function getFilter($key)
    {
        return self::find(array(
                    "mem_public_id = :key: OR mem_acc = :key: OR mem_email= :key: ",
                    "bind" => array(
                        "key" => $key
                    )
                ))->toArray();
    }

    public static function getMemberById($id)
    {
        return self::findFirst(array(
                    "mem_id = :id:",
                    "bind" => array(
                        "id" => $id
                    )
        ));
    }

    public function updateMemberByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "mem_id=" . $connection->escapeString($id));
    }

}
