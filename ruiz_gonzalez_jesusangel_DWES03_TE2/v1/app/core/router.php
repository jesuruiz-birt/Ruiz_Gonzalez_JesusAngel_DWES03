<?php

class Router
{
    protected $routes = array();
    protected $params = array();

    public function add($route, $params)
    {
        // Convertir la ruta a una expresión regular
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_\-]+)', $route);
        $route = '/^' . $route . '$/';
        $this->routes[$route] = $params;
    }

    public function match($url)
    {
        // Eliminar la parte de la URL hasta /public
        $url = preg_replace('/^.*\/public\//', '', $url);
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                array_shift($matches); // Eliminar el primer elemento que es la cadena completa
                $this->params = $params;
                $this->params['matches'] = $matches;
                return true;
            }
        }
        return false;
    }

    public function getParams()
    {
        return $this->params;
    }
}

