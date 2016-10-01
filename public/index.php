<?php

use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));
define('DS', DIRECTORY_SEPARATOR);
if (!defined('ROOT_PATH'))
{
    define('ROOT_PATH', dirname(dirname(__FILE__)));
}
if (!defined('PUBLIC_PATH'))
{
    define('PUBLIC_PATH', dirname(__FILE__));
}
ini_set('display_errors', 1);
define("APPLICATION_DEBUG", true);
define("IP_DEBUG", "113.172.137.51");
error_reporting(E_ALL);
try
{
    (new Phalcon\Debug)->listen();
    require_once ROOT_PATH . "/apps/hqengine/HqTool/HqUtil.php";
    require_once ROOT_PATH . "/apps/hqengine/HqConfig.php";
    require_once ROOT_PATH . "/apps/hqengine/HqException.php";
    require_once ROOT_PATH . "/apps/hqengine/HqApplication/HqApplicationInit.php";
    require_once ROOT_PATH . "/apps/hqengine/HqApplication/HqApplication.php";

    $application = new \HqEngine\HqApplication\HqApplication();
    $application->run();
    echo $application->getOutput();
//    $application->clearCache();
}
catch (Exception $e)
{
    echo $e->getMessage();
}

