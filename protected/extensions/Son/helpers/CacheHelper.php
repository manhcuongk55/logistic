<?php
/**
	* Cache
*/
class CacheHelper {

	/* CACHE CONSTANTS */
	const CACHE_FILE_DEPENDENCY_FOLDER = "_cache_file_dependency";
	const PAGE_FRAGMENT_KEY = "__page_fragment_key";
	const HTTP_CACHE_KEY = "__http_key";

	static $tempFragmentCaches;
	public static $httpCacheInfo;

	public static function getCacheEnabled(){
		return ArrayHelper::get(Util::controller()->data,"cacheEnabled",function(){
			if(!Util::param2("common","cacheEnabled"))
				return false;
			return !Input::isPost();
		},true);
	}

	public static function setCacheEnabled($value){
		Util::controller()->data["cacheEnabled"] = $value;
	}

	public static function getLastUpdatedTimeOfDependencyKeys($dependencyKeys){
		if(!is_array($dependencyKeys)){
			$dependencyKeys = unserialize($dependencyKeys);
		}
		$max = -1;
		foreach($dependencyKeys as $dependencyKey => $item){
			if(is_numeric($dependencyKey)){
				$itemClassName = $item;
				$itemConditions = null;
			} else {	
				$itemClassName = $dependencyKey;
				$itemConditions = $item;
			}
			$value = self::getLastUpdatedTimeOfDependencyKey($itemClassName,$itemConditions);
			if($value > $max){
				$max = $value;
			}
		}
		return $max;
	}

	public static function getLastUpdatedTimeOfDependencyKey($dependencyKey=null,$conditions=null){
		if($dependencyKey===null){
			return 0;
		}
		if(is_array($dependencyKey)){
			return self::getLastUpdatedTimeOfDependencyKeys($dependencyKey);
		}
		$fileName = self::getLastUpdatedTimeFilePathOfDependencyKey($dependencyKey,$conditions);
		$result = @file_get_contents($fileName);
		if(!$result)
			$result = 0;
		else
			$result = intval($result);
		return $result;
	}

	public static function setLastUpdatedTimeOfDependencyKey($dependencyKey,$updatedTime,$conditions=null){
		$fileName = self::getLastUpdatedTimeFilePathOfDependencyKey($dependencyKey,$conditions);
		$folder = dirname($fileName);
        if(!file_exists($folder)){
            mkdir($folder,0777,true);
        }
		file_put_contents($fileName, $updatedTime);
	}

	private static function getLastUpdatedTimeFilePathOfDependencyKey($dependencyKey,$conditions=null){
		$fileName = "";
		if(!is_array($conditions)){
			$fileName = "global";
		} else {
			foreach($conditions as $k => $v){
				$fileName = $k . "_" . $v . "_";
			}
		}
		return Yii::getPathOfAlias("webroot") . "/" . self::CACHE_FILE_DEPENDENCY_FOLDER . "/" . $dependencyKey . "/" . $fileName . ".cache";
	}

	public static function getDependency($dependencyKey,$conditions=null){
		//$fileName = CacheHelper::getLastUpdatedTimeFilePathOfDependencyKey($className,$conditions);
		//$expression = "@file_get_contents('" . $fileName . "')";
		if(is_array($dependencyKey)){
			$expression = "CacheHelper::getLastUpdatedTimeOfDependencyKeys('" . serialize($dependencyKey) . "')";
		} else {
			$expression = "CacheHelper::getLastUpdatedTimeOfDependencyKey('" . $dependencyKey . "')";
		}
		
		$dependency = new CExpressionDependency($expression);
		return $dependency;
	}

	private static function getKeyWithOptions($key,$options){
		if($key==null){
			$key = Yii::app()->request->requestUri;
		}
		if(ArrayHelper::get($options,"differentByUser")){
			$key = self::getKeyForUser($key);
		}
		if(ArrayHelper::get($options,"differentByLogin")){
			$key = self::getKeyForLogin($key);
		}
		if(Util::param2("cache","differentByLanguage") || ArrayHelper::get($options,"differentByLanguage")){
			$key = self::getKeyForLanguage($key);
		}
		return $key;
	}

	public static function returnHttpCacheIfAvailable($key=null,$dependencyKeys=null,$options=array(
			"differentByUser" => true,
			"differentByLanguage" => true
		)){
		self::$httpCacheInfo = array(
			"key" => $key,
			"dependencyKeys" => $dependencyKeys,
			"options" => $options
		);

		if(!self::getCacheEnabled())
			return false;
		$key = self::getKeyWithOptions($key,$options);
		if($dependencyKeys===null){
			$dependencyKeys = array($key);
		}
		if(!is_array($dependencyKeys)){
			$dependencyKeys = array($dependencyKeys);
		}
		$dependencyKeys[] = self::HTTP_CACHE_KEY;
		$dependencyKeys[] = self::getKeyForUser(self::HTTP_CACHE_KEY);
		$dependencyKeys[] = self::getKeyForLanguage(self::HTTP_CACHE_KEY);
		$timestamp = self::getLastUpdatedTimeOfDependencyKey($dependencyKeys);
		return Output::httpCache($key,$timestamp);
	}

	public static function beginFragmentWithHttpCacheInfo($options=array()){
		$info = self::$httpCacheInfo;
		foreach($options as $k => $v){
			$info["options"][$k] = $v;
		}
		return self::beginFragment($info["key"],$info["dependencyKeys"],$info["options"]);
	}

	public static function beginFragment($key=null,$dependencyKeys=null,$options=array()){
		if(!self::getCacheEnabled())
			return true;
		$debug = !Input::isAjax() && Util::param2("common","cacheDebug",false);
		$key = self::getKeyWithOptions($key,$options);
		$value = Yii::app()->cache->get($key);
		$hasCache = !($value===false);
		$date = date("d/m/Y h:i:s");
		if($hasCache){
			if($debug){
				echo "<noscript>CACHED: $key at $date</noscript>";
			}
			echo $value;
		} else {
			if($debug){
				echo "<noscript>NO CACHED: $key at $date</noscript>";
			}
			if($dependencyKeys===null){
				$dependencyKeys = array($key);
			}
			if(!is_array($dependencyKeys)){
				$dependencyKeys = array($dependencyKeys);
			}
			$dependencyKeys[] = self::PAGE_FRAGMENT_KEY;

			self::$tempFragmentCaches[] = array(
				"key" => $key,
				"duration" => ArrayHelper::get($options,"duration",3000), // default : 3000
				"dependencyKeys" => $dependencyKeys
			);
			ob_start();
		}
		return !$hasCache;
		
	}

	public static function endFragment(){
		if(!self::getCacheEnabled())
			return false;
		$value = ob_get_clean();
		echo $value;
		$item = array_pop(self::$tempFragmentCaches);
		$dependency = self::getDependency($item["dependencyKeys"]);
		Yii::app()->cache->set($item["key"],$value,$item["duration"],$dependency);
	}

	public static function getKeyForUser($dependencyKey,$userId=null){
		if($userId===null){
			$userId = Yii::app()->user->isGuest ? "guest" : Yii::app()->user->id;
		}
		return "users/$userId/$dependencyKey";
	}

	public static function getKeyForLogin($dependencyKey){
		$loggedIn = Yii::app()->user->isGuest ? "logged_in" : "logged_out";
		return $loggedIn . "/" . $dependencyKey;
	}

	public static function getKeyForLanguage($dependencyKey,$lang=null){
		if($lang===null){
			$lang = MultiLanguage::getCurrentLanguage();
		}
		return "$dependencyKey/$lang";
	}

	public static function clearAllPage(){
		self::setLastUpdatedTimeOfDependencyKey(self::PAGE_FRAGMENT_KEY,time());
		self::setLastUpdatedTimeOfDependencyKey(self::HTTP_CACHE_KEY,time());
	}

	public static function clearAllHttpCache(){
		self::setLastUpdatedTimeOfDependencyKey(self::HTTP_CACHE_KEY,time());
	}

	public static function clearAllHttpCacheForUser($userId=null){
		if($userId===null){
			$userId = Yii::app()->user->isGuest ? "guest" : Yii::app()->user->id;
		}
		self::setLastUpdatedTimeOfDependencyKey(self::getKeyForUser(self::HTTP_CACHE_KEY),time());
	}

	public static function clearAllHttpCacheForLanguage($userId=null){
		if($userId===null){
			$userId = Yii::app()->user->isGuest ? "guest" : Yii::app()->user->id;
		}
		self::setLastUpdatedTimeOfDependencyKey(self::getKeyForLanguage(self::HTTP_CACHE_KEY),time());
	}

	public static function get($key,$duration,$dependencyKeys,$callback){
		$value = Yii::app()->cache->get($key);
		if($value===false){
			$value = $callback();
			Yii::app()->cache->set($key,$value,$duration,self::getDependency($dependencyKeys));
			return $value;
		} else {
			return $value;
		}
	}
}

CacheHelper::$tempFragmentCaches = array();