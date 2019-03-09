<?php
class MultiLanguage {
	const KEY = "lang";

	public static function setLanguage($lang){
		Yii::app()->request->cookies[self::KEY] = new CHttpCookie(self::KEY, $lang, array(
			"expire" => time() + 3600 * 24 * 365 // 1 year
		));
		Yii::app()->language = $lang;
	}

	public static function getCurrentLanguage(){
		return Yii::app()->language;
	}

	public static function restoreCurrentLanguage(){
		if(isset($_GET["lang"])){
			Yii::app()->language = $_GET["lang"];
			return;
		}
		if(isset(Yii::app()->request->cookies[self::KEY])){
			Yii::app()->language = Yii::app()->request->cookies[self::KEY]->value;
		}
	}

	public static function getCurrentLanguageConfig(){
		$currentLanguage = self::getCurrentLanguage();
		$list = Util::param2("languages");
		return $list[$currentLanguage];
	}

	public static function getOtherLanguageConfigList(){
		$currentLanguage = self::getCurrentLanguage();
		$list = Util::param2("languages");
		unset($list[$currentLanguage]);
		return $list;
	}
}