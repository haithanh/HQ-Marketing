<?php

namespace Hqend\Controllers;

class MemberVipController extends ControllerBase {

    public function indexAction()
    {
        $this->tag->setTitle($this->language->__("List Admin"));
        $this->view->current_page = $this->language->__("List Admin");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/datatables/datatables.min.js");
        $this->assets->collection("head")->addJs("public/js/lib/plugins/forms/select/select2.min.js");
        $this->assets->collection("inline")->addJs($this->_js_link . "views/member-vip.js");
    }

    public function searchMemberAction()
    {
        $response   = array("data" => array());
        $member_key = $this->request->getPost("member_data", "trim", false);
        if (!empty($member_key) && strlen($member_key) > 0)
        {
            $members = \Hqend\Models\MemberVip::find("mv_name LIKE '%" . $member_key . "%'")->toArray();
        }
        else
        {
            $members = \Hqend\Models\MemberVip::find()->toArray();
        }
        foreach ($members as $key => $member)
        {
            $edit_ajax                = $this->url->get($this->module_config->main_route . "/member-vip/edit-member?id=" . $member["mv_id"]);
            $response["data"][$key][] = $member["mv_id"];
            $response["data"][$key][] = $member["mv_name"];
            $response["data"][$key][] = $member["mv_nickname"];
            $response["data"][$key][] = $member["mv_permission"];
            $response["data"][$key][] = '<button class="btn btn-info btn-icon btn-edit-content" href="' . $edit_ajax . '" type="button" data-popup="tooltip" data-target="#modal_edit_member" data-toggle="modal" data-original-title="Edit" data-rel="0">
                                            <i class="icon-pencil7"></i>
                                        </button>
                                         <button onclick="deleteMemberVip(' . $member["mv_id"] . ');" class="btn btn-danger btn-icon btn-delete-content" type="button" data-popup="tooltip" data-original-title="Remove" data-rel="1">
                                            <i class="icon-trash"></i>
                                        </button>';
        }

        echo json_encode($response);
        exit();
    }

    public function editMemberAction()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $id               = $this->request->getQuery("id", "trim", false);
        $this->view->data = array();
        if (!empty($id) && is_numeric($id))
        {
            $data_mem = \Hqend\Models\MemberVip::getMemberVipById($id);
            if (!empty($data_mem))
            {
                $this->view->data = $data_mem->toArray();
            }
        }
    }

    public function saveMemberAction()
    {
        $data     = $this->request->getPost("data", null, false);
        $response = array("status" => 0, "message" => $this->language->__("Action Failed"));

        if (!empty($data))
        {
            $mem_model = new \Hqend\Models\MemberVip();
            if (!empty($data["mv_secret"]) && strlen($data["mv_secret"]) > 0)
            {
                $data["mv_secret"] = $this->security->hash($data["mv_secret"]);
            }
            if (isset($data["mv_id"]))
            {
                $id    = $data["mv_id"];
                unset($data["mv_id"]);
                $check = $mem_model->updateMemberVipByID($data, $id);
            }
            else
            {
                $data["mv_last_login"] = date("Y-m-d H:i:s");
                $check                 = $mem_model->save($data);
            }
            if ($check)
            {
                $response = array("status" => 1, "message" => $this->language->__("Member Updated"));
            }
            else
            {
                $response["message"] = implode(",", $mem_model->getMessages());
            }
        }
        echo json_encode($response);
        exit();
    }

    public function deleteMemberAction()
    {
        $response = array(
            "status"  => 0,
            "message" => $this->language->__("Action Failed")
        );
        $id       = $this->request->getPost("id", "trim", false);
        if (!empty($id))
        {
            $member_vip_obj = new \Hqend\Models\MemberVip();
            if ($member_vip_obj->deleteMemberVipByID($id))
            {
                $response = array(
                    "status"  => 1,
                    "message" => $this->language->__("Delete Admin Success")
                );
            }
            else
            {
                $response = array(
                    "status"  => 0,
                    "message" => implode(",", $member_vip_obj->getMessages())
                );
            }
        }
        echo json_encode($response);
        exit();
    }

}
