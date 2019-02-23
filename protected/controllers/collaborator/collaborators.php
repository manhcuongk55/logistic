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
        "collaborator_group_id" => array(
            "inputType" => "dropdown_model_2",
			"inputConfig" => array(
				"modelClass" => "Collaborator",
				"attr" => "collaborator_group_id",
				"inputDropdown" => false,
				"triggerType" => "changed",
			),
			"displayType" => "label_model_2",
			"displayConfig" => array(
				"modelClass" => "Collaborator",
				"attr" => "collaborator_group_id"
			),
			"advancedSearchInputType" => true,
        ),
        "type" => array(
            "inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "Collaborator",
				"attr" => "type",
				"inputDropdown" => false,
				"triggerType" => "changed",
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "Collaborator",
				"attr" => "type",
			),
			"advancedSearchInputType" => true,
        ),
        "is_manager" => array(
            "inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
        ),
        "description" => array(
            "inputType" => "textarea"
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
        "image_thumbnail" => array(
            "order" => false,
			"advancedSearchInputType" => false,
            "displayType" => "image",
			"exportType" => "url",
        ),
		"phone" => array(),
		"facebook_id" => array(),
        "google_id" => array(),
        "skype" => array(),
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"active", "name", "email", "password", "image", "description", "type", "is_manager", "collaborator_group_id", "phone", "facebook_id", "google_id", "skype"
			),
			"delete" => true,
			"insert" => array(
				 "name", "email", "password", "image", "description", "type", "is_manager", "collaborator_group_id", "phone", "facebook_id", "google_id", "skype"
			),
			"data" => array(
				"search" => array(
					"id", "name", "email", "facebook_id", "google_id", "skype"
				),
				"advancedSearch" => false,
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
		"class" => "Collaborator",
		"primaryField" => "id",
		"conditions" => array(
			"collaborator_group_id" => Util::controller()->getUser()->collaborator_group_id
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
		"itemSelectable" => false,
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
			"name", "email", "image", "type", "is_manager"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "name", "email", "password", "image", "description", "type", "is_manager", "collaborator_group_id", "phone", "facebook_id", "google_id", "skype"
		),
		"action" => true,
		"actionButtons" => array()
	),
	"mode" => "jquery",
);

return $arr;