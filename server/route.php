<?php

class Route {
    public static function getRoute($url){

        $url = trim($url, '/');
        $urlSegments = explode('/', $url);
    
        $scheme = ['controller', 'action', 'params'];
        $route = [];
    
        foreach ($urlSegments as $index => $segment){        
            if ($scheme[$index] == 'params'){
                $route['params'] = array_slice($urlSegments, $index);
                break;
            } else {
                $route[$scheme[$index]] = $segment;
            }
        }
    
        return $route;
    }
}
?>