<?php

class Router{

    protected $routes = array();
    protected $params = array();

    public function add($route, $params)
    {
        $this->routes[$route] = $params;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function match($url){
        foreach($this->routes as $route=>$params){
            if($url['path']==$route){
                if($params['controller']==$url['controller'] && $params['action']==$url['action']){
                    $this->params = $params;
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    public function getParams(){
        return $this->params;
    }
}