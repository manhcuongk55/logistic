<?php
class SimpleNotificationList extends SList {

	protected function getConfig(){
		$arr = array(
			"fields" => array(
				"__item" => array(
					"order" => false
				),
				"id" => array(),
				"active" => array(),
				"created_time" => array(
					"displayType" => "time_format",
					"displayConfig" => array(
						"format" => "d-m-Y h:i:s"
					)
				),
				"updated_time" => array(
					"displayType" => "time_format",
					"displayConfig" => array(
						"format" => "d-m-Y h:i:s"
					)
				),
				"user_id" => array(),
				"type" => array(),
				"user_type" => array(),
				"params" => array(),
				"is_read" => array()
			),
			"actions" => array(
				"action" => array(
					"data" => array(
						"order" => true,
						"limit" => true,
						"offset" => true,
						"page" => true,
					),
				)
			),
			"model" => array(
				"class" => "Notification",
				"primaryField" => "id",
				"conditions" => array(
					"t.active" => 1,
					"t.is_read" => 0,
				),
				"with" => array(
				),
				"addedCondition" => array(),
				"defaultQuery" => array(
					"orderBy" => "id",
					"orderType" => "desc",
					"limit" => 20,
					"offset" => 0,
					"search" => "",
					"advancedSearch" => array(
						"active" => 1,
					),
					"page" => 1
				),
				"dynamicInputs" => array(
					"user_type" => function($criteria,$value){
						$criteria->addCondition("t.user_type = :user_type");
						$criteria->params[":user_type"] = $value;
					},
					"user_id" => function($criteria,$value){
						$criteria->addCondition("t.user_id = :user_id");
						$criteria->params[":user_id"] = $value;
					}
				),
				"preloadData" => false
			),
			"view" => array(
				"itemSelectable" => false,
				"trackUrl" => true,
				"viewPath" => array(
					"list" => "application.components.list.views.simple_notification_list",
					"item" => "application.components.list.views.simple_notification_list_item"
				)
			),
			"pagination" => array(
				"first" => true,
				"back" => true,
				"next" => true,
				"last" => true,
				"count" => 5,
			),
			"mode" => "jquery",
			"autoRenderPage" => false
		);

		return $arr;
	}

	public function __construct(){
		parent::__construct();
	}
}