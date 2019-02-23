<?php
abstract class ApiBaseController extends Controller {
	protected $defaultConfig = array(
		"folderName" => "api",
		"apiList" => array(

		),
		"view" => "ext.Son.super-modules.api.views.api-page",
		"layout" => "ext.Son.super-modules.api.views.layout",
		"menuView" => "ext.Son.html.views.menu.menu-bootstrap-list-group",
		"description" => "API for mobile"
	);

	protected $apiConfigDefault = array(
		"authenticate" => false,
		"description" => "",
		"method" => "post"
	);

	protected $apiInputConfigDefault = array(
		"type" => "text",
		"willRead" => true,
		"description" => ""
	);

	protected $config = array();
	protected $user = null;
	protected $currentApiConfig = null;
	public $menu = null;

	public function init(){
		parent::init();
		$this->config = array_replace_recursive($this->defaultConfig, $this->config);
		$this->layout = $this->config["layout"];
	}

	protected function beforeAction($action){
		if(!parent::beforeAction($action))
			return false;
		$this->preprocessList();
		return true;
	}

	public function missingAction($actionId){
		if(in_array($actionId,$this->config["apiList"])){
			$this->actionApi($actionId);
		} elseif(isset($this->config["apiList"][$actionId])){
			$this->actionApi($actionId,$this->config["apiList"][$actionId]);
		} else {
			parent::missingAction($actionId);
		}
	}

	public function actionIndex(){
		$this->render($this->config["view"],array(
			"api" => null,
			"list" => $this->config["apiList"]
		));
	}

	protected function actionApi($apiName,$apiLabel=null){
		if($apiLabel==null){
			$apiLabel = $this->convertToLabel($apiName);
		}
		list($apiConfig,$apiFunction) = include_once(dirname($this->getCurrentFile()) . "/" . $this->config["folderName"] . "/" . $apiName . ".php");
		$this->completeApiConfig($apiConfig);
		$apiConfig["label"] = $apiLabel;
		$apiConfig["name"] = $apiName;
		$this->currentApiConfig = $apiConfig;
		if($this->isDebug($apiConfig)){
			$this->preprocessList();
			$this->render($this->config["view"],array(
				"api" => $apiConfig
			));
		} else {
			$apiFunction($this);
		}
	}

	protected function preprocessList(){
		$newApiList = array();
		foreach($this->config["apiList"] as $key => $item){
			if(is_numeric($key)){
				$newApiList[$item] = $this->convertToLabel($item);
			} else {
				$newApiList[$key] = $item;
			}
		}
		$this->config["apiList"] = $newApiList;
	}

	protected function completeApiConfig(&$apiConfig){
		$apiConfig = array_merge($this->apiConfigDefault,$apiConfig);
		ArrayHelper::processItemDefaultAssoc($apiConfig["inputs"],$this->apiInputConfigDefault);
	}

	protected function isDebug($apiConfig){
		return Input::get("debug",false);
	}

	public function readInput(){
		if($authenticate = ArrayHelper::get($this->currentApiConfig,"authenticate",false,null,true)){
			$authenticateMethods = $this->getAuthenticateMethods();
			$authenticateFunction = $authenticateMethods[$authenticate];
			if(!$authenticateFunction())
				return;
		}
		$arr = array();
		foreach($this->currentApiConfig["inputs"] as $inputName => $config){
			if(!$config["willRead"]){
				continue;
			}
			if($this->currentApiConfig["method"]=="get"){
				// GET
				$arr[] = Input::get($inputName,$config,"json");
			} else {
				// POST
				$arr[] = Input::post($inputName,$config,"json");
			}

		}
		return $arr;
	}

	public function getBaseUrl($path=""){
		return $this->createAbsoluteUrl($path);
	}

	protected function convertToLabel($apiName){
		return ucwords(str_replace("_", " ", $apiName));
	}

	protected function getAuthenticateMethods(){
		return array();
	}

	public function render($view,$data=null,$return=false){
		
		if($this->currentApiConfig){
			$this->getMenu()->setActiveItemId($this->currentApiConfig["name"]);
		}
		return parent::render($view,$data,$return);
	}

	public function getMenu(){
		if($this->menu==null){
			$config = array(
				"items" => array(),
				"view" => $this->config["menuView"]
			);
			foreach($this->config["apiList"] as $key => $label){
				$config["items"][] = array(
					"id" => $key,
					"content" => $label,
					"url" => $this->getBaseUrl($key) . "?debug=1"
				);
			}
			$this->menu = new SMenu($config);
		}
		return $this->menu;
	}

	abstract protected function getCurrentFile();

}