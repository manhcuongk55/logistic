<?php
class SMenu {
	private $configDefault = array(
		"multiLevelActive" => true,
		"items" => array(
			"__item" => array(
				"url" => "javascript:;"
			)
		)
	);
	var $config = null;
	var $activeItemId = null;
	var $isSubMenu = false;

	private $_currentIndex = -1;
	private $_currentItem = null;

	function __construct($config=null,$isSubMenu=false){
		if($config!=null)
			$this->config = $config;
		$this->isSubMenu = $isSubMenu;
		$this->init();
	}

	protected function init(){
		if(!$this->config){
			if($tempConfig = $this->getConfig()){
				$this->config = $tempConfig;
			}
		}
		$this->config = array_replace_recursive($this->configDefault,$this->config);
		ArrayHelper::processItemDefault($this->config["items"]);
	}

	/**
		* Return true when still have item
	*/
	public function loop(){
		$this->_currentIndex = $this->_currentIndex + 1;
		$this->_currentItem = ArrayHelper::get($this->config["items"],$this->_currentIndex,false);
		return $this->_currentItem;
	}

	public function resetLoop(){
		$this->_currentIndex = -1;
		$this->_currentItem = null;
		return true;
	}

	public function currentItem($attributeName=null,$defaultValue=""){
		if($attributeName==null)
			return $this->_currentItem;
		$value = ArrayHelper::get($this->_currentItem,$attributeName,$defaultValue);
		if(is_callable($value)){
			$value = $value($this->_currentItem);
		}
		return $value;
	}

	public function currentItemActive($multiLevelActive=true){
		if($multiLevelActive){
			return $this->currentItemActiveOrHasDescActive();
		}
		return $this->_currentItem["id"] == $this->activeItemId;
	}

	public function currentItemHasDescActive(){
		$menu = $this->currentItemGetMenu();
		if(!$menu)
			return false;
		return $menu->hasItemActive();
	}

	public function hasItemActive(){
		foreach($this->config["items"] as $i => $item){
			if($item["id"]==$this->activeItemId){
				return true;
			} else {
				$itemMenu = $this->itemGetMenu($i);
				if($itemMenu && $itemMenu->hasItemActive()){
					return true;
				}
			}
		}
		return false;
	}

	public function currentItemActiveOrHasDescActive(){
		return $this->currentItemActive(false) || $this->currentItemHasDescActive();
	}

	public function currentItemGetMenu(){
		return $this->itemGetMenu($this->_currentIndex);
	}

	public function itemGetMenu($i){
		$item = $this->config["items"][$i];
		$self = $this;
		$itemMenu = ArrayHelper::get($item,"__menu",function() use ($self,$item){
			$menuObject = null;
			if($items = ArrayHelper::get($item,"items")){
				$currentItemMenuConfig = $self->config;
				$currentItemMenuConfig["items"] = $items;
				$className = get_class($self);
				$menuObject = new $className($currentItemMenuConfig,true);
				$menuObject->setActiveItemId($this->activeItemId);
			}
			return $menuObject;
		},true);
		return $itemMenu;
	}

	public function setActiveItemId($id){
		$this->activeItemId = $id;
	}

	public function render($view=null,$params=array()){
		if($view==null){
			$view = $this->config["view"];
		}
		$params["menu"] = $this;
		Util::controller()->renderPartial($view,$params);
	}

	protected function getConfig() { return null; }

}