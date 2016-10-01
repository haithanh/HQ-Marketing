<?php

namespace Mkt\Controllers;

class LinkController extends ControllerBase {

    public function indexAction()
    {
        $link = $this->request->getQuery("a", null, false);
        if (!empty($link))
        {
            $links = explode(".", $link);

            if (count($links) == 2)
            {
                $data_base = $links[0];
                $link_id   = base64_decode($data_base);
                if (!empty($link_id) && is_numeric($link_id))
                {
                    $cache_link = $this->getCacheLink($link_id);
                    $link_id    = \HqEngine\HqTool\HqUtil::_decrypt($link, $cache_link[$link_id]["link_name"]);
                    if ($link_id && is_numeric($link_id))
                    {
                        $date             = date("Y-m-d");
                        $time             = time();
                        $ip               = $_SERVER['REMOTE_ADDR'];
                        $cache_link_dates = $this->getCacheLinkDate($link_id);
                        $ld_ip            = $cache_link_dates[$link_id][$date]["ld_ip"];
                        $link_date        = new \Mkt\Models\LinksDate();
                        $data_update      = array();
                        if (isset($ld_ip[$ip]))
                        {
                            if (($time - strtotime($ld_ip[$ip])) > 30)
                            {
                                $cache_link_dates[$link_id][$date]["ld_link_click"] += 1;
                                $data_update                                     = array(
                                    "ld_link_click" => $cache_link_dates[$link_id][$date]["ld_link_click"]
                                );
                                $cache_link_dates[$link_id][$date]["ld_ip"][$ip] = date("Y-m-d H:i:s");
                            }
                        }
                        else
                        {
                            $cache_link_dates[$link_id][$date]["ld_link_click"] += 1;
                            $cache_link_dates[$link_id][$date]["ld_unique_click"] += 1;
                            $data_update                                     = array(
                                "ld_link_click"   => $cache_link_dates[$link_id][$date]["ld_link_click"],
                                "ld_unique_click" => $cache_link_dates[$link_id][$date]["ld_unique_click"]
                            );
                            $cache_link_dates[$link_id][$date]["ld_ip"][$ip] = date("Y-m-d H:i:s");
                        }
                        $data_update["ld_ip"] = json_encode($cache_link_dates[$link_id][$date]["ld_ip"]);
                        $link_date->updateLinkDateByID($data_update, $cache_link_dates[$link_id][$date]["ld_id"]);
                        $this->cacheData->save("links", $cache_link_dates);
                        return $this->response->redirect($cache_link_dates[$link_id]["link_url"] . "?a=" . $link, true);
                        exit();
                    }
                    $this->_echo_response("Không tìm thấy link 4");
                }
                $this->_echo_response("Không tìm thấy link 3");
            }
            $this->_echo_response("Không tìm thấy link 2");
        }
        $this->_echo_response("Không tìm thấy link 1");
    }

    private function getCacheLink($link_id, $ajax = false)
    {
        $cache_link = $this->cacheData->get("links");
        if (!isset($cache_link[$link_id]))
        {
            $link_detail = \Mkt\Models\Links::getLinkById($link_id);
            if (!empty($link_detail))
            {
                $cache_link[$link_id] = $link_detail->toArray();
                $this->cacheData->save("links", $cache_link);
            }
            else
            {
                if (!$ajax)
                {
                    $this->_echo_response("Không tìm thấy link 3");
                }
                else
                {
                    $this->_response("Không tìm thấy link 3");
                }
            }
        }
        return $cache_link;
    }

    private function getCacheLinkDate($link_id)
    {
        $cache_link = $this->cacheData->get("links");
        $date       = date("Y-m-d");
        if (!isset($cache_link[$link_id][$date]))
        {
            $link_date_detail = \Mkt\Models\LinksDate::getByLinkIDAndDate($link_id, $date);
            if (!empty($link_date_detail))
            {
                $cache_link[$link_id][$date]          = $link_date_detail->toArray();
                $cache_link[$link_id][$date]["ld_ip"] = json_decode($cache_link[$link_id][$date]["ld_ip"], true);
            }
            else
            {
                $link_date_obj               = new \Mkt\Models\LinksDate();
                $array_save                  = array(
                    "ld_link"          => $link_id,
                    "ld_ip"            => json_encode(array()),
                    "ld_link_click"    => 0,
                    "ld_unique_click"  => 0,
                    "ld_link_view"     => 0,
                    "ld_link_register" => 0,
                    "ld_link_revenue"  => 0,
                    "ld_date"          => $date,
                );
                $link_date_obj->save($array_save);
                $array_save["ld_id"]         = $link_date_obj->ld_id;
                $array_save["ld_ip"]         = array();
                $cache_link[$link_id][$date] = $array_save;
            }
            $this->cacheData->save("links", $cache_link);
        }
        return $cache_link;
    }

    private function _echo_response($message)
    {
        echo $message;
        exit();
    }

    public function registerAction()
    {
        $data              = $this->request->getQuery();
        $channel           = $this->request->getQuery("channel", null, false);
        $link_id           = $this->request->getQuery("link", null, false);
        $channel_class_url = "\\HqLibrary\\Channel\\" . ucfirst($channel);
        if (class_exists($channel_class_url))
        {
            $channel_class = new $channel_class_url();
            if ($channel_class->checkKey($data))
            {
                $link_id = base64_decode($link_id);
                if (is_numeric($link_id))
                {
                    $date            = date("Y-m-d");
                    $cache_link      = $this->getCacheLink($link_id, true);
                    $cache_link_date = $this->getCacheLinkDate($link_id);
                    $cache_link_date[$link_id][$date]["ld_link_register"]+=1;
                    if ($cache_link_date[$link_id][$date]["ld_link_register"] <= $cache_link_date[$link_id][$date]["ld_link_click"])
                    {
                        $link_date   = new \Mkt\Models\LinksDate();
                        $data_update = array(
                            "ld_link_register" => $cache_link_date[$link_id][$date]["ld_link_register"]
                        );
                        if ($link_date->updateLinkDateByID($data_update, $cache_link_date[$link_id][$date]["ld_id"]))
                        {
                            $this->cacheData->save("links", $cache_link_date);
                            $this->_response("Thành Công", 1);
                        }
                        else
                        {
                            $this->_response(implode(",", $link_date->getMessages()));
                        }
                    }
                    $this->_response("Register không đúng");
                }
                $this->_response("Link không đúng");
            }
            $this->_response("Key không đúng");
        }
        $this->_response("Không tồn tại Channel");
    }

}
