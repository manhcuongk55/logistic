<?php
$arr = array(
	"onRun" => function($list){
		//return;
		
	},
	"fields" => array(
		"__item" => array(
			"order" => true
		),
		"id" => array(
		),
		"active" => array(
			"inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
		),
		"created_time" => array(
			"displayType" => "time_format",
			"displayConfig" => array(
				"format" => "d-m-Y h:i:s"
			),
			"inputType" => "timestamp_datetimepicker",
			"advancedSearchInputType" => "timestamp_range_datetimepicker",
			"exportType" => "timestamp"
		),
		"updated_time" => array(
			"displayType" => "time_format",
			"displayConfig" => array(
				"format" => "d-m-Y h:i:s"
			),
			"inputType" => "timestamp_datetimepicker",
			"advancedSearchInputType" => "timestamp_range_datetimepicker",
			"exportType" => "timestamp"
		),
		"name" => array(
			"advancedSearchInputType" => "text_match_partial",
		),
		"email" => array(
			"advancedSearchInputType" => "text_match_partial",
			"advancedSearchHtmlAttributes" => array(
				"placeholder" => "Email"
			)
		),
		"password" => array(
			"inputType" => "password"
		),
		"type" => array(
			"inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "Admin",
				"attr" => "type",
				"triggerType" => "changed",
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "Admin",
				"attr" => "type"
			),
			"advancedSearchInputType" => true
		)
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"active", "name", "email", "password", "type"
			),
			"delete" => true,
			"insert" => array(
				"name", "email", "password", "type"
			),
			"data" => array(
				"search" => array(
					"id", "name", "email",
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => false,
			),
		),
		"actionTogether" => array(
			"deleteTogether" => false	
		),
		"extendedAction" => array(
		),
		"extendedActionTogether" => array(
		),
	),
	"model" => array(
		"class" => "Admin",
		"primaryField" => "id",
		"conditions" => array(
		),
		"with" => array(),
		"addedCondition" => array(),
		"defaultQuery" => array(
			"orderBy" => "id",
			"orderType" => "desc",
			"limit" => 20,
			"offset" => 0,
			"search" => "",
			"advancedSearch" => array(
				"active" => 1
			),
			"page" => 1
		),
		"dynamicInputs" => array(
		),
		"preloadData" => false
	),
	"view" => array(
		"itemSelectable" => false,
		"onRender" => function($list){
			$sAsset = Son::load("SAsset");
			$sAsset->startCssCode();
			?>
			<style>
				.list-th-actions {
					width:200px;
				}

				.list-th-attr-id {
					width:60px;
				}
				.list-th-attr-active {
					width:80px;
				}
			</style>
			<?php
			$sAsset->endCssCode();
		},
		"limitSelectList" => array(10,20,30,40),
		"trackUrl" => true
	),
	"pagination" => array(
		"first" => true,
		"back" => true,
		"next" => true,
		"last" => true,
		"count" => 5
	),
	"admin" => array(
		"title" => "Quản lý Admin",
		"columns" => array(
			"id", "name", "email", "type", "active",
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "name", "email", "type"
		),
		"action" => true,
		"actionButtons" => array(
		)
	),
	"mode" => "jquery",
);

return $arr;