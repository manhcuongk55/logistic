<?php
class AdminTableListItem extends SListItem {
	protected function renderDefaultAction($actionName){
		$arr = ArrayHelper::get($this->list->config["admin"]["action"],$actionName,array());
		$config = ArrayHelper::get($arr,0,array());
		$htmlAttributes = ArrayHelper::get($arr,1,array());
		Son::load("AdminTableHelper")->renderActionButton($this,$actionName,$config,$htmlAttributes);
	}

	public function renderActionDelete(){
		$this->renderDefaultAction("delete");
	}

	public function renderActionUpdate(){
		$this->renderDefaultAction("update");
	}

	public function renderActionDetail(){
		$this->renderDefaultAction("detail");
	}

	public function renderAction($actionButton){
		$actionName = $actionButton[0];
		$config = ArrayHelper::get($actionButton,1,array());
		$htmlAttributes = ArrayHelper::get($actionButton,2,array());
		Son::load("AdminTableHelper")->renderActionButton($this,$actionName,$config,$htmlAttributes);
	}

	public function hasActionDelete(){
		return $this->list->config["actions"]["action"]["delete"];
	}

	public function hasActionUpdate(){
		return $this->list->config["actions"]["action"]["update"];
	}

	public function hasActionDetail(){
		return $this->list->config["admin"]["detail"];
	}
}