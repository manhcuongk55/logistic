<?php
class BackgroundTask {
	public static function runInBackground($task,$data=array()){
		$data["password"] = Util::param2("background_task","password");
		$data["task"] = $task;
		$dataStr = http_build_query($data);
		$url = Yii::app()->controller->createAbsoluteUrl("/task/background_task?$dataStr");
		$ctx = stream_context_create(array('http'=>
		    array(
		        'timeout' => 1,  //1200 Seconds is 20 Minutes
		    )
		));
		try {
			Util::log("SEND: $url","background_task_url");
			@file_get_contents($url,false,$ctx);
		} catch(Exception $ex){
			
		}
	}

	public static function handle(){
		$url = Yii::app()->request->requestUri;
		Util::log("RECEIVED: $url","background_task_url");
		if(!isset($_GET["password"]) || $_GET["password"]!=Util::param2("background_task","password")){
			echo "invalid request";
			return;
		}
		$task = isset($_GET["task"]) ? $_GET["task"] : null;
		if(!$task)
			return;
		if(isset($_GET["trace"])){
			self::run($task,$_GET);
		} else {
			try {
				self::run($task,$_GET);
				Util::log("DONE: $url","background_task_url");
			} catch(Exception $ex){
				Util::log("ERROR: $url","background_task_url");
				Util::log("------------------------------","background_task_exception");
				Util::log($ex->getMessage(),"background_task_exception");
				Util::log($ex->getTraceAsString(),"background_task_exception");
			}
		}
		
	}

	public static function run($task,$arr=array()){
		$data = $arr;
		$file = Yii::getPathOfAlias($task) . ".php";
		extract($data);
		include($file);
	}
}