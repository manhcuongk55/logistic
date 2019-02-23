<?php
$controller = Util::controller();
$collaborator = Util::controller()->getUser();

$arr = array(
	"onRun" => function($list){
		
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
		"email" => array(
			"advancedSearchInputType" => "text_match_partial",
			"advancedSearchHtmlAttributes" => array(
				"placeholder" => "Email"
			)
		),
		"name" => array(
			"advancedSearchInputType" => "text_match_partial",
			"advancedSearchHtmlAttributes" => array(
				"placeholder" => "Tên"
			)
		),
		"address" => array(
			"advancedSearchInputType" => "text_match_partial",
			"advancedSearchHtmlAttributes" => array(
				"placeholder" => "Địa chỉ"
			)
		),
		"password" => array(
			"inputType" => "password"
		),
		"image" => array(
			"order" => false,
			"advancedSearchInputType" => false,
			"inputType" => "file_picker",
			"inputConfig" => array(
				"file_type" => "image"
			),
			"displayType" => "image",
			"exportType" => "url",
			"displayHtmlAttributes" => array(
				"style" => "width:50px; height: 50px;"
			),
		),
		"phone" => array(
			"advancedSearchInputType" => "text_match_partial"
        ),
		"facebook_id" => array(),
		"google_id" => array(),
        "skype" => array(
			"advancedSearchInputType" => "text_match_partial"
        ),
		"collaborator_group_id" => array(
			"label" => "Nhóm CTV",
			"inputType" => "dropdown_model_2",
			"inputConfig" => array(
				"modelClass" => "User",
				"attr" => "collaborator_group_id",
				"inputDropdown" => false
			),
			"displayType" => "label_model_2",
			"displayConfig" => array(
				"modelClass" => "User",
				"attr" => "collaborator_group_id"
			),
			"advancedSearchInputType" => true
		),
		"collaborator_id" => array(
			"label" => "CTV",
			"inputType" => "dropdown_model_2",
			"inputConfig" => array(
				"modelClass" => "User",
				"attr" => "collaborator_id",
				"inputDropdown" => false,
				"criteria" => array(
					"order" => "name ASC",
					"condition" => "collaborator_group_id = $collaborator->collaborator_group_id"
				)
			),
			"displayType" => "label_model_2",
			"displayConfig" => array(
				"modelClass" => "User",
				"attr" => "collaborator_id",
				"criteria" => array(
					"order" => "name ASC",
					"condition" => "collaborator_group_id = $collaborator->collaborator_group_id"
				)
			),
			"advancedSearchInputType" => true
		),
		"customer_type_id" => array(
			"label" => "Loại khách hàng",
			"inputType" => "dropdown_model_2",
			"inputConfig" => array(
				"modelClass" => "User",
				"attr" => "customer_type_id",
				"inputDropdown" => false
			),
			"displayType" => "label_model_2",
			"displayConfig" => array(
				"modelClass" => "User",
				"attr" => "customer_type_id"
			),
			"advancedSearchInputType" => true
		),
		"collaborator_name" => array(
			"label" => "CTV",
			"foreignConfig" => array("collaborator","name"),
		),
		"is_email_active" => array(
			"inputType" => "hidden"
		)
	),
	"actions" => array(
		"action" => array(
			"update" => false,
			"delete" => true,
			"insert" => false,
			"data" => array(
				"search" => array(
					"id", "name", "email", "facebook_id", 
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
		"class" => "User",
		"primaryField" => "id",
		"conditions" => array(
		),
		"with" => array(
			"collaborator"
		),
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
		"preloadData" => false,
		"forms" => array(
			"insert" => array(
				"uploadEnabled" => true
			),
			"update" => array(
				"uploadEnabled" => true
			),
		),
		"insertScenario" => "insert_from_collaborator",
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
		"title" => "Quản lý khách hàng",
		"columns" => array(
			"id", "name", "__action__", "image", "email", "customer_type_id", "skype", "phone", "collaborator_id"
		),
		"detail" => array(
			"customer_type_id", "id", "created_time", "updated_time", "active", "name", "email", "image", "facebook_id", "collaborator_group_id", "skype", "phone", "address", "collaborator_id"
		),
		"action" => false,
		"actionButtons" => array()
	),
	"mode" => "jquery",
);


if($collaborator->type==Collaborator::TYPE_SALE) {
	if($collaborator->is_manager){
		$arr["model"]["conditions"]["t.collaborator_group_id"] = $collaborator->collaborator_group_id;
		$arr["actions"]["action"]["update"] = array(
			"collaborator_id"
		);
	} else {
		$arr["model"]["conditions"]["collaborator_id"] = $collaborator->id;
	}
}

if($collaborator->type==Collaborator::TYPE_SALE){
	$arr["actions"]["action"]["insert"] = array(
		"name", "email", "password", "phone", "image", "skype", "address", "customer_type_id"
	);
}

return $arr;