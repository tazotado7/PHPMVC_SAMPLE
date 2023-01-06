<?php
namespace libs\controllers;

use libs\controller\Controller;
use taladashvili\vendor\bog\Bog;


include(ROOT . '/helpers/BOG/Bog.php');

class Vendor_tests extends Controller
{

    public function __construct()
    {
        $this->bog = new Bog();
    }

    public function bog_token()
    { 
      var_dump($this->bogbog->loan_token());
    }
    public function bog_calc()
    { 
      var_dump($this->bogbog->loan_calc(150));
    }
    public  function bog_req()
    { 
      $data = [
        'installment_month'=>12,
        'shop_order_id'=>47,
        'value'=>105,
        'installment_type'=>'STANDARD',
        'array'=>[
          "total_item_amount"=>"105",
          "item_description"=> "test_product",
          "total_item_qty"=> "1",
          "item_vendor_code"=> "123456",
          "product_image_url"=> "https://example.com/product.jpg",
          "item_site_detail_url"=> "https://example.com/product"
        ]
      ]; 
      var_dump($this->bogbog->loan_request($data));
    }
}