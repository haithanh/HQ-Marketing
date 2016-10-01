<?php

namespace Hqend\Models;

class MemberVip extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'member_vip';
    }

    public static function getMemberVipByUsername($username)
    {
        return self::findFirst(array(
                    "mv_name = :username:",
                    "bind" => array(
                        "username" => $username
                    )
        ));
    }

    public static function getMemberVipById($id)
    {
        return self::findFirst(array(
                    "mv_id = :id:",
                    "bind" => array(
                        "id" => $id
                    )
        ));
    }

    public function updateMemberVipByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "mv_id=" . $connection->escapeString($id));
    }

    public function deleteMemberVipByID($id)
    {
        $connection = $this->getWriteConnection();
        return $connection->delete($this->getSource(), "mv_id=" . $connection->escapeString($id));
    }

}
