<?php
class ConfigPage {
	var $configDefault = array(
		"title" => null,
		"fields" => array(

		),
		"defaultItem" => array(
			"label" => null,
			"type" => "text",
			"config" => array(),
			"default" => "",
			"rules" => array(),
			"htmlAttributes" => array()
		),
		"onRun" => false,
		"viewPath" => array(
			"main" => "ext.Son.super-modules.config-page.views.config-page",
			"field" => "ext.Son.super-modules.config-page.views.config-page-field"
		),
		"cache" => array()
	);

	var $config = array();

	var $form = null;

	public function __construct($config=null){
		if($config){
			$this->config = $config;
		}
		$this->init();
	}

	public function init(){
		if($tempConfig=$this->getConfig()){
			$this->config = $tempConfig;
		}
		$this->config = array_replace_recursive($this->configDefault, $this->config);
		$fields = &$this->config["fields"];
		foreach($fields as $fieldName => &$item){
			$this->processItemDefault($item,$fieldName);			
		}
	}

	public function processItemDefault(&$item,$fieldName=null){
		if($this->itemIsArray($item)){
			$this->processItemDefault($item["itemTemplate"],null);
			$item["dataType"] = "array";
		} else if($this->itemIsObject($item)){
			foreach($item["items"] as $key => &$subItem){
				$this->processItemDefault($subItem,$key);
			}
			$item["dataType"] = "object";
		} else {
			$item = array_replace_recursive($this->config["defaultItem"], $item);
			$item["dataType"] = "field";
		}
		$item["fieldName"] = $fieldName;
		$item["label"] = $this->getLabel($item);
		return $item;
	}

	public function itemIsArray($item){
		return isset($item["type"]) && $item["type"]=="array";
	}

	public function itemIsObject($item){
		return isset($item["type"]) && $item["type"]=="object";
	}

	public function itemIsField($item){
		return $item["type"] != "array";
	}

	public function run(){
		if($onRun = $this->config["onRun"]){
			$onRun($this);
		}
		$action = Input::get("action","html_view");
		switch($action){
			case "html_view":
				return $this->actionHtmlView();
			case "update":
				return $this->actionUpdate();
			default:
				Output::show404();
				return false;
		}
		return true;
	}

	public function actionHtmlView(){
		$fileContent = array();
		try {
			$fileContent = include($this->getConfigPath());
		} catch(Exception $ex){
			$fileContent = array();
		}
		$sAsset = Son::load("SAsset");
		$sAsset->arrayToJs($this->config["fields"],"pageConfigFields");
		$sAsset->arrayToJs($fileContent,"pageConfigValues");
		Util::controller()->render($this->config["viewPath"]["main"],array(
			"configPage" => $this,
			"actionUrl" => Util::urlAppendParams(Yii::app()->request->requestUri,array(
				"action" => "update"
			)),
			"fieldViewPath" => $this->config["viewPath"]["field"],
			"fields" => $this->config["fields"],
			"values" => $fileContent,
			"title" => $this->config["title"]
		));
	}

	public function actionUpdate(){
		$config = Input::post("config");
		if(!$config){
			Output::returnJsonError("Invalid setting");
		} else {
			//print_r($config);
			$configStr = var_export($config,true);
			$configFileContent = "<?php return $configStr;";
			file_put_contents($this->getConfigPath(), $configFileContent);
			$now = time();
			foreach($this->config["cache"] as $item){
				if($item===true){
					CacheHelper::clearAllPage();
				}
				CacheHelper::setLastUpdatedTimeOfDependencyKey($item,$now);
			}
			Output::returnJsonSuccess();
		}
	}

	public function getLabel($item){
		$label = ArrayHelper::get($item,"label");
		if(!$label){
			$label = ucwords(str_replace("_"," ",$item["fieldName"]));
		}
		return $label;
	}

	public function getConfigPath(){
		return Yii::getPathOfAlias($this->config["src"]) . ".php";
	}

	// abstract and virtual functions

	protected function getConfig() { return null; }

	var $baseUrl = false;
	public function getBaseUrl(){
		if($this->baseUrl===false){
			$url = $this->config["baseUrl"];
			if(is_callable($url))
				$url = $url();
			$this->baseUrl = $url;
		}
		return $this->baseUrl;
	}
}