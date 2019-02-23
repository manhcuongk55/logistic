<?php
class GoogleHelper {
	const CLIENT_ID = '733602967523-lnlgg8mm36n6ndtbniulh88iqp23s7b4.apps.googleusercontent.com';
	const CLIENT_SECRET = 'HVvIHJkATK7P93OAx3ih0VEH';
	const APPLICATION_NAME = "Google+ PHP Quickstart";

	public $client = null;

	public function __construct(){
		$this->init();
	}

	public function init(){
		/*set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ .'/vendor/google/apiclient/src');
		$loader = require_once __DIR__.'/vendor/autoload.php';
		Yii::$classMap = $loader->getClassMap();

		require_once __DIR__ . "/vendor/google/apiclient/vendor/autoload.php";

		$GLOBALS["caBundlePath"] = Son::getFilePath("ext.GoogleHelper.keys.ca-bundle","crt");*/
		Son::loadFile("ext.GoogleHelper2.includes.Google_Client");
		Son::loadFile("ext.GoogleHelper2.includes.contrib.Google_Oauth2Service");
		$this->setUpClient();
	}

	public function setUpClient(){
		$this->client = new Google_Client();
		$this->client->setApplicationName(self::APPLICATION_NAME);
		$this->client->setClientId(self::CLIENT_ID);
		$this->client->setClientSecret(self::CLIENT_SECRET);
		$this->client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
		$this->client->setRedirectUri(Util::controller()->createAbsoluteUrl("/site/google_login_callback"));
	}

	public function redirect(){
		$url = $this->client->createAuthUrl();
		Util::controller()->redirect($url);
	}

	public function loginCallback(){
		//$plus = new Google_PlusService($this->client);
		//$this->client->revokeToken();
		$oauth2 = new Google_Oauth2Service($this->client);
		$code = Input::get("code");
		if(!$code){
			echo "invalid request";
			die();
		}
		//var_dump($code); die();
		$this->client->authenticate($code);
		$accessToken = $this->client->getAccessToken();
 		$userData = $oauth2->userinfo->get();
 		//$plus = new Google_Service_Plus($this->client);
 		//$me = $plus->people->get('me');
 		//print_r($me); die();
 		//print_r($userData); die();
 		return array($accessToken,$userData);
	}
}