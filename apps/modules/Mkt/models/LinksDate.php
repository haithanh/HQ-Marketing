<?php

namespace Mkt\Models;

class LinksDate extends \HqEngine\HqModel\HqBaseModel {

    public $ld_id;

    public function getSource()
    {
        return $this->_prefix_table . 'links_date';
    }

    public static function getByLinkIDAndDate($id, $date)
    {
        return self::findFirst(array(
                    "ld_link = :id: AND ld_date = :date:",
                    "bind" => array(
                        "id"   => $id,
                        "date" => $date
                    )
        ));
    }

    public function updateLinkDateByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "ld_id=" . $connection->escapeString($id));
    }

}
