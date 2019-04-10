<?php
$controller = Util::controller();

$collaboratorGroupId = Input::get("collaborator_group_id");
$collaboratorGroup = null;
if($collaboratorGroupId){
	$collaboratorGroup = CollaboratorGroup::model()->findByPk($collaboratorGroupId);
	if(!$collaboratorGroup){
		Output::show404();
		return;
	}
}
$controller->data["collaboratorGroup"] = $collaboratorGroup;

$arr = array(
	"onRun" => function($list){
		if($collaboratorGroup = ArrayHelper::get(Util::controller()->data,"collaboratorGroup")){
			$list->setDynamicInput("collaborator_group_id",$collaboratorGroup->id);
		}
		
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
		"is_email_active" => array(
			"inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
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
		"phone" => array(),
		"facebook_id" => array(),
		"google_id" => array(),
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
		)
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"name", "email", "password", "image", "facebook_id", "phone", "collaborator_group_id", "customer_type_id", "active", "is_email_active",
			),
			"delete" => true,
			"insert" => array(
				"name", "email", "password", "image", "facebook_id", "phone", "collaborator_group_id", "customer_type_id"
			),
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
			"collaborator_group_id" => function($criteria,$value){
				$criteria->compare("collaborator_group_id",$value);
			},
		),
		"preloadData" => false,
		"forms" => array(
			"insert" => array(
				"uploadEnabled" => true
			),
			"update" => array(
				"uploadEnabled" => true
			),
		)
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
		"title" => "Quản lý thành viên",
		"columns" => array(
			"id", "name", "image", "email", "active", "collaborator_group_id", "customer_type_id"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "name", "email", "image", "facebook_id", "collaborator_group_id", "customer_type_id", "is_email_active",
		),
		"action" => true,
		"actionButtons" => array()
	),
	"mode" => "jquery",
);

return $arr;