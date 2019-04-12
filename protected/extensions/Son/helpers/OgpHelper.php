<?php
class OgpHelper {
	public static function generateProperty($key,$value){
		self::createMetaTag($key,$value);
	}

	public static function generateProperties($arr){
		foreach($arr as $key => $value){
			self::createMetaTag($key,$value);
		}
	}

	public static function generateObject($name,$arr,$raw=null){
		foreach($arr as $key => $value){
			self::createMetaTag("$name:$key",$value);
		}
		if($raw){
			self::createMetaTag($name,$raw);
		}
	}

	public static function createMetaTag($key,$value){
		Yii::app()->clientScript->registerMetaTag($value,null,null,array(
			"property" => "og:$key"
		));
	}
}