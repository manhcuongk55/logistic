<?php
class CronController extends Controller {
	protected function beforeAction($action){
		if(!parent::beforeAction($action))
			return false;
		if(!isset($_GET["password"]) || $_GET["password"]!=Util::param2("background_task","password")){
			echo "invalid request";
			return false;
		}
		return true;
	}

	public function actionHourly(){
		$this->cleanDir("temp");
		$this->cleanDir("nonpublic/temp");
	}

	function cleanDir($dir){
		$path = Util::getFullPath($dir);
		$files = array_diff(scandir($path), array('.', '..'));
		$now = time();
		foreach ($files as $file){
			$file = $path . "/" . $file;
			try {
				if ($now - filemtime($file) >= 60 * 60 * 3){ // 3 hours
					Util::deleteFile($file);
				}
			} catch(Exception $ex){

			}
		}
	}
}