<?php
class BaoKimHelper {
	public static function verify($databaseUpdateCallback,$databaseUpdateHoldCallback=null,$errorCallback=null){
        $config = Util::param2("accounts","baokim");
		$req = '';
		foreach ( $_POST as $key => $value ) {
			$value = urlencode ( stripslashes ( $value ) );
			$req .= "&$key=$value";
		}
        $url = $config["url"] . 'bpn/verify';
        Util::log("VERIFYING via $url $req...","BaoKim");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);

		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		$error = curl_error($ch);

        Util::log("RESULT => " . $result,"BaoKim");

		$hasError = true;

		if($result != '' && strstr($result,'VERIFIED') && $status==200){
			//thuc hien update hoa don
			
			$order_id = $_POST['order_id'];
			$transaction_id = $_POST['transaction_id'];
			$transaction_status = $_POST['transaction_status'];
			$total_amount = $_POST['total_amount'];
			
			//Mot so thong tin khach hang khac
			$customer_name = @$_POST['customer_name'];
			$customer_address = @$_POST['customer_address'];
			//...
			
            Util::log("STATUS => " . $transaction_status,"BaoKim");

			//kiem tra trang thai giao dich
			if ($transaction_status == 4){//Trang thai giao dich =4 la thanh toan truc tiep = 13 la thanh toan an toan
				//xac nhan la da thanh toan thanh cong don hang
				// Thao tac voi co so du lieu
				$databaseUpdateCallback($order_id,$transaction_id,$transaction_status,$total_amount,$customer_name);
			}
            elseif($transaction_status == 13) {
                if($databaseUpdateHoldCallback!=null){
                    $databaseUpdateHoldCallback($order_id,$transaction_id,$transaction_status,$total_amount,$customer_name);
                }
            }
			
			/**
			 * Neu khong thi bo qua
			 */
		} else {
			if($errorCallback!=null)
				$errorCallback($error);
		}
		if ($error){
		}
	}

	public static function verifyInSuccessUrl($callback=null){
		if($callback!=null){
			$callback();
		}
	}

	public static function verifyInCancelUrl($callback=null){
		if($callback!=null){
			$callback();
		}
	}

	public static function getUrl($order_id,$price,$transaction_info=""){
		require_once("BaoKimPayment.php");
		$bk = new BaoKimPayment();
		$config = Util::param2("accounts","baokim");
		$business = $config["account"];
		$total_amount = $price;
		$shipping_fee = 0;
		$tax_fee = 0;
		$order_description = $transaction_info;
		$url_success = Yii::app()->controller->createAbsoluteUrl("/payment/baokim_success");
		$url_cancel = Yii::app()->controller->createAbsoluteUrl("/payment/baokim_cancel");
		$url_detail = Yii::app()->controller->createAbsoluteUrl("/user/home/money");
		$url = $bk->createRequestUrl(array(
			"order_id" => $order_id,
			"business" => $business,
			"total_amount" => $total_amount,
			"shipping_fee" => $shipping_fee,
			"tax_fee" => $tax_fee,
			"order_description" => $order_description,
			"url_success" => $url_success,
			"url_cancel" => $url_cancel,
			"url_detail" => $url_detail
		));
		//echo $url; die();
		return $url;
	}

}