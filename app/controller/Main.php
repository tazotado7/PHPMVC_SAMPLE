<?php

namespace libs\controllers;

use libs\controller\Controller;

class Main extends Controller
{

    public function __construct()
    {
        $this->model = Controller::model('main');
    }

    public function Main()
    {
        $data['user_class'] = $this->model->get_user_from_DB(1);
        $this->model = Controller::view('MainPages/index', $data);
    }

    public function _404()
    {
        $data['user_class'] = $this->model->get_user_from_DB(0);
        $this->model = Controller::view('_404', $data);
    }
}
