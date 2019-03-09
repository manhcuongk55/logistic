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
			"advancedSearchInputType" => true
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
        "description" => array(
            "inputType" => "textarea"
        ),
		"is_admin_group" => array(
			"inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
		),
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"active", "name", "description", "is_admin_group"
			),
			"delete" => true,
			"insert" => array(
				"name", "description", "is_admin_group"
			),
			"data" => array(
				"search" => array(
					"id", "name"
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
		"class" => "CollaboratorGroup",
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
		"itemSelectable" => array(
			"type" => "checkbox",
			"selectedClass" => "active"
		),
		"onRender" => function($list){
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
		"title" => "Nhóm CTV",
		"columns" => array(
			"id", "name", "is_admin_group", "active", "created_time"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "name", "description", "is_admin_group"
		),
		"action" => true,
		"actionButtons" => array(
			array("link",function($item){
				$url = Util::controller()->createUrl("/admin/home/collaborators?collaborator_group_id=". $item->model->id);
				return array(
					"content" => '<i class="fa fa-user-md"></i>',
					"newTab" => false,
					"href" => $url,
					"title" => "Danh sách CTV"
				);
			}),
			array("link",function($item){
				$url = Util::controller()->createUrl("/admin/home/users?collaborator_group_id=". $item->model->id);
				return array(
					"content" => '<i class="fa fa-user"></i>',
					"newTab" => false,
					"href" => $url,
					"title" => "Danh sách khách hàng"
				);
			}),
		)
	),
	"mode" => "jquery",
);

return $arr;