<?php
$arr = array(
	"title" => "Suggest posts",
	"src" => "application.config.params.keywords",
	"fields" => array(
		"keywords" => array(
			"type" => "array",
			"label" => "TỪ KHÓA GỢI Ý",
			"itemTemplate" => array(
				"type" => "object",
				"items" => array(
					"title" => array(
						"label" => "Title"
					),
					"url" => array(
						"label" => "Url"
					)
				)
			)
		),
	),
	"cache" => array(
		true
	)
);

return $arr;