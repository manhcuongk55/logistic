<?php

Yii::import('application.models._base.BaseCustomerType');

class CustomerType extends BaseCustomerType
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}