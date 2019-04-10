<?php
class NganLuongMobiCardHelper {
	public static function makeTransaction($pinCard,$cardSerial,$typeCard,$orderId,$customerName,$customerEmail,$customerPhone){
		$folder = dirname(__FILE__) . "/";
		include($folder . "MobiCard.php");
		$call = new MobiCard();
		//$rs = self::getTestResult(); return array(true,"",$rs);
		$rs = $call->CardPay($pinCard,$cardSerial,$typeCard,$orderId,$customerName,$customerPhone,$customerEmail);
		if($rs->error_code == '00') {				
			return array(true,"",$rs);
		}
		else {
			return array(false,$rs->error_message,$rs);
		}
	}

	public static function getTestResult(){
		$rs = new Result();
		$rs->error_code = "00";
		$rs->merchant_id = "A1";
		$rs->merchant_account = "A2";				
		$rs->pin_card = "A3";
		$rs->card_serial = "A4";
		$rs->type_card = "A5";
		$rs->order_id = "A6";
		$rs->client_fullname = "A7";
		$rs->client_email = "A8";
		$rs->client_mobile = "A9";
		$rs->card_amount = "50000";
		$rs->amount = "A11";
		$rs->transaction_id = "A12";
		$rs->error_message = "";
		return $rs;
	}
}