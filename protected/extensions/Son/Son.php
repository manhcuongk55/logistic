<?php
class Son {
	static $objects;
	public static function whoami() {
		return "Tung's girlfriend :-)";
	}

	public static function load($className,$params=null){
		return ArrayHelper::get(Son::$objects,$className,function() use ($className,$params){
			if($params==null){
				return new $className();
			} else {
				$reflect  = new ReflectionClass($className);
				$instance = $reflect->newInstanceArgs($params);
				return $instance;
			}
		},true);
	}

	public static function loadFile($path,$includeOnce=true,$isRequire=false){
		$file = self::getFilePath($path);
		if(!$isRequire){
			if($includeOnce)
				return include_once($file);
			else
				return include($file);
		} else {
			if($includeOnce)
				return require_once($file);
			else
				return require($file);
		}
	}

	public static function getFilePath($path,$ext="php"){
		$file = Yii::getPathOfAlias($path);
		if($ext)
			$file .= ".$ext";
		return $file;
	}
}
Son::$objects = array();