<?php

namespace Hqend\Controllers;

class PartnerController extends ControllerBase {

    public function indexAction()
    {
        $this->tag->setTitle($this->language->__("List Partner"));
        $this->view->current_page = $this->language->__("List Partner");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datatables/datatables.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/select/select2.min.js");
        $this->assets->collection("inline")->addJs($this->_js_link . "views/partners.js");
    }

    public function searchPartnerAction()
    {
        $response   = array("data" => array());
        $member_key = $this->request->getPost("member_data", "trim", false);
        if (!empty($member_key) && strlen($member_key) > 0)
        {
            $members = \Hqend\Models\Partners::find("pa_name LIKE '%" . $member_key . "%'")->toArray();
        }
        else
        {
            $members = \Hqend\Models\Partners::find()->toArray();
        }
        foreach ($members as $key => $member)
        {
            $edit_ajax                = $this->url->get($this->module_config->main_route . "/partner/edit-partner?id=" . $member["pa_id"]);
            $response["data"][$key][] = $member["pa_id"];
            $response["data"][$key][] = $member["pa_name"];
            $response["data"][$key][] = '<button class="btn btn-info btn-icon btn-edit-content" href="' . $edit_ajax . '" type="button" data-popup="tooltip" data-target="#modal_edit_member" data-toggle="modal" data-original-title="Edit" data-rel="0">
                                            <i class="icon-pencil7"></i>
                                        </button>';
        }

        echo json_encode($response);
        exit();
    }

    public function editPartnerAction()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $id               = $this->request->getQuery("id", "trim", false);
        $this->view->data = array();
        if (!empty($id) && is_numeric($id))
        {
            $data_mem = \Hqend\Models\Partners::getPartnerById($id);
            if (!empty($data_mem))
            {
                $this->view->data = $data_mem->toArray();
            }
        }
    }

    public function savePartnerAction()
    {
        $data     = $this->request->getPost("data", null, false);
        $response = array("status" => 0, "message" => $this->language->__("Action Failed"));

        if (!empty($data))
        {
            $mem_model = new \Hqend\Models\Partners();
            if (isset($data["pa_id"]))
            {
                $id    = $data["pa_id"];
                unset($data["pa_id"]);
                $check = $mem_model->updatePartnerByID($data, $id);
            }
            else
            {
                $check = $mem_model->save($data);
            }
            if ($check)
            {
                $response = array("status" => 1, "message" => $this->language->__("Partner Updated"));
            }
            else
            {
                $response["message"] = implode(",", $mem_model->getMessages());
            }
        }
        echo json_encode($response);
        exit();
    }

//    public function deletePartnerAction()
//    {
//        $response = array(
//            "status"  => 0,
//            "message" => $this->language->__("Action Failed")
//        );
//        $id       = $this->request->getPost("id", "trim", false);
//        if (!empty($id))
//        {
//            $member_vip_obj = new \Hqend\Models\Partners();
//            if ($member_vip_obj->deletePartnersByID($id))
//            {
//                $response = array(
//                    "status"  => 1,
//                    "message" => $this->language->__("Delete Partner Success")
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
