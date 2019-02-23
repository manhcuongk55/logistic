<?php
$arr = array(
	"title" => "Tài khoản ngân hàng",
	"src" => "application.config.params.transfer_accounts",
	"fields" => array(
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
	),
	"cache" => array(
		"home-checkout_payment"
	)
);

return $arr;
