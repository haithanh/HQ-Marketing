<?php

namespace Hqend\Controllers;

class LinkController extends ControllerBase {

    public function indexAction()
    {
        $this->tag->setTitle($this->language->__("Links History"));
        $this->view->current_page = $this->language->__("Links History");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datatables/datatables.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/select/select2.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/radio/switch.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/datepicker.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/picker.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/picker.date.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/effects.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/hightchart/highcharts.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/hightchart/dark-unica.js");
        $this->assets->collection("inline")->addJs($this->_js_link . "views/history-links.js");
        $this->view->partners     = \Hqend\Models\Partners::find()->toArray();
        $this->view->channels     = \Hqend\Models\Channels::find()->toArray();
        $this->view->campaigns    = \Hqend\Models\Campaigns::find()->toArray();
    }

    public function listAction()
    {
        $this->tag->setTitle($this->language->__("List Links"));
        $this->view->current_page = $this->language->__("List Links");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datatables/datatables.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/select/select2.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/radio/switch.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/datepicker.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/picker.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/picker.date.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/effects.min.js");
        $this->assets->collection("inline")->addJs($this->_js_link . "views/list-links.js");
        $this->view->partners     = \Hqend\Models\Partners::find()->toArray();
        $this->view->channels     = \Hqend\Models\Channels::find()->toArray();
        $this->view->campaigns    = \Hqend\Models\Campaigns::find()->toArray();
    }

    public function searchLinkAction()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $data_search = $this->request->getPost("data_search", 'trim', false);
        if (!isset($data_search["to_date"]) || empty($data_search["to_date"]))
        {
            $data_search["to_date"] = date("Y-m-d");
        }
        if (!isset($data_search["from_date"]) || empty($data_search["from_date"]))
        {
            $data_search["from_date"] = date("Y-m-d");
        }
        $date_ranges = \HqEngine\HqTool\HqUtil::getRangeDate($data_search["from_date"], $data_search["to_date"], "Y-m-d");
        if (count($date_ranges) > 31)
        {
            echo "alert('" . $this->language->__("Date Range Must < 31 days") . "');";
            exit();
        }
        $links = array();
        if (isset($data_search["link_id"]) && !empty($data_search["link_id"]) && is_numeric($data_search["link_id"]))
        {
            $link_detail = \Hqend\Models\Links::getLinkById($data_search["link_id"]);
            if (!empty($link_detail))
            {
                $link_detail = $link_detail->toArray();
                $links[] = $link_detail;
            }
        }
        if (empty($links))
        {
            $links = \Hqend\Models\Links::getFilter($data_search);
        }

        $new_list_link = array();
        $list_id       = "";
        foreach ($links as $link)
        {
            $list_id .=$link["link_id"] . ",";
            $new_list_link[$link["link_id"]] = $link;
        }
        $list_id    = substr($list_id, 0, strlen($list_id) - 1);
        $link_dates = \Hqend\Models\LinksDate::getFilter($data_search, $list_id, 0);
        $response   = array("data" => array());
        foreach ($link_dates as $key => $link)
        {
            $detail_url               = $this->url->get($this->module_config->main_route . "/link/link-detail?id=" . $link["link"]);
            $response["data"][$key][] = $link["link"];
            $response["data"][$key][] = $new_list_link[$link["link"]]["link_name"];
            $response["data"][$key][] = $link["link_click"];
            $response["data"][$key][] = $link["link_unique_click"];
            $response["data"][$key][] = $link["link_view"];
            $response["data"][$key][] = $link["link_register"];
            $response["data"][$key][] = round(($link["link_register"] / $link["link_click"]) * 100, 2) . "%";
            $response["data"][$key][] = round(($link["link_register"] / $link["link_unique_click"]) * 100, 2) . "%";
            $response["data"][$key][] = $response["data"][$key][] = '<button class="btn btn-info btn-icon btn-edit-content" onclick="editLink(' . $link["link"] . ')" type="button" data-popup="tooltip" data-original-title="Edit" data-rel="0">
                                            <i class="icon-pencil7"></i>
                                        </button>
                                        <a class="btn btn-warning btn-icon btn-edit-content" target="_blank" href="' . $detail_url . '" type="button" data-rel="0">
                                            <i class="icon-eye"></i>
                                        </a>';
        }

        echo json_encode($response);
        exit();
    }

    public function filterLinkAction()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $data_search = $this->request->getPost("data_search", 'trim', false);
        if (!isset($data_search["to_date"]) || empty($data_search["to_date"]))
        {
            $data_search["to_date"] = date("Y-m-d");
        }
        if (!isset($data_search["from_date"]) || empty($data_search["from_date"]))
        {
            $data_search["from_date"] = date("Y-m-d");
        }
        $date_ranges = \HqEngine\HqTool\HqUtil::getRangeDate($data_search["from_date"], $data_search["to_date"], "Y-m-d");
        if (count($date_ranges) > 31)
        {
            echo "alert('" . $this->language->__("Date Range Must < 31 days") . "');";
            exit();
        }
        $links = \Hqend\Models\Links::getFilterDate($data_search);

        $response = array("data" => array());
        foreach ($links as $key => $link)
        {
            $detail_url               = $this->url->get($this->module_config->main_route . "/link/link-detail?id=" . $link["link_id"]);
            $response["data"][$key][] = $link["link_id"];
            $response["data"][$key][] = $link["link_name"];
            $response["data"][$key][] = $link["link_url"];
            $response["data"][$key][] = $link["link_created_date"];
            $response["data"][$key][] = $response["data"][$key][] = '<button class="btn btn-info btn-icon btn-edit-content" onclick="editLink(' . $link["link_id"] . ')" type="button" data-popup="tooltip" data-original-title="Edit" data-rel="0">
                                            <i class="icon-pencil7"></i>
                                        </button>
                                        <a class="btn btn-warning btn-icon btn-edit-content" target="_blank" href="' . $detail_url . '" type="button" data-rel="0">
                                            <i class="icon-eye"></i>
                                        </a>';
        }

        echo json_encode($response);
        exit();
    }

    public function editLinkAction()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $id               = $this->request->getQuery("id", "trim", false);
        $this->view->data = array();
        if (!empty($id) && is_numeric($id))
        {
            $data_mem = \Hqend\Models\Links::getLinkById($id);
            if (!empty($data_mem))
            {
                $this->view->data = $data_mem->toArray();
            }
        }
        $this->view->partners  = \Hqend\Models\Partners::find()->toArray();
        $this->view->channels  = \Hqend\Models\Channels::find()->toArray();
        $this->view->campaigns = \Hqend\Models\Campaigns::find()->toArray();
    }

    public function linkDetailAction()
    {
        $id               = $this->request->getQuery("id", "trim", false);
        $this->view->data = array();
        if (!empty($id) && is_numeric($id))
        {
            $data_mem = \Hqend\Models\Links::getLinkById($id);
            if (!empty($data_mem))
            {
                $this->assets->collection("head")->addJs("public/js/lib/plugins/datatables/datatables.min.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/select/select2.min.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/radio/switch.min.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/datepicker.min.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/picker.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/datepicker/picker.date.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/effects.min.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/hightchart/highcharts.js");
                $this->assets->collection("head")->addJs("public/js/lib/plugins/hightchart/dark-unica.js");
                $this->assets->collection("inline")->addJs($this->_js_link . "views/link-detail.js");
                $data_mem = $data_mem->toArray();

                //Get Channel
                $this->view->channel      = \Hqend\Models\Channels::getChannelById($data_mem["link_channels"])->toArray();
                $this->view->campaign     = \Hqend\Models\Campaigns::getCampaignById($data_mem["link_campaigns"])->toArray();
                $this->view->partner      = \Hqend\Models\Partners::getPartnerById($data_mem["link_partners"])->toArray();
                $this->tag->setTitle($this->language->__($data_mem["link_name"]));
                $this->view->current_page = $this->language->__($data_mem["link_name"]);
                $this->view->data         = $data_mem;
                $this->view->key          = \HqEngine\HqTool\HqUtil::_encrypt($data_mem["link_id"], $data_mem["link_name"]);
            }
            else
            {
                return $this->response->redirect($this->module_config->main_route . "/link/list");
            }
        }
        else
        {
            return $this->response->redirect($this->module_config->main_route . "/link/list");
        }
    }

    public function saveLinkAction()
    {
        $data     = $this->request->getPost("data", null, false);
        $response = array("status" => 0, "message" => $this->language->__("Action Failed"));

        if (!empty($data))
        {
            if (!isset($data["link_redirect"]) || empty($data["link_redirect"]) || $data["link_redirect"] != 1)
            {
                $data["link_redirect"] = 0;
            }
            $mem_model = new \Hqend\Models\Links();
            if (isset($data["link_id"]))
            {
                $id    = $data["link_id"];
                unset($data["link_id"]);
                $check = $mem_model->updateLinkByID($data, $id);
            }
            else
            {
                $data["link_created_date"] = date("Y-m-d H:i:s");
                $check                     = $mem_model->save($data);
            }
            if ($check)
            {
                $response = array("status" => 1, "message" => $this->language->__("Link Updated"));
            }
            else
            {
                $response["message"] = implode(",", $mem_model->getMessages());
            }
        }
        echo json_encode($response);
        exit();
    }

//    public function deleteLinkAction()
//    {
//        $response = array(
//            "status"  => 0,
//            "message" => $this->language->__("Action Failed")
//        );
//        $id       = $this->request->getPost("id", "trim", false);
//        if (!empty($id))
//        {
//            $member_vip_obj = new \Hqend\Models\Links();
//            if ($member_vip_obj->deleteLinksByID($id))
//            {
//                $response = array(
//                    "status"  => 1,
//                    "message" => $this->language->__("Delete Link Success")
//                );
//            }
//            else
//            {
//                $response = array(
//                    "status"  => 0,
//                    "message" => implode(",", $member_vip_obj->getMessages())
//                );
//            }
//        }
//        echo json_encode($response);
//        exit();
//    }
}
