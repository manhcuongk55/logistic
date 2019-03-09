<?php
Yii::import("ext.Son.super-modules.list.*");
Yii::import("ext.Son.super-modules.config-page.*");
abstract class AdminTableBaseController extends Controller {
	var $defaultConfig = array(
		"pageList" => array(),
		"configPageList" => array(),
		"menu" => array(),
		"folderName" => "admin",
		"authenticate" => false,
		"view" => array(
			"layout" => "ext.Son.super-modules.admin-table.views.layout",
			"menu" => "ext.Son.super-modules.admin-table.views.menu"
		),
		"listDefaultConfig" => null,
		"configPageDefaultConfig" => null
	);

	protected $config = array();
	protected $user = null;
	protected $menu = null;
	protected $currentPageId = null;

	public function init(){
		parent::init();
		if($tempConfig=$this->getConfig()){
			$this->config = $tempConfig;
		}
		$this->config = array_replace_recursive($this->defaultConfig, $this->config);
	}

	protected function getConfig() {
		return null;
	}

	private function menuItemProcess($arr,&$items){
		foreach($arr as $key => $value){
			if(!is_array($value)){
				$items[] = array(
					"id" => $key,
					"content" => $value,
					"url" => $this->getBaseUrl($key)
				);
			} else {
				$presetItems = ArrayHelper::get($value,"items");
				$newMenuParent = array(
					"id" => $key,
				);
				if($presetItems){
					unset($value["items"]);
					$newMenuParent["items"] = array();
				} else {
					$newMenuParent["url"] = $this->getBaseUrl($key);
				}
				$newMenuParent = array_replace_recursive($value, $newMenuParent);
				if($presetItems){
					$this->menuItemProcess($presetItems,$newMenuParent["items"]);
				}
				$items[] = $newMenuParent;
			}
		}
	}

	public function getMenu(){
		if($this->menu===null){
			$config = array(
				"items" => array(),
				"view" => $this->config["view"]["menu"]
			);
			$this->menuItemProcess($this->config["menu"],$config["items"]);
			$this->menu = new SMenu($config);
			$this->menu->setActiveItemId($this->currentPageId ? $this->currentPageId : $this->action->id);
		}
		return $this->menu;
	}

	public function getBaseUrl($path=""){
		return $this->createAbsoluteUrl($path);
	}

	// override

	protected function beforeAction($action){
		if(!parent::beforeAction($action))
			return false;
		if($authenticate=$this->config["authenticate"]){
			return $authenticate($this);
		}
		return true;
	}

	public function missingAction($actionId){
		if(isset($this->config["pageList"][$actionId])){
			$this->action = new CInlineAction($this,$actionId);
			if($this->beforeAction($this->action)){
				$actionLabel = $this->config["pageList"][$actionId];
				$this->actionPage($actionId,$actionLabel);
			}
		} else if(isset($this->config["configPageList"][$actionId])){
			$this->action = new CInlineAction($this,$actionId);
			if($this->beforeAction($this->action)){
				$actionLabel = $this->config["configPageList"][$actionId];
				$this->actionConfigPage($actionId,$actionLabel);
			}
		} else {
			parent::missingAction($actionId);
		}
	}

	public function render($view,$data=null,$return=false){
		Son::load("SAsset")->addExtension("admin-table");
		return parent::render($view,$data,$return);
	}

	public function renderFile($viewFile,$data=null,$return=false){
		Son::loadFile("ext.Son.super-modules.admin-table.code.action_button_register");
		return parent::renderFile($viewFile,$data,$return);
	}

	// actions

	protected function actionPage($pageId,$pageTitle){
		$this->currentPageId = $pageId;
		$this->pageTitle = $pageTitle;
		$config = $this->getAdminTableListConfig($pageId,$pageTitle);
		$adminTableList = new AdminTableList($config);
		$this->onBuildList($adminTableList);
		$adminTableList->run();
	}

	protected function onBuildList($list){

	}

	protected function getAdminTableListConfig($pageId,$pageLabel){
		$thisFolder = dirname($this->getCurrentFile());
		$folderName = $this->config["folderName"];
		$file = "$thisFolder/$folderName/$pageId.php";
		$config = include_once($file);
		if($layout = $this->config["view"]["layout"]){
			$this->layout = $layout;
		}
		if($defaultConfig = $this->config["listDefaultConfig"]){
			$config = array_replace_recursive($defaultConfig, $config);
		}
		$config["alias"] = $pageId;
		$config["url"] = $this->getBaseUrl();
		return $config;
	}

	protected function actionConfigPage($pageId,$pageTitle){
		$this->currentPageId = $pageId;
		$this->pageTitle = $pageTitle;
		$config = $this->getConfigPageConfig($pageId,$pageTitle);
		$configPage = new ConfigPage($config);
		$this->onBuildConfigPage($configPage);
		$configPage->run();
	}

	protected function getConfigPageConfig($pageId, $pageTitle){
		$thisFolder = dirname($this->getCurrentFile());
		$folderName = $this->config["folderName"];
		$file = "$thisFolder/$folderName/$pageId.php";
		$config = include_once($file);
		if($layout = $this->config["view"]["layout"]){
			$this->layout = $layout;
		}
		if($defaultConfig = $this->config["configPageDefaultConfig"]){
			$config = array_replace_recursive($defaultConfig, $config);
		}
		return $config;
	}

	protected function onBuildConfigPage($configPage){

	}

	public function actionIndex(){
		$defaultPage = $this->config["defaultPage"];
		if(!$defaultPage){
			reset($this->config["pageList"]);
			$defaultPage = key($this->config["pageList"]);
		}
		$pageLabel = $this->config["pageList"][$defaultPage];
		$this->redirect($this->getBaseUrl($defaultPage));
	}

	// abstract methods
	abstract protected function getCurrentFile();
}