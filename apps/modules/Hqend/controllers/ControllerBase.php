<?php

namespace Hqend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller {

    public $_js_link  = "public/js/Hqend/";
    public $_css_link = "public/css/Hqend/";

    public function beforeExecuteRoute($dispatcher)
    {
        $admin = $this->session->has("admin");
        if (!$admin || empty($admin))
        {
            $dispatcher->setReturnedValue($this->response->redirect($this->module_config->main_route . '/access'));
            return false;
        }
        return true;
    }

    protected function _response($message, $status = 0, $data = array())
    {
        echo json_encode(array(
            "message" => $message,
            "status"  => $status,
            "data"    => $data
        ));
        exit();
    }

}
