<?php

namespace User\Controllers;

class IndexController extends ControllerBase {

    public function indexAction()
    {
        echo phpinfo();
    }

}
