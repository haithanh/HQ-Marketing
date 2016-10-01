<?php

namespace Mkt\Models;

class Links extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'links';
    }

    public static function getLinkById($id)
    {
        return self::findFirst(array(
                    "link_id = :id:",
                    "bind" => array(
                        "id" => $id
                    )
        ));
    }

    public function updateLinkByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "link_id=" . $connection->escapeString($id));
    }

}
