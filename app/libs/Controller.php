<?php

namespace libs\controller;

class Controller
{
    public static function model($value)
    {
        $p = ucwords($value);
        $path = ROOT.'/models/'.$p.'Model.php';
        if (!file_exists($path))
            throw new \Exception('Model Not Found');


        require_once $path;

        $p = 'models\\' . $p;
        return  new $p;
    }

    public static function view($view,$data=[])
    {
        $p = ucwords($view);
        $path = ROOT.'/views/' . $p . '.php';
        if (!file_exists($path))
            throw new \Exception('Model Not Found');


        require_once $path;

    }

}
