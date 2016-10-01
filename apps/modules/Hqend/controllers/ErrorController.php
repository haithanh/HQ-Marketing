<?php

namespace Hqend\Controllers;

class ErrorController extends \Phalcon\Mvc\Controller {

    public function initialize()
    {
        $action = $this->dispatcher->getActionName();
        $this->response->redirect(\HqEngine\HqApplication\HqApplication::SYSTEM_ERROR_MODULE . "/error/" . $action);
        return false;
    }

    public function show404Action()
    {
        
    }

    public function show500Action()
    {
        
    }

}
