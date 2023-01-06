<?php
namespace taladashvili\vendor\bog;
use taladashvili\vendor\ipost\IPost;

include('Ipost.php');
class Bog
{
   private $client_id = 0000;
   private $secret_key = 'qwertyuiopasdfghjklzxcvbnm1234567890';

    public function __construct()
    {
       
    }

    public function loan_token()
    {
 
      $link = "https://installment.bog.ge/v1/oauth2/token"; 
      $POSTFIELDS = "grant_type=client_credentials";
      $basic = base64_encode($this->client_id . ':' . $this->secret_key);
      $basic = trim(str_replace('\n', '', (str_replace('\r', '', $basic))));
      $httpheader = ["Content-Type: application/x-www-form-urlencoded", "Authorization: Basic " . $basic];
  
      $resoult = IPost::Post($link, $POSTFIELDS, $httpheader);

        return $resoult['access_token']; 
    }
  
    public function loan_calc($amounth)
    { 
     $token = self::loan_token();
   
      $link = "https://installment.bog.ge/v1/services/installment/calculate";
      $POSTFIELDS = [
        "amount" => $amounth,
        "client_id" => $this->client_id
      ];
  
     
      $httpheader = ["Content-Type: application/json", "Authorization: Bearer" . $token]; 
      $POSTFIELDS = json_encode($POSTFIELDS);
      $resoult = IPost::Post($link, $POSTFIELDS, $httpheader);
    
      return $resoult['discounts'];
    }
  
  
    public function loan_request($decoded)
    {
       
      $error = '';
      if (!isset($decoded['installment_month']))        $error .= 'installment_month,';
      if (!isset($decoded['shop_order_id']))            $error .= 'shop_order_id,';
      if (!isset($decoded['value']))                    $error .= 'value,';
      if (!isset($decoded['array']))                    $error .= 'array,';
      if (!isset($decoded['installment_type']))                $error .= 'installment_type,';
      if (count($decoded['array']) <= 0)                $error .= 'array,';
    
      if (strlen($error) > 0) { 
        $data = [
          'error' => $error,
          'decoded' => $decoded
        ];
            return $data; 
      }
  
  
      
      $basic = self::loan_token();
   
      $installment_month = intval($decoded['installment_month']);
      $installment_type = strval($decoded['installment_type']);
      $shop_order_id = strval($decoded['shop_order_id']);
      $value = strval(number_format($decoded['value'], 2));
      $_array = $decoded['array'];
  
  
      $POSTFIELDS = array(
        "intent" => "LOAN",
        "installment_month" => $installment_month,
        "installment_type" => $installment_type,
        "shop_order_id" => $shop_order_id,
        "success_redirect_url" => "https://youre_website/callback",
        "fail_redirect_url" => "https://youre_websitecallback_reject",
        "reject_redirect_url" => "https://youre_website/callback_reject",
        "validate_items" => true,
        "locale" => "ka",
        "purchase_units" => array(
          array(
            "amount" => array(
              "currency_code" => "GEL",
              "value" => $value
            )
          )
        ),
        "cart_items" => [
          $_array
        ]
      );
  
  
  
      $link = "https://installment.bog.ge/v1/installment/checkout";
      $httpheader = ["Content-Type: application/json", "Authorization: Bearer " . $basic];
      
  
      $POSTFIELDS = json_encode($POSTFIELDS, JSON_UNESCAPED_SLASHES);
      return IPost::Post($link, $POSTFIELDS, $httpheader);
   
      //return json_encode($resoult, JSON_UNESCAPED_SLASHES);
    }
  
 
}