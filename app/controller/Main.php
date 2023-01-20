<?php

namespace libs\controllers;

use libs\controller\Controller;

class Main extends Controller
{
    private $model;
    public function __construct()
    {
        // რადგან კონტროლერის ფუნქციები სტატიკია 
        //აღარ არის საჭიროებია კლასის ცალკეული გამოძახების.
        $this->model = Controller::model('main');
    }

    public function Main()
    {
        // მოდელის კლასიდან ფუნქციით მიმართვა
        $data['user_class'] = $this->model->get_user_from_DB(1);

        // ვიზუალის გამოძახება
        $this->model = Controller::view('MainPages/index', $data);
    }

    public function _404()
    {
        $data['user_class'] = $this->model->get_user_from_DB(0);
        $this->model = Controller::view('_404', $data);
    }
}
