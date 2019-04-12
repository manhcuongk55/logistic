<?php
class PostList extends SList {
	protected function getConfig(){
		$list = $this;
		$arr = array(
			"fields" => array(
				"__item" => array(),
				"id" => array(),
				"active" => array(),
				"created_time" => array(),
				"updated_time" => array(),
				"title" => array(),
				"slug" => array(),
				"meta_keywords" => array(),
				"meta_description" => array(),
				"short_description" => array(),
				"admin_id" => array(),
				"image" => array(),
			),
			"actions" => array(
				"action" => array(
					"data" => array(
						"search" => false,
						"order" => false,
						"limit" => true,
						"offset" => true,
						"page" => true,
					),
				)
			),
			"model" => array(
				"class" => "Post",
				"primaryField" => "id",
				"conditions" => array(
					"t.active" => 1,
				),
				"with" => array(
					"admin"
				),
				"onCriteria" => null,
				"addedCondition" => array(),
				"defaultQuery" => array(
					"orderBy" => "id",
					"orderType" => "desc",
					"limit" => 12,
					"offset" => 0,
					"search" => "",
					"advancedSearch" => array(
						"active" => 1
					),
					"page" => 1
				),
				"dynamicInputs" => array(),
				"preloadData" => false
			),
			"view" => array(
				"itemSelectable" => false,
				"trackUrl" => true,
				"viewPath" => array(
					"list" => "application.components.list.views.post_list",
					"item" => "application.components.list.views.post_list_item"
				),
				//"infiniteScroll" => true
			),
			"pagination" => array(
				"first" => true,
				"back" => true,
				"next" => true,
				"last" => true,
				"count" => 5,
				"view" => "application.components.list.views.list-pagination"
			),
			"mode" => "php",
			"autoRenderPage" => false,
			"baseUrl" => Util::controller()->createUrl("/home/posts") . "?"
		);

		return $arr;
	}
}