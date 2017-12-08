<?php

namespace Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class Factory {

    use Nette\StaticClass;

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter() {
        $router = new RouteList;

        $router[] = new Route('<presenter>/<action>/[<id>]', array(
            "module" => "Admin",
            "presenter" => "Dashboard",
            "action" => "default",
            "id" => null
        ));
        $router[] = new Route('<module>/<presenter>/<action>/[<id>]', array(
            "module" => "Front",
            "presenter" => "Homepage",
            "action" => "default",
            "id" => null
        ));

        $router[] = new Route('/*', array(
            "module" => "Front",
            "presenter" => "Homepage",
            "action" => "default",
            "id" => null
        ));
        return $router;
    }

}
