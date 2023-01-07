<?php
namespace libs\core;

class Core
{

    //დეფაულტად გამოვიყენოთ Main კონტროლერი, როდესაც არ გვაქვს კონტროლერი ან/და არასწორად წერია, ყოველთვის გაიხსნება მაინი
    protected $Controller = 'Main';
    //კონტროლერის ფუნქცია website/main/<method> საიტის მისამართში რაც იქნება მითითებული, დეფაულტად 404 გვერძე გადავაგდებთ.
    protected $Method = '_404';
    //დანარჩენს პარამეტრებში ვისვრით.
    protected $params = [];

    // თავიდან გაეშვება კონსტრუქტორი
    public function __construct()
    {
        // წამოვიღებთ მოთხვონილ საიტის ურლ-ს
        $url = $this->getUrl();

        //ვამოწმებთ რომ არაი ცარიელი არ არის და ვამოწმებთ რომ კონტროლერის ფოლდერში მსგავსი ფაილი არსებობს
        if (count($url) > 1 && file_exists(ROOT . '/controller/' . ucwords($url[0]) . '.php')) {
            //დადასტურების შემთხვევაში კონტროლერში მხოლოდ დასახელება როგორც სტრინგი ისე იწერება
            $this->currentController = ucwords($url[0]);
            // და ურლ მასივიდან ამოვშლით.
            unset($url[0]);
        }
        // აქ ვამოწმებთ თუ ურლ მასივი მხოლოდ ერთი პარამეტრი წერია რომ გადავამისამართოთ მაინის ფუნქციად.
        $urle = count($url) > 1 ? $url[1] : $url[0];

        $urle = explode('.', $urle)[0];


        // აქ უკვე გამოძახებას ვაკეთებთ ფაილის.
        require_once ROOT . '/controller/' . ucwords($this->Controller) . '.php';
        // კონტროლერი გახდება კლასის დასახელებისთვის გამოსადეგი, ნეიმსფეისების მოთხოვნების ჩათვლით.
        $this->Controller = 'libs\\controllers\\' . $this->Controller;
        // კონტროლერი კლასის ტიპისაა და შეგვიძლია მასზე მანიპულირება როგორც კლასის ობიექტზე.
        $this->Controller = new $this->Controller;
        // მეთოდში იწერება კლასის კონტროლერი თუ შეიცავს ამ მეთოდს თუ არადა დეფაულტ 404 ზე გადის
        $this->Method = method_exists($this->Controller, $urle) ? $urle : $this->Method;

        // პარამეტრებში გადადის დარჩენილი მასივის ნაწილი.
        $this->params = $url ? array_values($url) : [null];

        // კიდევ ერთხელ მოწმდება თუ მეთოდი  არ არსებობს კონტროლერ კლასში დეფაულტზე გადაიყვანოს
        if(!method_exists($this->Controller, $this->Method))
        {
            $this->Controller = 'Main';
            $this->Method = '_404';
        } 
        //გამოიძახება შესაბამისი კლასი და კონტოლერი.
        call_user_func_array([$this->Controller, $this->Method], $this->params);
    }


// ეს პატარა ფუნქცია აბრუნებს მოთხოვნილი ლინკის მასივს.
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
