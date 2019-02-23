<?php
class Output {
    static $jsonErrorCode;

	public static function end($str=null){
		if($str)
			echo $str;
		die();
	}

	public static function arr($arr){
		print_r($arr);
		self::end();
	}

	public static function renderCacheDebug($key,$timestamp,$hasCache){
		if(Util::param2("common","cacheEnabled")){
			$httpCacheHeader = "Cache-HTTP: " . ($hasCache ? 1 : 0);
			header($httpCacheHeader);
			if($timestamp==0)
				$timestamp = time();
			self::httpCache($key,$timestamp);

		}
	}

	public static function httpCache($key,$timestamp){
		$cacheTime = 3600;
		$tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
		$expired = gmdate('D, d M Y H:i:s ', $timestamp + $cacheTime) . 'GMT';
		$etag = md5($key . $timestamp);

		$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
		$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
		
		if (
			(($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
			($if_modified_since && $if_modified_since == $tsstring)
		)
		{
			header("Cache-Control: max-age=2592000");
			header("Expires: $expired");
			header("ETag: $etag");
			header('HTTP/1.1 304 Not Modified');
			exit();
			return true;
		}
		else
		{
			header("Cache-Control: max-age=2592000");
			header("Last-Modified: $tsstring");
			header("Expires: $expired");
			header("ETag: $etag");
			return false;
		}
	}
	
	public static function returnJson($obj){
		//var_dump($obj); die();
		//unset($obj["data"]["data_html"]);
		$json = json_encode($obj);
		echo $json;
		//self::end();
	}

	public static function returnJsonSuccess($data=null,$message="Ok"){
		$arr = array(
			"error" => 0,
			"message" => $message
		);
		if($data!==null)
			$arr["data"] = $data;
		self::returnJson($arr);
	}

	public static function setJsonErrorCode($errorCode){
		self::$jsonErrorCode = $errorCode;
	}

	public static function returnJsonError($message="Oops..! Something wrong happened!",$statusCode=400,$errorCode=null,$data=null){
		if($errorCode===null){
			$errorCode = self::$jsonErrorCode;
		}
		$arr = array(
			"error" => $errorCode,
			"message" => $message
		);
		if($data!=null)
			$arr["data"] = $data;
		//http_response_code($statusCode);
		self::returnJson($arr);
	}

	public static function returnJsonErrorData($message="Oops..! Something wrong happened!",$data=null,$statusCode=400,$errorCode=1){
		$arr = array(
			"error" => $errorCode,
			"message" => $message
		);
		if($data!=null)
			$arr["data"] = $data;
		http_response_code($statusCode);
		self::returnJson($arr);
	}

	public static function returnJsonPermissionDenied($errorMessage="The page you requested cannot be found!",$errorCode=2){
		self::returnJsonError($errorMessage,403,$errorCode);
	}

	public static function returnHtml($html){
		self::returnJsonSuccess($html);
	}

	public static function returnHtmlView($view,$data=null){
		$html = Util::controller()->renderPartial($view,$data,true);
		self::returnHtml($html);
	}

	public static function showError($errorMessage="Oops..! Something wrong happened!",$statusCode=400){
		throw new CHttpException($statusCode,$errorMessage);
	}
	
	public static function show404($errorMessage="The page you requested cannot be found!"){
		self::showError($errorMessage,404);
	}

	public static function showPermissionDenied($errorMessage="Permission denied"){
		self::showError($errorMessage,403);
	}

	public static function redirect($url){
		Util::controller()->redirect($url);
	}
	
	public static function downloadCsv($data,$fileName="data",$delimiter=","){
		header('Content-Type: application/csv');
		$fileName = "$fileName.csv";
		header('Content-Disposition: attachment; filename="'.$fileName.'";');
		// open the "output" stream
		// see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
		$f = fopen('php://output', 'w');
		foreach ($data as $line) {
			fputcsv($f, $line, $delimiter);
		}
	}
	
	public static function downloadExcel($data,$fileName="data"){
		//var_dump($data); die();
		Yii::import('application.extensions.JPhpExcel.JPhpExcel');
		$xls = new JPhpExcel('UTF-8', false, $fileName);
		$xls->addArray($data);
		$xls->generateXML($fileName);
	}
}

Output::$jsonErrorCode = 1;