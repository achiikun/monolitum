<?php

namespace monolitum\core;

// spl_autoload_register(function ($class_name) {
//     include $class_name . '.php';
// });

use monolitum\core\panic\BreakExecution;

class Monolitum{

    /**
     * @param string $localAddress
     * @param string $resourcesAddress
     * @param Node $router
     */
    public static function execute($localAddress, $resourcesAddress, $router){

        $ctx = new Context();
        $ctx->setLocalAddress($localAddress);
        $ctx->setResourcesAddress($resourcesAddress);
        
        GlobalContext::setGlobalContext($ctx);

        try{

            $router->_build($ctx, null);
            $router->_execute();

        }catch (BreakExecution $ignored){

        }

        // TODO: check for panics
        
        GlobalContext::setGlobalContext(null);
        
    }

    /**
     * @param Active $active
     */
    public static function add($active) {
        GlobalContext::add($active);
    }

}
