<?php

namespace Mkt\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller {

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
