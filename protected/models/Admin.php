<?php

Yii::import('application.models._base.BaseAdmin');

class Admin extends BaseAdmin
{
	const
			TYPE_FULL_PERMISSION = 1,
			TYPE_ACCOUNTANT = 2,
			TYPE_CUSTOMER_CARE = 3;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	protected $passwordField = "password";

	public $listDropdownConfig = array(
		"type" => array(
			self::TYPE_FULL_PERMISSION => "Toàn quyền",
			self::TYPE_ACCOUNTANT => "Kế toán",
			self::TYPE_CUSTOMER_CARE => "CSKH",
		)
	);
}