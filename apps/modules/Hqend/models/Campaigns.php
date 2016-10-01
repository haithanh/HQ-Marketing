<?php

namespace Hqend\Models;

class Campaigns extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'campaigns';
    }

    public static function getCampaignByUsername($username)
    {
        return self::findFirst(array(
                    "cam_name = :username:",
                    "bind" => array(
                        "username" => $username
                    )
        ));
    }

    public static function getCampaignById($id)
    {
        return self::findFirst(array(
                    "cam_id = :id:",
                    "bind" => array(
                        "id" => $id
                    )
        ));
    }

    public function updateCampaignByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "cam_id=" . $connection->escapeString($id));
    }

}
