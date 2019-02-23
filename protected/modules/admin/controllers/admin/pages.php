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
		"page_id" => array(
			"advancedSearchInputType" => "text_match_partial"
		),
		"content" => array(
			"inputType" => "html"
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
		"image_thumbnail" => array(
			"order" => false,
			"advancedSearchInputType" => false,
			"displayType" => "image",
			"exportType" => "url"
		),
		"meta_keyword" => array(
			"inputType" => "textarea"
		),
		"meta_description" => array(
			"inputType" => "textarea"
		),
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"active", "title", "slug", "page_id", "content", "image", "meta_keyword", "meta_description"
			),
			"delete" => true,
			"insert" => array(
				"title", "slug", "page_id", "content", "image", "meta_keyword", "meta_description"
			),
			"data" => array(
				"search" => array(
					"id", "title", "page_id",
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => array(
					"columns" => array(
						"id", "title", "page_id", "content", "language"
					),
					"types" => array(
						"excel", "csv"
					),
					"name" => "page_report"
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
		"class" => "Page",
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
		"title" => "Quản lý nội dung",
		"columns" => array(
			"id", "title", "page_id"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "title", "slug", "page_id"
		),
		"action" => true,
		"actionButtons" => array(
			array("content_display",array(
				"attr" => "content"
			)),
			array("link",function($item){
				return array(
					"content" => '<i class="fa fa-external-link"></i>',
					"href" => Util::controller()->createUrl("/home/page",array(
						"page" => $item->model->page_id
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