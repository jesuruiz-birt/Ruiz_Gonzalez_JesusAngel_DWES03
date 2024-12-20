<?php

require '../core/router.php';
require '../app/controllers/post.php';

$url = $SERVER['QUERY_STRING'];
echo 'URL = ' .$url.'<br>';

$router = new Router();

$router->add('/public', array(
    'controller'=>'Home',
    'action'=> 'index'
));

$router->add('/public/posts/new', array(
    'controller'=>'Posts',
    'action'=> 'new'
));

$urlParams = explode('/',$url);

$urlArray = array(
    'HTTP'=>$SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=>''
);

if(!empty($urlParams[2])){
    $urlArray['controller'] = ucwords($urlParams[2]);
    if(!empty($urlParams[3])){
        $urlArray['action'] = $urlParams[3];
        if(!empty($urlParams[4])){
            $urlArray['action'] = $urlParams[3];
        }
    }else{
        $urlArray['action'] = 'index';
    }
}else{
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}

if($router->match($urlArray)){
    $controller = $router->getParams()['controller'];
    $action=$router->getParams()['action'];

    $controller=new $controller();
    $controller->$action();
}else{
    echo "No route found for URL ".$url;
}

echo '<pre>';
print_r($urlArray) .'<br>';
echo '</pre>';

