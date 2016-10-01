<?php

namespace Core\Controllers;

class IndexController extends ControllerBase {

    public function indexAction()
    {
        $language = $this->request->getQuery("language");
        if ($language == "vi")
        {
            $this->session->set("language", "vi");
            $this->session->set("locale", "vi_vn");
        }
        else
        {
            $this->session->set("language", "en");
            $this->session->set("locale", "en_us");
        }
    }

}
