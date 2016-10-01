<?php

namespace User\Controllers;

class ErrorController extends ControllerBase {

    public function indexAction()
    {
        echo 456;
        die;
    }

    public function show404Action()
    {
        
    }

    public function show500Action()
    {
        
    }

}
