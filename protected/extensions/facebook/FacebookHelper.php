<?php
class FacebookHelper {
	private static $instance = null;
	public static function getInstance(){
		if(!self::$instance){
			Son::loadFile("ext.facebook.Facebook.autoload",true,true);
			$config = Util::param2("accounts","facebook");
			@session_start();
			self::$instance = new Facebook\Facebook($config);
		}
		return self::$instance;
	}
}