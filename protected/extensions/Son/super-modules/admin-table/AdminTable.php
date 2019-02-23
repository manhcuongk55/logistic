<?php
class AdminTable extends List {
	public $viewFolder = "ext.Son.super-modules.views";

	public function isLoggedIn(){
		return true;
	}

	public function getLogInUrl(){
		return $this->getBaseUrl() . "/login";
	}

	public function getLogOutUrl(){
		return $this->getBaseUrl() . "/logout";
	}
}