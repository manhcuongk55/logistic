<?php
class DdosGateway {
	const SESSION_NAME = "ddos_gateway";
	const SESSION_UUID_NAME = "ddos_gateway_uuid";
	const LIVE_TIME = 18000; // 5 hours

	public function __construct(){
		session_start();
	}

	public function handleSessionRequest(){
		if(!isset($_POST["session_uuid"]) || !isset($_POST["clicked"]) || !isset($_SESSION[self::SESSION_UUID_NAME]))
			return false;
		$sessionUuid = $_POST["session_uuid"];
		$savedSessionUuid = $_SESSION[self::SESSION_UUID_NAME];
		if($sessionUuid != $savedSessionUuid){
			return false;
		}
		unset($_SESSION[self::SESSION_UUID_NAME]);
		$_SESSION[self::SESSION_NAME] = array(
			"time" => time()
		);
		return $_POST["url"];
	}

	public function checkExistSession(){
		if(!isset($_SESSION[self::SESSION_NAME]))
			return false;
		$arr = $_SESSION[self::SESSION_NAME];
		if(time() > $arr["time"] + self::LIVE_TIME){
			return false;
		}
		return true;
	}

	public function saveSessionUuid(){
		if(isset($_SESSION[self::SESSION_UUID_NAME]))
			return $_SESSION[self::SESSION_UUID_NAME];
	    $uuid = $this->generateUUID();
	    $_SESSION[self::SESSION_UUID_NAME] = $uuid;
	    return $uuid;
	}

	public function generateUUID(){
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        // 32 bits for "time_low"
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

	        // 16 bits for "time_mid"
	        mt_rand( 0, 0xffff ),

	        // 16 bits for "time_hi_and_version",
	        // four most significant bits holds version number 4
	        mt_rand( 0, 0x0fff ) | 0x4000,

	        // 16 bits, 8 bits for "clk_seq_hi_res",
	        // 8 bits for "clk_seq_low",
	        // two most significant bits holds zero and one for variant DCE1.1
	        mt_rand( 0, 0x3fff ) | 0x8000,

	        // 48 bits for "node"
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	    );
	}
}