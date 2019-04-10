<?php
$menuItemTemplate = array(
	"type" => "object",
	"items" => array(
		"name" => array(
			"label" => "Tiêu đề",
			"type" => "text"
		),
		"url" => array(
			"label" => "Url",
			"type" => "text"
		)
	)
);

$arr = array(
	"title" => "Cài đặt Front Page",
	"src" => "application.config.params.front_page",
	"fields" => array(
		"meta_description" => array(
			"type" => "textarea"
		),
		"meta_keywords" => array(
			"type" => "textarea"
		),
		"google_analytic_code" => array(
			"type" => "textarea"
		),
		"facebook_page" => array(),
		"facebook" => array(),
		"twitter" => array(),
		"pinterest" => array(),
		"googleplus" => array(),
		"skype" => array(),
		"address" => array(
      		"type" => "textarea"
    	),
		"phone" => array(
			"type" => "phone"
		),
		"email" => array(
			"type" => "email"
		),
		"transfer_accounts" => array(
			"type" => "array",
			"label" => "Tài khoản ngân hàng",
			"itemTemplate" => array(
				"type" => "object",
				"items" => array(
					"id_number" => array(
						"label" => "Số tài khoản",
					),
					"owner" => array(
						"label" => "Chủ tài khoản",
					),
					"bank" => array(
						"label" => "Ngân hàng",
					),
					"branch" => array(
						"label" => "Chi nhánh",
					),
				)
            )
		),
		"float_links" => array(
			"label" => "Links",
			"type" => "object",
			"items" => array(
				"extension_link" => array(
					"label" => "Link exntesion",
					"type" => "text"
				),
			)
		),
		"menu" => array(
			"label" => "Menu",
			"type" => "object",
			"items" => array(
				"services" => array(
					"label" => "Dịch vụ",
					"type" => "array",
					"itemTemplate" => $menuItemTemplate,
				),
				"regulations" => array(
					"label" => "Quy định",
					"type" => "array",
					"itemTemplate" => $menuItemTemplate,
				),
				"download" => array(
					"label" => "Download",
					"type" => "array",
					"itemTemplate" => $menuItemTemplate,
				),
				"price" => array(
					"label" => "Bảng giá",
					"type" => "array",
					"itemTemplate" => $menuItemTemplate,
				),
			)
		)
	),
	"cache" => array(
		true
	)
);

return $arr;
