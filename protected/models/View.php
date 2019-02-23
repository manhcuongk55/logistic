<?php

Yii::import('application.models._base.BaseView');

class View extends BaseView
{
	const 	
			TYPE_GUEST = 0,
			TYPE_USER = 1,
			TYPE_COLLABORATOR = 2,
			TYPE_ADMIN = 3;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public static function viewCount($from,$to) {
		return self::model()->countBySql("SELECT IFNULL(COUNT(*),0) FROM {{view}} WHERE (type=:typeGuest OR type=:typeUser) AND :from < created_time AND created_time < :to",array(
			":typeGuest" => self::TYPE_GUEST,
			":typeUser" => self::TYPE_USER,
			":from" => $from,
			":to" => $to
		));
	}

	public static function userCount($type,$from,$to) {
		return self::model()->countBySql("SELECT IFNULL(COUNT(*),0) FROM (SELECT user_id FROM {{view}} WHERE type=:type AND :from < created_time AND created_time < :to GROUP BY user_id) t",array(
			":type" => $type,
			":from" => $from,
			":to" => $to
		));
	}
}