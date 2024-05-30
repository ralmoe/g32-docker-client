<?php

class OwgApi{

    private $username;
    private $password;

    private $helper;

    private static $url = 'https://mobile-api.ottowildeapp.com/login';
    private static $contentType = 'Content-Type: application/json; charset=utf-8';

    public function __construct($username, $password, $helper)
    {
        $this->username = $username;
        $this->password = $password;
        $this->helper = $helper;
    }

    public function getAccessToken(){
        $jsonResult = $this->authenticate();
        $authResult = json_decode($jsonResult,true);

        if(json_last_error() === JSON_ERROR_NONE){
            if(isset($authResult['data']['accessToken'])){
                return($authResult['data']['accessToken']);
            }else{
                $this->helper->dLog('ERROR: Could not authenticate: ','ERROR');
            }
        }else{
            $this->helper->dLog('invalid json-result: '.$jsonResult, 'ERROR');
        }
        exit;
    }

    public function authenticate(){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getPostData());
        curl_setopt($curl, CURLOPT_HTTPHEADER, [self::$contentType]);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function getPostData(){
        $data = [
            'email' => $this->username,
            'password' => $this->password
        ];

        return(json_encode($data));
    }
}
