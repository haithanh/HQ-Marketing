<?php

namespace Hqend\Models;

class Channels extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'channels';
    }

    public static function getChannelByUsername($username)
    {
        return self::findFirst(array(
                    "channel_name = :username:",
                    "bind" => array(
                        "username" => $username
                    )
        ));
    }

    public static function getChannelById($id)
    {
        return self::findFirst(array(
                    "channel_id = :id:",
                    "bind" => array(
                        "id" => $id
                    )
        ));
    }

    public function updateChannelByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "channel_id=" . $connection->escapeString($id));
    }

}
