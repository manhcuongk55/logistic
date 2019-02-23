<?php
function UpdateOrder($transaction_info,$order_code,$payment_id,$payment_type,$secure_code){
	Util::log("CALL UpdateOrder","NganLuong");
	NganLuongHelper::UpdateOrder($transaction_info,$order_code,$payment_id,$payment_type,$secure_code);
}

function RefundOrder($transaction_info,$order_code,$payment_id,$refund_payment_id,$payment_type,$secure_code)
{
	Util::log("CALL RefundOrder","NganLuong");
	NganLuongHelper::RefundOrder($transaction_info,$order_code,$payment_id,$refund_payment_id,$payment_type,$secure_code);
}

class NganLuongHelper {
	const NganLuong = "NganLuong";
	static $databaseUpdateOrderCallback, $databaseUpdateRefundCallback, $errorOrderCallback, $errorRefundCallback;
	public static function verify($databaseUpdateOrderCallback,$databaseUpdateRefundCallback=null,$errorOrderCallback=null,$errorRefundCallback=null){
		self::$databaseUpdateOrderCallback = $databaseUpdateOrderCallback;
		self::$databaseUpdateRefundCallback = $databaseUpdateRefundCallback;
		self::$errorOrderCallback = $errorOrderCallback;
		self::$errorRefundCallback = $errorRefundCallback;

		require_once('nusoap.php');
		
		// Khai bao chung WebService
		$server = new nusoap_server();
		$server->configureWSDL('WS_WITH_SMS','NS',Yii::app()->controller->createAbsoluteUrl("/payment/nganluong_confirm"));
		$server->wsdl->schemaTargetNamespace='NS';
		// Khai bao cac Function
		$server->register('UpdateOrder',array('transaction_info'=>'xsd:string','order_code'=>'xsd:string','payment_id'=>'xsd:int','payment_type'=>'xsd:int','secure_code'=>'xsd:string'),array('result'=>'xsd:int'),'NS',Yii::app()->controller->createAbsoluteUrl("/site/nganluong"));
		$server->register('RefundOrder',array('transaction_info'=>'xsd:string','order_code'=>'xsd:string','payment_id'=>'xsd:int','refund_payment_id'=>'xsd:int','payment_type'=>'xsd:int','secure_code'=>'xsd:string'),array('result'=>'xsd:int'),'NS',Yii::app()->controller->createAbsoluteUrl("/site/nganluong_confirm"));
		$POST_DATA = file_get_contents("php://input");
        Util::log("--BEGIN SERVICE","NganLuong");
	    $server->service($POST_DATA);
        Util::log("--END SERVICE","NganLuong");
	}

	public static function UpdateOrder($transaction_info,$order_code,$payment_id,$payment_type,$secure_code)
	{            
        Util::log("Update Order : Payment Type => $payment_type","NganLuong");
        $config = Util::param2("accounts","nganluong");
	    $secure_pass = $config["secure_pass"];
		// Kiểm tra chuỗi bảo mật
	   	$secure_code_new = md5($transaction_info.' '.$order_code.' '.$payment_id.' '.$payment_type.' '.$secure_pass);
		if($secure_code_new != $secure_code)
		{
			if(self::$errorOrderCallback!=null){
				call_user_func(self::$errorOrderCallback);
			}
			return -1; // Sai mã bảo mật
		}
		else // Thanh toán thành công
		{	
			Util::log("PAYMENT TYPE => $payment_type","NganLuong");
			// Trường hợp là thanh toán tạm giữ. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
			if($payment_type == 2)
			{
				// Lập trình thông báo thành công và cập nhật hóa đơn
				call_user_func(self::$databaseUpdateOrderCallback,$transaction_info,$order_code,$payment_id,$payment_type);
			}
			// Trường hợp thanh toán ngay. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
			elseif($payment_type == 1)
			{		
				// Lập trình thông báo thành công và cập nhật hóa đơn
				call_user_func(self::$databaseUpdateOrderCallback,$transaction_info,$order_code,$payment_id,$payment_type);
			}
		}
	}

	public static function RefundOrder($transaction_info,$order_code,$payment_id,$refund_payment_id,$payment_type,$secure_code)
	{                    
        Util::log("Refund Order : Payment Type => $payment_type","NganLuong");   
	    $config = Util::param2("accounts","nganluong");
	    $secure_pass = $config["secure_pass"];
		// Kiểm tra chuỗi bảo mật
	   	$secure_code_new = md5($transaction_info.' '.$order_code.' '.$payment_id.' '.$refund_payment_id.' '.$secure_pass);
		if($secure_code_new != $secure_code)
		{
			if(self::$errorRefundCallback!=null){
				call_user_func(self::$errorRefundCallback);
			}
			return -1; // Sai mã bảo mật
		}	
		else // Trường hợp hòan trả thành công
		{
			// Lập trình thông báo hoàn trả thành công và cập nhật hóa đơn		
			if(self::$databaseUpdateRefundCallback!=null){
				call_user_func(self::$databaseUpdateRefundCallback,$transaction_info,$order_code,$payment_id,$refund_payment_id,$payment_type);
			}	
		}
	}

	public static function verifyInSuccessUrl($callback=null){
		require_once("NL_Checkout.php");
		//Lấy kết quả trả về từ ngân lượng
	    
		//Lấy thông tin giao dịch
		$transaction_info=$_GET["transaction_info"];
		//Lấy mã đơn hàng 
		$order_code=$_GET["order_code"];
		//Lấy tổng số tiền thanh toán tại ngân lượng 
		$price=$_GET["price"];
		//Lấy mã giao dịch thanh toán tại ngân lượng
		$payment_id=$_GET["payment_id"];
		//Lấy loại giao dịch tại ngân lượng (1=thanh toán ngay ,2=thanh toán tạm giữ)
		$payment_type=$_GET["payment_type"];
		//Lấy thông tin chi tiết về lỗi trong quá trình giao dịch
		$error_text=$_GET["error_text"];
		//Lấy mã kiểm tra tính hợp lệ của đầu vào 
		$secure_code=$_GET["secure_code"];
		
		//Xử lí đầu vào
		
		$nl = new NL_Checkout();
		$check = $nl->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);

		if($callback!=null)
			$callback($transaction_info,$order_code,$price,$payment_id,$payment_type,$error_text,$check);

		return $check;
	}

	public static function getUrl($order_code,$price,$transaction_info=""){
		require_once("NL_Checkout.php");
		$nl = new NL_Checkout();
		$config = Util::param2("accounts","nganluong");
		$account = $config["account"];
		$url = $nl->buildCheckoutUrl(Yii::app()->controller->createAbsoluteUrl("/payment/nganluong_success"),$account,$transaction_info,$order_code,$price);
		return $url;
	}
}