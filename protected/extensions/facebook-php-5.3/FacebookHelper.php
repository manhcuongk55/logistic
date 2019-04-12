<?php
class FacebookHelper {
	private static $instance = null;
	public static function getInstance(){
		if(!self::$instance){
			Son::loadFile("ext.facebook.facebook");
			$config = Util::param2("accounts","facebook");
			self::$instance = new Facebook($config);
		}
		return self::$instance;
	}

	public static function clearOpenGraphCache($url) {
		$vars = array('id' => $url, 'scrape' => 'true');
		$body = http_build_query($vars);

		$fp = fsockopen('ssl://graph.facebook.com', 443);
		fwrite($fp, "POST / HTTP/1.1\r\n");
		fwrite($fp, "Host: graph.facebook.com\r\n");
		fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
		fwrite($fp, "Content-Length: ".strlen($body)."\r\n");
		fwrite($fp, "Connection: close\r\n");
		fwrite($fp, "\r\n");
		fwrite($fp, $body);
		fclose($fp);
	}
}