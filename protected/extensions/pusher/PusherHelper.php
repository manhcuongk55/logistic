<?php
class PusherHelper {
	private static $instance = null;
	public static function getInstance(){
		if(!self::$instance){
			Son::loadFile("ext.pusher.Pusher",true,true);
			$config = Util::param2("accounts","pusher");
			self::$instance = new Pusher($config["key"],$config["secret"],$config["app_id"],array(
				"cluster" => $config["cluster"]
			));
		}
		return self::$instance;
	}
}