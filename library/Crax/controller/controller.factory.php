<?php
class ControllerFactory
{   
    public static function createController($bootstrap)
    {
        include('library/Crax/HTTP/request.class.php');
        $request = new HTTP_Request($_SERVER['REQUEST_URI'],$bootstrap->getConfiguration()->getOption("relPath"));
        $action = ucfirst($request->getAction())."Action";
        $controllerName = ucfirst($request->getController())."Controller";
        if($request->isValid()){
            if($bootstrap->getCache()->check($controllerName))
            {
                return $bootstrap->getCache()->get($controllerName);
            }else{
                include_once('library/Crax/controller/controller.class.php');
                require_once('application/controllers/'.$request->getController().'.php');
                $controller = new $controllerName($request);
                $bootstrap->getCache()->put($controllerName,$controller);
                return $controller;
            }
        }
    }
}
?>