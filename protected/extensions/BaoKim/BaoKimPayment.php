<?php
class BaoKimPayment
{

	private $baokim_url = 'https://www.baokim.vn/payment/customize_payment/order';

	// Mã merchante site 
	private $merchant_id = '100001';	// Biến này được baokim.vn cung cấp khi bạn đăng ký merchant site

	// Mật khẩu bảo mật
	private $secure_pass = 'DED01D1CFF3BE2767196FF0080F6DB6D5C'; // Biến này được baokim.vn cung cấp khi bạn đăng ký merchant site

	public function __construct(){
		$baokim = Util::param2("accounts","baokim");
		$this->baokim_url = $baokim["url"] . "payment/order/version11";
		$this->merchant_id = $baokim["web_id"];
		$this->secure_pass = $baokim["secure_pass"];
	}

	/**
	 * Cấu hình phương thức thanh toán với các tham số
	 * E-mail Bảo Kim - E-mail tài khoản bạn đăng ký với BaoKim.vn.
	 * Merchant ID - là “mã website” được Baokim cấp khi bạn đăng ký tích hợp.
	 * Mã bảo mật - là “mật khẩu” được Baokim cấp khi bạn đăng ký tích hợp
	 * Vd : 12f31c74fgd002b1
	 * Server Bảo Kim
	 * Trang ​Kiểm thử - server để test thử phương thức thanh. .toán
	 * Trang thực tế - Server thực tế thực hiện thanh toán.
	 * https://www.baokim.vn/payment/order/version11' => ('Trang thực tế'),
	 * http://kiemthu.baokim.vn/payment/order/version11' => ('Trang kiểm thử')
	 * Chọn Save configuration để áp dụng thay đổi
	 * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
	 * @param $order_id                Mã đơn hàng
	 * @param $business            Email tài khoản người bán
	 * @param $total_amount            Giá trị đơn hàng
	 * @param $shipping_fee            Phí vận chuyển
	 * @param $tax_fee                Thuế
	 * @param $order_description    Mô tả đơn hàng
	 * @param $url_success            Url trả về khi thanh toán thành công
	 * @param $url_cancel            Url trả về khi hủy thanh toán
	 * @param $url_detail            Url chi tiết đơn hàng
	 * @param null $payer_name
	 * @param null $payer_email
	 * @param null $payer_phone_no
	 * @param null $shipping_address
	 * @return url cần tạo
	 */
	public function createRequestUrl($data)
	{
		$defaultParams = array(
			"shipping_fee" => 0,
			"tax_fee" => 0,
			"order_description" => "",
			"url_success" => "",
			"url_cancel" => "",
			"url_detail" => "",
			"payer_name" => "",
			"payer_email" => "",
			"payer_phone_no" => "",
			"shipping_address" => "",
			"currency" => "VND"
		);
		$data = array_merge($defaultParams,$data);
		// Mảng các tham số chuyển tới baokim.vn
		$params = array(
			'merchant_id'		=>	strval($this->merchant_id),
			'order_id'			=>	strval($data["order_id"]),
			'business'			=>	strval($data["business"]),
			'total_amount'		=>	strval($data["total_amount"]),
			'shipping_fee'		=>  strval($data["shipping_fee"]),
			'tax_fee'			=>  strval($data["tax_fee"]),
			'order_description'	=>	strval($data["order_description"]),
			'url_success'		=>	strtolower($data["url_success"]),
			'url_cancel'		=>	strtolower($data["url_cancel"]),
			'url_detail'		=>	strtolower($data["url_detail"]),
			'payer_name'		=>  strval($data['payer_name']),
			'payer_email'		=> 	strval($data['payer_email']),
			'payer_phone_no'	=> 	strval($data['payer_phone_no']),
			'shipping_address'  =>  strval($data['shipping_address']),
			'currency' 			=>  strval($data["currency"]),

		);
		ksort($params);

		$params['checksum']=hash_hmac('SHA1',implode('',$params),$this->secure_pass);

		//Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
		$redirect_url = $this->baokim_url;
		if (strpos($redirect_url, '?') === false)
		{
			$redirect_url .= '?';
		}
		else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';
		}

		// Tạo đoạn url chứa tham số
		$url_params = '';
		foreach ($params as $key=>$value)
		{
			if ($url_params == '')
				$url_params .= $key . '=' . urlencode($value);
			else
				$url_params .= '&' . $key . '=' . urlencode($value);
		}
		return $redirect_url.$url_params;
	}

	/**
	 * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
	 * @param $url_params chứa tham số trả về trên url
	 * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
	 */
	public function verifyResponseUrl($url_params = array())
	{
		if(empty($url_params['checksum'])){
			echo "invalid parameters: checksum is missing";
			return FALSE;
		}

		$checksum = $url_params['checksum'];
		unset($url_params['checksum']);

		ksort($url_params);

		if(strcasecmp($checksum,hash_hmac('SHA1',implode('',$url_params),$this->secure_pass))===0)
			return TRUE;
		else
			return FALSE;
	}
}
?>