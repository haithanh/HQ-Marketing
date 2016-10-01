<?php

namespace Hqend\Models;

class Links extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'links';
    }

    public static function getFilter($data)
    {
        $where = "";
        $bind  = array();
        if (isset($data["partner"]) && !empty($data["partner"]))
        {
            $where .= "link_partners = :partner: AND ";
            $bind["partner"] = $data["partner"];
        }
        if (isset($data["campaign"]) && !empty($data["campaign"]))
        {
            $where .= "link_campaigns = :campaign: AND";
            $bind["campaign"] = $data["campaign"];
        }
        if (isset($data["channel"]) && !empty($data["channel"]))
        {
            $where .= "link_channels = :channel: AND ";
            $bind["channel"] = $data["channel"];
        }
        $where = substr($where, 0, strlen($where) - 4);
        return self::find(array(
                    $where,
                    "bind" => $bind
                ))->toArray();
    }

    public static function getFilterDate($data)
    {
        $where = "";
        $bind  = array();
        if (isset($data["partner"]) && !empty($data["partner"]))
        {
            $where .= "link_partners = :partner: AND ";
            $bind["partner"] = $data["partner"];
        }
        if (isset($data["campaign"]) && !empty($data["campaign"]))
        {
            $where .= "link_campaigns = :campaign: AND ";
            $bind["campaign"] = $data["campaign"];
        }
        if (isset($data["channel"]) && !empty($data["channel"]))
        {
            $where .= "link_channels = :channel: AND ";
            $bind["channel"] = $data["channel"];
        }
        if (isset($data["from_date"]) && !empty($data["from_date"]))
        {
            $where .= "DATE(link_created_date) >= :from_date: AND ";
            $bind["from_date"] = $data["from_date"];
        }
        if (isset($data["to_date"]) && !empty($data["to_date"]))
        {
            $where .= "DATE(link_created_date) <= :to_date: AND ";
            $bind["to_date"] = $data["to_date"];
        }
        $where = substr($where, 0, strlen($where) - 4);
        return self::find(array(
                    $where,
                    "bind" => $bind
                ))->toArray();
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
