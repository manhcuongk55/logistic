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
		"title" => array(
			"advancedSearchInputType" => "text_match_partial",
			"inputHtmlAttributes" => array(
				"slug-source" => ""
			)
		),
		"slug" => array(
			"inputType" => "text_slug",
			"inputConfig" => array(
				"target" => "[slug-source]"
			),
			"advancedSearchInputType" => "text_match_partial"
		),
		"content" => array(
			"inputType" => "html"
		),
		"short_description" => array(
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
			"exportType" => "url"
		),
		"admin_id" => array(
			"default" => Yii::app()->adminUser->id,
			"displayInput" => false
		),
		"admin_name" => array(
			"foreignConfig" => array("admin","name")
		),
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"active", "title", "slug", "content", "short_description", "image",
			),
			"delete" => true,
			"insert" => array(
				"title", "slug", "content", "short_description", "image", "admin_id"
			),
			"data" => array(
				"search" => array(
					"id", "title", "slug"
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => array(
					"columns" => array(
						"id", "title", "slug", "content", "image", "admin_id"
					),
					"types" => array(
						"excel", "csv"
					),
					"name" => "post_report"
				),
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
		"class" => "Post",
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
		"preloadData" => false,
		"forms" => array(
			"insert_update" => array(
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
		"title" => "Quản lý bài đăng",
		"columns" => array(
			"id", "title", "active", "created_time"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "title", "slug", "image", "admin_id", "short_description"
		),
		"action" => true,
		"actionButtons" => array(
			array("content_display",array(
				"attr" => "content"
			)),
			array("link",function($item){
				return array(
					"content" => '<i class="fa fa-external-link"></i>',
					"href" => Util::controller()->createUrl("/home/post",array(
						"id" => $item->model->id,
						"slug" => $item->model->slug
					)),
					"title" => "Link",
					"newTab" => true
				);
			}),
		)
	),
	"mode" => "jquery",
);

return $arr;