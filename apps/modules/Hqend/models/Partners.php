<?php

namespace Hqend\Models;

class Partners extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'partners';
    }

    public static function getPartnerByUsername($username)
    {
        return self::findFirst(array(
                    "pa_name = :username:",
                    "bind" => array(
                        "username" => $username
                    )
        ));
    }

    public static function getPartnerById($id)
    {
        return self::findFirst(array(
                    "pa_id = :id:",
                    "bind" => array(
                        "id" => $id
                    )
        ));
    }

    public function updatePartnerByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "pa_id=" . $connection->escapeString($id));
    }

}
