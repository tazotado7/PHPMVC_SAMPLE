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
    if (!isset($decoded['installment_month']))
      $error .= 'installment_month,';
    if (!isset($decoded['shop_order_id']))
      $error .= 'shop_order_id,';
    if (!isset($decoded['value']))
      $error .= 'value,';
    if (!isset($decoded['array']))
      $error .= 'array,';
    if (!isset($decoded['installment_type']))
      $error .= 'installment_type,';
    if (!isset($decoded['success_redirect_url']))
      $error .= 'success_redirect_url,';
    if (!isset($decoded['fail_redirect_url']))
      $error .= 'fail_redirect_url,';
    if (!isset($decoded['reject_redirect_url']))
      $error .= 'reject_redirect_url,';
    if (count($decoded['array']) <= 0)
      $error .= 'array,';

    if (strlen($error) > 0) {
      $token = 'error';
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
    $success_redirect_url = strval($decoded['success_redirect_url']);
    $fail_redirect_url = strval($decoded['fail_redirect_url']);
    $reject_redirect_url = strval($decoded['reject_redirect_url']);
    $value = strval(number_format($decoded['value'], 2));
    // $value = strval($decoded['value']);


    $POSTFIELDS = array(
      "intent" => "LOAN",
      "installment_month" => $installment_month,
      "installment_type" => $installment_type,
      "shop_order_id" => $shop_order_id,
      "success_redirect_url" => $success_redirect_url,
      "fail_redirect_url" => $fail_redirect_url,
      "reject_redirect_url" => $reject_redirect_url,
      "validate_items" => false,
      "locale" => "ka",
      "purchase_units" => array(
        array(
          "amount" => array(
            "currency_code" => "GEL",
            "value" => $value
          )
        )
      ),
      "cart_items" => $decoded['array'] 
    );



    $link = "https://installment.bog.ge/v1/installment/checkout";
    $httpheader = ["Content-Type: application/json", "Authorization: Bearer " . $basic];



    $resoult = IPost::Post($link, json_encode($POSTFIELDS, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $httpheader);
    /*
    $dt = [
    'sending'=>[
    'link'=>$link,
    'httpheader'=>$httpheader,
    'POSTFIELDS'=>$POSTFIELDS
    ],
    'resoult'=>$resoult 
    ];
    */
    return $resoult;

  }

  public function loan_check($orderid)
  {
    $tokeni = self::loan_token();
    $link = "https://installment.bog.ge/v1/installment/checkout/" . $orderid;
    $httpheader = ["Content-Type: application/x-www-form-urlencoded", "Authorization: Basic " . $tokeni];
    $resoult = IPost::Get($link, null, $httpheader);


    $dt = [
      'sending' => [
        'link' => $link,
        'httpheader' => $httpheader
      ],
      'received' => $resoult
    ];
    return $dt;
  }

  public function bog_modal($decoded)
  {

    $resoult = self::loan_request($decoded);

    if (!isset($resoult['resoult']['status']) || $resoult['resoult']['status'] != 'CREATED')
      return null;
 
    if (empty($resoult['resoult']['order_id']))
      return null;
 
    $return_data['order_id'] = $resoult['resoult']['order_id'];

    return json_encode($return_data);
  }


}