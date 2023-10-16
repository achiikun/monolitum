<?php

namespace monolitum\core;

// spl_autoload_register(function ($class_name) {
//     include $class_name . '.php';
// });

use monolitum\core\panic\BreakExecution;
use monolitum\core\util\ResourceAddressResolver;

class Monolitum{

    /**
     * @param string $localAddress
     * @param ResourceAddressResolver $resourcesAddressResolver
     * @param Node $router
     */
    public static function execute($localAddress, $resourcesAddressResolver, $router){

        $ctx = new Context();
        $ctx->setLocalAddress($localAddress);
        $ctx->setResourcesAddressResolver($resourcesAddressResolver);
        
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
