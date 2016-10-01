<?php

namespace Hqend\Controllers;

class MemberController extends ControllerBase {

    public function indexAction()
    {
        $this->tag->setTitle($this->language->__("List User"));
        $this->view->current_page = $this->language->__("List User");
        $this->assets->collection("head")->addJs("js/lib/plugins/datatables/datatables.min.js");
        $this->assets->collection("head")->addJs("js/lib/plugins/forms/select/select2.min.js");
        $this->assets->collection("inline")->addJs("js/views/admin/member.js");
    }

    public function searchMemberAction()
    {
        $response   = array("data" => array());
        $member_key = $this->request->getPost("member_data", "trim", false);
        if (!empty($member_key) && strlen($member_key) > 0)
        {
            $members = \Hqend\Models\Member::getFilter($member_key);
            foreach ($members as $key => $member)
            {
                $response["data"][$key][] = $member["mem_id"];
                $response["data"][$key][] = $member["mem_public_id"];
                $response["data"][$key][] = $member["mem_acc"];
                $response["data"][$key][] = $member["mem_nickname"];
                $response["data"][$key][] = $member["mem_email"];
                $response["data"][$key][] = number_format($member["mem_gold"]);
                if ($member["mem_active"])
                {
                    $response["data"][$key][] = '<span class="label label-success">' . $this->language->__("Active") . '</span>';
                }
                else
                {
                    $response["data"][$key][] = '<span class="label label-default">' . $this->language->__("Disabled") . '</span>';
                }
                $url_ajax                 = $this->url->get($this->module_config->main_route . "/member/editmember?id=" . $member["mem_id"]);
                $response["data"][$key][] = $member["mem_created_date"];
                $response["data"][$key][] = '<ul class="icons-list">
                                                <li class="dropdown open">
                                                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                					<li><a href="' . $url_ajax . '" data-backdrop="static" data-keyboard="false"  data-target="#modal_edit_member" data-toggle="modal"><i class="icon-pencil3"></i>' . $this->language->__("Edit") . '</a></li>
                                                    </ul>
						</li>
                                            </ul>';
            }
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
            $data_mem = \Hqend\Models\Member::getMemberById($id);
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

        if (!empty($data) && isset($data["mem_id"]))
        {
            $mem_model = new \Hqend\Models\Member();
            $id        = $data["mem_id"];
            if (!empty($data["mem_pa"]) && strlen($data["mem_pa"]) > 0)
            {
                $data["mem_pa"] = $this->security->hash($data["mem_pa"]);
            }
            unset($data["mem_id"]);
            if ($mem_model->updateMemberByID($data, $id))
            {
                $response = array("status" => 1, "message" => $this->language->__("Member Updated"));
            }
        }
        echo json_encode($response);
        exit();
    }

}
