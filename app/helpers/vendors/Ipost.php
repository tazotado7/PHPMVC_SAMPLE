<?php
namespace taladashvili\vendor\ipost;
class IPost
{
    static function Post($link,$POSTFIELDS,$httpheader)
    {
        return self::request($link, $POSTFIELDS, $httpheader, 'POST');
    }

    static function Get($link,$POSTFIELDS,$httpheader)
    {
        return self::request($link, $POSTFIELDS, $httpheader, 'GET');
    }

    private function request($link,$POSTFIELDS,$httpheader,$postget)
    {
        $curl = curl_init(); 
        curl_setopt_array($curl, array( 
            CURLOPT_URL => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $postget, 
            CURLOPT_POSTFIELDS => $POSTFIELDS,
            CURLOPT_HTTPHEADER => $httpheader,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        return $response; 
    }
}
