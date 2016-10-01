<?php

namespace Hqend\Controllers;

class AccessController extends \Phalcon\Mvc\Controller {

    public function indexAction()
    {
        $this->tag->setTitle("Login");
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
    }

    public function loginAction()
    {
        $response = array("status" => 0, "message" => $this->language->__("Username Or Password not true"));
        $username = $this->request->getPost("username", "trim", false);
        $password = $this->request->getPost("password", "trim", false);
        if (!empty($username) && !empty($password))
        {
            $admin = \Hqend\Models\MemberVip::getMemberVipByUsername($username);
            if (!empty($admin))
            {
                $admin = $admin->toArray();
                if ($this->security->checkHash($password, $admin["mv_secret"]))
                {
                    $response = array("status" => 1, "message" => $this->language->__("Login Success"));
                    $this->session->set("admin", $admin);
                }
            }
        }

        echo json_encode($response);
        exit();
    }

    public function testAction()
    {
        print_r($this->security->hash("!@#123"));
        die;
    }

    public function logoutAction()
    {
        $this->session->remove("admin");
        $this->response->redirect($this->module_config->main_route . "/access");
    }

}
