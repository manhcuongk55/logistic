<?php
$arr = array(
	"title" => "Cài đặt chung",
	"src" => "application.config.params.setting",
	"fields" => array(
		"vnd_ndt_rate" => array(
			"label" => "Tỉ giá VNĐ / NDT",
			"type" => "money_input",
			"rules" => array(
				array("required"),
				array("numerical", "integerOnly"=>true)
			),
		),
		"min_service_price" => array(
			"label" => "Phí DV tối thiểu",
			"type" => "money_input",
			"rules" => array(
				array("required"),
				array("numerical", "integerOnly"=>true)
			),
		),
		"min_weight_price" => array(
			"label" => "Cước cân nặng tối thiểu",
			"type" => "money_input",
			"rules" => array(
				array("required"),
				array("numerical", "integerOnly"=>true)
			),
		),
		"popup_banner_enabled" => array(
			"type" => "checkbox_button"
		),
		"admin_email_subscribe" => array(
			"type" => "email"
		)
	),
	"cache" => array(
		true
	)
);

return $arr;
