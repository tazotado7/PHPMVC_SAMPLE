<?php

namespace libs\controller;

// საიტის კონტროლერი შედგება შემდეგი ფუნქციებისგან
class Controller
{
    // $this->model($value)->   ამ ფუნქციის გამოძახებისას ბრუნდება შესაბამის /model/ ფოლდერში არსებული მოდეილის კლასი
    public static function model($value)
    {
        // ვალუე პარამერტრი ხდება პირველი დიდი დანარჩენი პატარა ასოებად.
        $p = ucwords($value);
        // ფაილის მისამართის ჩაწერა
        $path = ROOT.'/models/'.$p.'Model.php';
        if (!file_exists($path))
            throw new \Exception('Model Not Found');
        // თუ ფაილი არ არსებობს აგდებს ერორს

        // გამოიძახება ეს ფაილი
        require_once $path;

        // ნეიმსფეისზე დაყრდნობით საჭიროა ამდაგვარი გამოძახება და დაბრუნება new პარამეტრით.
        $p = 'models\\' . $p;
        return  new $p;
    }

    // ასევეა ვიუც, ვუ ფუნქცია ხდება ახალი ვიუ კლასი რომელიც იძახებს დამოუკიდებელ ვიზუალის კლასს და გადასცემს პარამეტრებს.
    // იგივეა რაც მოდელ ფუნქცია
    public static function view($view,$data=[])
    {
        $p = ucwords($view);
        $path = ROOT.'/views/' . $p . '.php';
        if (!file_exists($path))
            throw new \Exception('Model Not Found');


        require_once $path;

    }

}
