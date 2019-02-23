<?php
$arr = array(
	"title" => "Cài đặt trang chủ",
	"src" => "application.config.params.front_page_content",
	"fields" => array(
		"content" => array(
			"type" => "html"
		),
		"video" => array(
			"type" => "textarea"
		),
		"user_page_content1" => array(
			"label" => "Lưu ý (trang cá nhân)",
			"type" => "html"
		),
		"contact_phones" => array(
			"type" => "object",
			"label" => "Thông tin liên lạc",
			"items" => array(
				"sale" => array(
					"label" => "CSKH",
					"type" => "text"
				),
				"accountant" => array(
					"label" => "Kế toán",
					"type" => "text"
				),
				"store" => array(
					"label" => "Kho",
					"type" => "text"
				),
				"order" => array(
					"label" => "Đặt hàng",
					"type" => "text"
				),
			)
		)
	),
	"cache" => array(
		true
	)
);

return $arr;