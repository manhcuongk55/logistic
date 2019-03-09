<?php
class AdminTableList extends SList {
	var $adminTableDefaultConfig = array(
		"class" => array(
			"item" => "AdminTableListItem"
		),
		"model" => array(
			"cacheLoadDataDisabled" => false
		),
		"view" => array(
			"viewPath" => array(
				"list" => "ext.Son.super-modules.admin-table.views.list",
				"item" => "ext.Son.super-modules.admin-table.views.item"
			)
		),
		"admin" => array(
			"title" => "Manager",
			"action" => true,
			"detail" => null,
			"actionButtons" => array()
		)
	);
	public function __construct($config=null){
		$this->configDefault = array_replace_recursive($this->configDefault, $this->adminTableDefaultConfig);
		parent::__construct($config);
	}
}