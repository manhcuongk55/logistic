<?php
/**
 * Created by PhpStorm.
 * User: duongdx
 * Date: 7/18/17
 * Time: 11:27 PM
 */

class SpeedVoiceAPI {
    private $ROOT_URL = "https://api.speedsms.vn/index.php";
    private $accessToken ;

    public function setAccessToken($accessToken){
        $this->accessToken = $accessToken;
    }

    public function sendVoiceOTP($to, $content) {
        $json = json_encode(array('to' => $to, 'content' => $content));

        $headers = array('Content-type: application/json');

        $url = $this->ROOT_URL.'/voice/otp';
        $http = curl_init($url);
        curl_setopt($http, CURLOPT_HEADER, false);
        curl_setopt($http, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($http, CURLOPT_POSTFIELDS, $json);
        curl_setopt($http, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($http, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($http, CURLOPT_VERBOSE, 0);
        curl_setopt($http, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($http, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($http, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($http, CURLOPT_USERPWD, $this->accessToken.':x');
        $result = curl_exec($http);
        if(curl_errno($http))
        {
            return null;
        }
        else
        {
            curl_close($http);
            return json_decode($result, true);
        }
    }
} 