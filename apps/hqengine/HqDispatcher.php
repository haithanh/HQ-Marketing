<?php

namespace HqEngine;

use Phalcon\Mvc\Dispatcher as PhalconDispatcher;

class HqDispatcher extends PhalconDispatcher {

    /**
     * Dispatch.
     * Override it to use own logic.
     *
     * @throws \Exception
     * @return object
     */
    public function dispatch()
    {
        try
        {
            $parts            = explode('_', $this->_handlerName);
            $finalHandlerName = '';

            foreach ($parts as $part)
            {
                $finalHandlerName .= ucfirst($part);
            }
            $this->_handlerName = $finalHandlerName;
            $this->_actionName  = strtolower($this->_actionName);
            $temp               = explode("-", $this->_actionName);
            if (count($temp) > 1)
            {
                $action_name = $temp[0];
                for ($i = 1; $i < count($temp); $i++)
                {
                    $action_name.=ucfirst($temp[$i]);
                }
                $this->_actionName = $action_name;
            }
            return parent::dispatch();
        }
        catch (\Exception $e)
        {
            $this->_handleException($e);

            if (APPLICATION_DEBUG)
            {
                throw $e;
            }
            else
            {
                $id = Exception::logError(
                                'Exception', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()
                );

                $this->getDI()->setShared(
                        'currentErrorCode', function () use ($id) {
                    return $id;
                }
                );
            }
        }

        return parent::dispatch();
    }

}
