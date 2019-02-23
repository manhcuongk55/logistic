<?php
require("SpeedSMSAPI.php");
require("SpeedVoiceAPI.php");
require("TwoFactorAPI.php");

class SMSSender {
    var $accessToken;
    var $brandName;

    function __construct() {
        $config = Util::param2("accounts","sms");
        $this->accessToken = $config["accessToken"];
        $this->brandName = $config["brandName"];
    }

    public function getUserInfo() {
        $sms = new SpeedSMSAPI();
        $sms->setAccessToken($this->accessToken);
        $userInfo = $sms->getUserInfo();
        var_dump($userInfo);
    }

    public function sendSMS($phones, $content) {
        // $phone = $phones[0];
        // Util::log("SendSMS: $phone: $content");
        // return true;
        $sms = new SpeedSMSAPI();
        $sms->setAccessToken($this->accessToken);
        $result = $sms->sendSMS($phones, $content, SpeedSMSAPI::SMS_TYPE_BRANDNAME, $this->brandName, 1);
        return intval($result["code"]) == 0;
    }

    public function sendVoiceOTP($phone, $content) {
        $voice = new SpeedVoiceAPI();
        $voice->setAccessToken($this->accessToken);
        $result = $voice->sendVoiceOTP($phone, $content);
        return intval($result["code"]) == 0;
    }

    public function createPIN($phone, $content, $appId) {
        $twoFA = new TwoFactorAPI();
        $twoFA->setAccessToken($this->accessToken);
        $result = $twoFA->pinCreate($phone, $content, $appId);
        return $result;

    }

    public function verifyPIN($phone, $pinCode, $appId) {
        $twoFA = new TwoFactorAPI();
        $twoFA->setAccessToken($this->accessToken);
        $result = $twoFA->pinVerify($phone, $pinCode, $appId);
        return intval($result["code"]) == 0;
    }
}