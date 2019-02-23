<?php
class ArrayHelper {
	private static function handleDefaultValue(&$arr,$key,$default=null,$setIfNotExist=false){
		if(is_callable($default)){
			$default = $default();
		}
		if($setIfNotExist){
			if($arr===null){
				$arr = array();
			}
			$arr[$key] = $default;
		}
		return $default;
	}

	public static function get(&$arr,$key,$default=null,$setIfNotExist=false){
		if($arr===null || !is_array($arr)){
			return self::handleDefaultValue($arr,$key,$default,$setIfNotExist);
		}
		if(!is_array($key)){
			if(!isset($arr[$key])){
				return self::handleDefaultValue($arr,$key,$default,$setIfNotExist);
			} else {
				return $arr[$key];
			}
		} else {
			$value = $arr;
			foreach($key as $item){
				if($value===null || !is_array($value) || !isset($value[$item])){
					$default = self::handleDefaultValue($arr,$key,$default,false);
					if(!$setIfNotExist){
						return $default;
					}
					if($value===null){
						$value = array();
					}
					$value[$item] = $default;
				}
				$value = $value[$item];
			}
			return $value;
		}
	}

	public static function has($arr,$key){
		return $arr && isset($arr[$key]);
	}

	public static function contains($arr,$item){
		return $arr && is_array($arr) && in_array($item, $arr);
	}

	public static function set(&$arr,$key,$value="__unset__"){
		if($arr==null){
			$arr = array();
		}
		if($value=="__unset__"){
			$arr = array_merge_recursive($arr,$key);
			return;
		}
		$arr[$key] = $value;
	}

	public static function processItemDefault(&$arr,$default=null){
		if(!($defaultItem = ArrayHelper::get($arr,"__item",$default)))
			return;
		if(isset($arr["__item"]))
			unset($arr["__item"]);
		foreach($arr as $key => $item){
			$arr[$key] = array_replace_recursive($defaultItem,$item);
		}
	}

	public static function processItemDefaultAssoc(&$arr,$default=null){
		if(!($defaultItem = ArrayHelper::get($arr,"__item",$default)))
			return;
		if(isset($arr["__item"]))
			unset($arr["__item"]);
		foreach($arr as $key => $item){
			if(is_numeric($key)){
				$arr[$item] = $defaultItem;
				unset($arr[$key]);
			} else {
				$arr[$key] = array_replace_recursive($defaultItem,$item);
			}
		}
	}

	public static function asArray($item){
		if(is_array($item)){
			return $item;
		}
		return array($item);
	}
}