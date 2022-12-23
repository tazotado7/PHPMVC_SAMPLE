<?php

namespace libs\core;

class Core
{

    protected $Controller = 'Main';
    protected $Method = '_404';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        if (count($url) > 1 && file_exists(ROOT . '/controller/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        }
        $urle = count($url) > 1 ? $url[1] : $url[0];

        $urle = explode('.', $urle)[0];



        require_once ROOT . '/controller/' . ucwords($this->Controller) . '.php';

        $this->Controller = 'libs\\controllers\\' . $this->Controller;

        $this->Controller = new $this->Controller;

        $this->Method = method_exists($this->Controller, $urle) ? $urle : $this->Method;

        $this->params = $url ? array_values($url) : [null];

        if(!method_exists($this->Controller, $this->Method))
        {
            $this->Controller = 'Main';
            $this->Method = '_404';
        } 

        call_user_func_array([$this->Controller, $this->Method], $this->params);
    }



    public function getUrl()
    {
        if (!isset($_GET['url'])) {
            return array('\\', '');
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        return $url;
    }
}
