<?php

namespace Hqend\Models;

class LinksDate extends \HqEngine\HqModel\HqBaseModel {

    public function getSource()
    {
        return $this->_prefix_table . 'links_date';
    }

    public static function getFilter($data, $list_id, $group_time = 1)
    {
        $bind  = array();
        $where = "DATE(ld_date) <= :to_date: AND DATE(ld_date) >= :from_date: AND ld_link IN (" . $list_id . ")";
        if (isset($data["to_date"]))
        {
            $bind["to_date"] = $data["to_date"];
        }
        else
        {
            $bind["to_date"] = date("Y-m-d");
        }
        if (isset($data["from_date"]))
        {
            $bind["from_date"] = $data["from_date"];
        }
        else
        {
            $bind["from_date"] = date("Y-m-d");
        }
        if ($group_time)
        {
            $group_time = "time";
        }
        else
        {
            $group_time = "link";
        }
        return self::find(array(
                    $where,
                    "bind"    => $bind,
                    "columns" => array(
                        "link"              => "ld_link",
                        "link_click"        => "SUM(ld_link_click)",
                        "link_unique_click" => "SUM(ld_unique_click)",
                        "link_view"         => "SUM(ld_link_view)",
                        "link_register"     => "SUM(ld_link_register)",
                        "link_revenue"      => "SUM(ld_link_revenue)/1000",
                        "time"              => "DATE(ld_date)",
                    ),
                    "group"   => array($group_time),
                    "order"   => array("time")
                ))->toArray();
    }

    public function updateChannelByID($data, $id)
    {
        $fields     = array_keys($data);
        $values     = array_values($data);
        $connection = $this->getWriteConnection();
        return $connection->update($this->getSource(), $fields, $values, "channel_id=" . $connection->escapeString($id));
    }

}
