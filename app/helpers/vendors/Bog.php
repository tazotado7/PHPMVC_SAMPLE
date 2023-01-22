<?php
namespace taladashvili\vendor\bog;
use taladashvili\vendor\ipost\IPost;

include('IPost.php');
// Parent class
abstract class Bank_Of_Georgia
{
    protected $client_id = 1234;
    protected $secret_key = 'qwewrtyuiopasdfghjkl;zxcvbnm,.';


    /*      loan       */
    protected $loan_link = "https://installment.bog.ge/v1/oauth2/token";
    protected $loan_httpheader = ["Content-Type: application/x-www-form-urlencoded", "Authorization: Basic "]; 
    protected $loan_POSTFIELDS = "grant_type=client_credentials";

/*      loan_calc       */
    protected $loan_calc_link = "https://installment.bog.ge/v1/services/installment/calculate";
    protected $loan_calc_httpheader = ["Content-Type: application/json", "Authorization: Bearer"];
    protected $loan_calc_POSTFIELDS = [
        "amount" => 0,
        "client_id" => 0
      ];


/*      loan_request       */

    protected $loan_request_link = "https://installment.bog.ge/v1/installment/checkout";
    protected $loan_request_httpheader = ["Content-Type: application/json", "Authorization: Bearer "];


    /*      loan_check       */
    protected $loan_check_link = "https://installment.bog.ge/v1/installment/checkout/";
    protected $loan_check_httpheader = ["Content-Type: application/x-www-form-urlencoded", "Authorization: Basic "];

    /*      pay       */

    protected $pay_link = "https://ipay.ge/opay/api/v1/oauth2/token"; 
    protected $pay_POSTFIELDS = "grant_type=client_credentials";
    protected $pay_httpheader = ["Content-Type: application/x-www-form-urlencoded", "Authorization: Basic "];
     
    /*      pay_order       */
    protected $pay_order_link = "https://ipay.ge/opay/api/v1/checkout/orders";
    protected $pay_order_httpheader = ["Content-Type: application/json", "Authorization: Bearer "];


    /*      pay_order_details       */
    protected $pay_order_details_link = "https://ipay.ge/opay/api/v1/checkout/payment/";
    protected $pay_order_details_httpheader = ["Content-Type: application/json", "Authorization: Bearer "];





    public function __construct()
    {
        $this->loan_httpheader[1] .= $this -> Basic();
        $this->loan_calc_POSTFIELDS['client_id'] = $this->client_id;
    }
    protected function Basic()
    {
        $basic = base64_encode($this->client_id . ':' . $this->secret_key);
        return trim(str_replace('\n', '', (str_replace('\r', '', $basic)))); 
    }

    protected function j_encode($value)
    {
        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
 

    abstract public function loan_token(); 
    abstract public function loan_calc($amounth); 
    abstract public function loan_request($decoded); 
    abstract public function loan_request_POSTFIELDS($decoded); 
    abstract public function loan_check($orderid); 
    abstract public function html_loan_button(); 
    abstract public function html_loan_modal($sumi,$cart_id); 
    abstract public function pay_token();  
    abstract public function pay_order_POSTFIELDS($decoded);
    abstract public function pay_order($decoded);
    abstract public function pay_order_details($order_id);
}


class Bog extends Bank_Of_Georgia
{
    public function __construct()
    {

    }

    public function loan_token()
    {  
        $resoult = IPost::Post(
            $this->loan_link , 
            $this->loan_POSTFIELDS, 
            $this->loan_httpheader
        );
  
        if(empty($resoult['access_token']))
        {
            $resoult = ['error'=>$resoult];
            return $resoult; 
        }
        return $resoult['access_token']; 
    }

    public function loan_calc($amounth)
    { 
     $token = $this->loan_token();

        if (!empty($token['error']))
            return $token;
        
        $this->loan_calc_POSTFIELDS['amount'] = $amounth;
        $this->loan_calc_httpheader[1] .= $token;
   
      $resoult = IPost::Post(
        
        $this->loan_calc_link, 
        $this->j_encode($this->loan_calc_POSTFIELDS), 
        $this->loan_calc_httpheader
      );
    
      if(empty($resoult['discounts']))
      {
        $resoult = ['error'=>$resoult];
            return $resoult; 
      } 
      return $resoult['discounts'];
    }

    public function  loan_request_POSTFIELDS($decoded)
    {
        $error = '';
        if (!isset($decoded['installment_month']))        $error .= 'installment_month,';
        if (!isset($decoded['shop_order_id']))            $error .= 'shop_order_id,';
        if (!isset($decoded['value']))                    $error .= 'value,';
        if (!isset($decoded['array']))                    $error .= 'array,';
        if (!isset($decoded['installment_type']))                $error .= 'installment_type,';
        if (!isset($decoded['success_redirect_url']))                $error .= 'success_redirect_url,';
        if (!isset($decoded['fail_redirect_url']))                $error .= 'fail_redirect_url,';
        if (!isset($decoded['reject_redirect_url']))                $error .= 'reject_redirect_url,';
        if (count($decoded['array']) <= 0)                $error .= 'array,';
      
        if (strlen($error) > 0) { 
          $data = [
            'error' => $error,
            'decoded' => $decoded
          ];
              return $data; 
        }
    
    
        
      
     
        $installment_month = intval($decoded['installment_month']);
        $installment_type = strval($decoded['installment_type']);
        $shop_order_id = strval($decoded['shop_order_id']);
        $success_redirect_url = strval($decoded['success_redirect_url']);
        $fail_redirect_url = strval($decoded['fail_redirect_url']);
        $reject_redirect_url = strval($decoded['reject_redirect_url']);
         $value = strval(number_format($decoded['value'], 2));
      
    
    
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
          "cart_items" =>  
            $decoded['array']
           
        );

        return $POSTFIELDS;
    }
    public function loan_request($decoded)
    {

        $POSTFIELDS = $this->loan_request_POSTFIELDS($decoded);

        if (!empty($POSTFIELDS['error']))
            return $POSTFIELDS;

            $token = $this->loan_token();

            if (!empty($token['error']))
                return $token;
 
        $this->loan_request_httpheader[1] .= $token;
    
      $resoult =IPost::Post(
        $this->loan_request_link , 
        $this->j_encode($POSTFIELDS), 
        $this->loan_request_httpheader); 

        $dt = [
            'sending'=>[
              'link'=>$this->loan_request_link ,
              'httpheader'=>$this->loan_request_httpheader,
              'POSTFIELDS'=>$POSTFIELDS
            ],
            'resoult'=>$resoult 
          ];

        if (!isset($resoult['resoult']['status']) || $resoult['resoult']['status'] != 'CREATED')
        {
            return ['error' => $dt];
        }

     
      return $dt; 
    }

    public function loan_check($orderid)
    {
 
        $token = $this->loan_token();

        if (!empty($token['error']))
            return $token;

        $this->loan_check_httpheader[1] .= $token;

      $this->loan_check_link .= $orderid;
      
       
      $resoult = IPost::Get(
        $this->loan_check_link, 
        null, 
        $this->loan_check_httpheader);

     
  
      $dt = [
        'sending'=>[
          'link'=>$this->loan_check_link,
          'httpheader'=>$this->loan_check_httpheader
        ],
        'received'=>$resoult
      ];
    return $dt;
    }

    public function html_loan_button()
    {
    return '<div class="bog-smart-button"></div>';
    }
    public function html_loan_modal($sumi,$cart_id)
    {

      $amounth = $sumi;
      $cartid = !empty($cart_id) ? $cart_id : -1;

    return ' 
    <script src="https://webstatic.bog.ge/bog-sdk/bog-sdk.js?client_id='.$this->client_id.'"></script>
    <script>

        const button = BOG.SmartButton.render(document.querySelector(".bog-smart-button"), {
            text: "მოითხოვე სესხი",
            onClick: () => {
                BOG.Calculator.open({
                    amount: '.$amounth.',
                    bnpl: null,
                    onClose: () => {
                        location.reload();
                    },
                    onRequest: (selected, successCb, closeCb) => {
                        const {
                            amount, month, discount_code
                        } = selected;

                        Object.assign(selected, { "cartid":'.$cartid.'});



                var data = fetch("https://iaia.ge/user/bog_modal/", {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/json",
                    },
                    body: JSON.stringify(selected)
                })
                    .then(response => response.json())
                    .then(data => successCb(data.order_id));
                    //.catch(err => closeCb());

            }

        })
                }
            });  
    </script>
      ';
    }

    public function pay_token()
    {
 
        $this->pay_httpheader[1] .= $this->Basic();
  

      $resoult = IPost::Post(
        $this->pay_link, 
        $this->pay_POSTFIELDS, 
        $this->pay_httpheader);

        if(empty($resoult['access_token']))
        { 
            return ['error'=>$resoult,
            'step'=>'pay_token'
        ];
        }
        return $resoult['access_token'];  
    }

    public function pay_order_POSTFIELDS($decoded)
    {
        $error = '';
        if (!isset($decoded['shop_order_id']))            $error .= 'shop_order_id,';
        if (!isset($decoded['redirect_url']))                $error .= 'redirect_url,';
        if (!isset($decoded['value']))                    $error .= 'value,';
        if (!isset($decoded['array']))                    $error .= 'array,'; 
        if (count($decoded['array']) <= 0)                $error .= 'array,';
     
        if (strlen($error) > 0) { 
          $data = [
            'error' => $error,
            'decoded' => $decoded
          ];
              return $data; 
        }
     
        $shop_order_id = strval($decoded['shop_order_id']);
        $redirect_url = strval($decoded['redirect_url']); 
         $value = strval(number_format($decoded['value'], 2));
       // $value = strval($decoded['value']);
     
        
    
        $data = [
          "intent" => "AUTHORIZE",
          "items" => $decoded['array'],
          "locale" => "ka",
          "shop_order_id" => $shop_order_id,
          "redirect_url" => $redirect_url,
          "show_shop_order_id_on_extract" => true,
          "capture_method" => "AUTOMATIC",
          "purchase_units" => [
            [
              "amount" => [
                "currency_code" => "GEL",
                "value" => $value
              ]
            ]
          ]
        ];

        return $data;
    }

    public function pay_order($decoded)
    {
        $POSTFIELDS = $this->pay_order_POSTFIELDS($decoded); 

        if (!empty($POSTFIELDS['error']))
        return $POSTFIELDS;
        
       
        $token = self::pay_token(); 

        if (!empty($token['error']))
        return ['error'=>$token,
        'step'=>'pay_order'
    ];
  

      $this->pay_order_httpheader[1] .= $token;

     
      $resoult =IPost::Post(
        $this->pay_order_link, 
        $this->j_encode($POSTFIELDS), 
        $this->pay_order_httpheader); 
    $dt = [
      'sending'=>[
        'link'=>$this->pay_order_link,
        'httpheader'=>$this->pay_order_httpheader,
        'POSTFIELDS'=>$POSTFIELDS
      ],
      'resoult'=>$resoult 
    ];
  
    
    if (!isset($dt['resoult']['status']) || $dt['resoult']['status'] != 'CREATED')
    {
        return ['error' => $dt];
    }
      return $dt;
      
    }

    public function pay_order_details($order_id)
    {
    
        $token = self::pay_token(); 

        if (!empty($token['error']))
        return ['error'=>$token,
        'step'=>'pay_order_details'
    ];
  
         $this->pay_order_details_httpheader[1].= $token;
    
         $this->pay_order_details_link .= $order_id; 
         $POSTFIELDS = json_encode(['order_id'=>$order_id]);

        $resoult = IPost::Get(
            $this->pay_order_details_link, 
            $POSTFIELDS, 
            $this->pay_order_details_httpheader);

            $dt = [
                'sending'=>[
                  'link'=>$this->pay_order_details_link,
                  'httpheader'=>$this->pay_order_details_httpheader,
                  'POSTFIELDS'=>$POSTFIELDS
                ],
                'received'=>$resoult 
              ];
            
                return $dt;
    }
}
