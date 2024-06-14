<?php

class OwgApi{

    private $username;
    private $password;

    private $helper;

    private static $loginUrl = 'https://mobile-api.ottowildeapp.com/login';
    private static $dataUrl = 'https://mobile-api.ottowildeapp.com/v2/grills';
    private static $contentType = 'Content-Type: application/json; charset=utf-8';

    public function __construct($username, $password, $helper)
    {
        $this->username = $username;
        $this->password = $password;
        $this->helper = $helper;
    }

    public function getAccessToken(){
        $jsonResult = $this->loginRequest();
        $authResult = json_decode($jsonResult,true);

        if(json_last_error() === JSON_ERROR_NONE){
            if(isset($authResult['data']['accessToken'])){
                return($authResult['data']['accessToken']);
            }else{
                $this->helper->dLog('ERROR: Could not authenticate: ','ERROR');
            }
        }else{
            $this->helper->dLog('ERROR ACCESS-TOKEN: invalid json-result: '.$jsonResult, 'ERROR');
        }
        exit;
    }

    public function getPopId($accessToken){
        $jsonResult = $this->grillDataRequest($accessToken);
        $grillData = json_decode($jsonResult,true);

        if(json_last_error() === JSON_ERROR_NONE){
            if(isset($grillData['data'][0]['popKey'])){
                return($grillData['data'][0]['popKey']);
            }else{
                $this->helper->dLog('ERROR: Could read grilldata: '.$jsonResult,'ERROR');
            }
        }else{
            $this->helper->dLog('ERROR POP-ID: invalid json-result: '.$jsonResult, 'ERROR');
        }
        exit;
    }

    private function LoginRequest(){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::$loginUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getPostData());
        curl_setopt($curl, CURLOPT_HTTPHEADER, [self::$contentType]);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    private function grillDataRequest($accessToken){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::$dataUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            self::$contentType,
            $this->getAuthHeaderData($accessToken)
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function getPostData(){
        $data = [
            'email' => $this->username,
            'password' => $this->password
        ];

        return json_encode($data);
    }

    private function getAuthHeaderData($accessToken){
        return sprintf('authorization: %s',$accessToken);
    }
}
