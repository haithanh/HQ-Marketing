<?php

namespace Hqend\Controllers;

class IndexController extends ControllerBase {

    public function indexAction()
    {
        $this->tag->setTitle($this->language->__("Dashboard"));
        $this->view->current_page = $this->language->__("Dashboard");
    }

}
