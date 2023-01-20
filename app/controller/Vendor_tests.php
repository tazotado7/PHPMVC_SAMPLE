<?php
namespace libs\controllers;

use libs\controller\Controller;
use taladashvili\vendor\bog\Bog;


include(ROOT . '/helpers/BOG/Bog.php');

class Vendor_tests extends Controller
{
  private $bog;
  private $data;
  private $itemdata;

    public function __construct()
    {
        $this->bog = new Bog();

    $this->itemdata = [
      "total_item_amount" => "105",
      "item_description" => "test_product",
      "total_item_qty" => "1",
      "item_vendor_code" => "96",
      "product_image_url" => "https://example.com/product.jpg",
      "item_site_detail_url" => "https://example.com/product/",
      
    ];

    $this->data = array(
      "intent" => "LOAN",
      "installment_month" => 12,
      "installment_type" => "STANDARD",
      "shop_order_id" => 123456,
      "success_redirect_url" => "https://example.com/product/orders?orderid=",
      "fail_redirect_url" => "https://example.com/product/orders?orderid=",
      "reject_redirect_url" => "https://example.com/product/orders?orderid=",
      "validate_items" => false,
      "locale" => "ka",
      "purchase_units" => array(
        array(
          "amount" => array(
            "currency_code" => "GEL",
            "value" => 105
          )
        )
      ),
      "cart_items" => $this->itemdata
    );
    
    
   

  }

    public function bog_token()
    { 
      var_dump($this->bog->loan_token());
    }
    public function bog_calc()
    { 
      var_dump($this->bog->loan_calc(150));
    }
    public  function bog_req()
    {
      $data = $this->data;

      var_dump($this->bog->loan_request($data));
    }

    public function bog_loan_pay()
    {
     
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
      exit();

    $months = isset($_POST['months']) ? $_POST['months'] : '12'; 
    $discount_code = isset($_POST['discount_code']) ? $_POST['discount_code'] : 'STANDARD';

    $data = $this->data;
    $data['installment_month'] = $months;
    $data['installment_type'] = $discount_code;

    $sumi = 105; // აქ წამოვიღოთ ჩვენი ბაზიდან და არა მომხმარებლის მიერ გამოგზავნილი.
    $data['purchase_units'][0][0]['amount']['value'] = $sumi; // ეს არის ჯამური თანხა რა არის
    $data['cart_items']['total_item_amount'] = $sumi; // ეს არის პროდუქტების სიის თანხა, ამ შემთხვევაში ერთი პროდუქტია

    $shop_order_id = 12345; // ქარდის ცხრილიდან გადამაქვს მე ორდერების ცხრილში მონაცემები, და ახალი ორდერის აიდი მომაქვს, თუ ქარდში ტოვებთ მაშინ ქარდის აიდი წამოიღეთ.

    $redirect_my_url = "https://example.com/product/orders?orderid=" . $shop_order_id;
    $data['success_redirect_url'] = $redirect_my_url;
    $data['fail_redirect_url'] = $redirect_my_url;
    $data['reject_redirect_url'] = $redirect_my_url;

     
 
    // აქედან უკვე ვაგზავნით საქართველოს ბანკის კლასში მონაცემებს და ვამუშავებთ მიღებულ პასუხს
    $resoult = $this->bog->loan_request($data);
     
    if (isset($resoult['resoult']['status']) && $resoult['resoult']['status'] == 'CREATED') {

      $href = $resoult['resoult']['links'][1]['href']; // სად უნდა გადავამისამართოთ კლიენტი
      $order_id = $resoult['resoult']['order_id']; // ბანკის ორდერ აიდი,

      /*

      საიტის მოდალში რა პარამეტრსაც მიუთითებთ  orderId -ის ნაცვლად იგივე დააბრუნეთ უკან

      .then(data => successCb(data.orderId))

      */
      
      echo json_encode(['orderId'=>$order_id]); // გამოიყენება successCb(data.orderId))-სთვის
      exit();

    }  
    exit();
    }
}