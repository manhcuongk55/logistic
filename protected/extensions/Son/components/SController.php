<?php
class SController extends CController {
	public $data = array();
	var $pageTitle = "";
	var $mailTitle = "";
	var $defaultPluginLoaded = false;

	public function renderFragment($view,$data,$cacheKey,$dependencyKeys,$options=array()){
		if(CacheHelper::beginFragment($cacheKey,$dependencyKeys,$options)){
			$this->renderPartial($view,$data);
			CacheHelper::endFragment();
		}
	}

	public function renderFragmentWithHttpCacheInfo($view,$data,$options=array()){
		$info = CacheHelper::$httpCacheInfo;
		foreach($options as $k => $v){
			$info["options"][$k] = $v;
		}
		return $this->renderFragment($view,$data,$info["key"],$info["dependencyKeys"],$info["options"]);
	}

	public function render($view,$data=null,$return=false){
		// asset
		Son::load("SAsset")->run();
		if($data===null){
			$data = $this->data;
		}
		return parent::render($view,$data,$return);
	}

	public function renderWithHttpCacheInfo($view,$data=null,$options=array()){
		$info = CacheHelper::$httpCacheInfo;
		foreach($options as $k => $v){
			$info["options"][$k] = $v;
		}
		return $this->renderWithCache($view,$data,$info["key"],$info["dependencyKeys"],$info["options"]);
	}

	public function renderWithCache($view,$data=null,$cacheKey,$dependencyKeys,$options=array()){
		Son::load("SAsset")->run();
		if($data===null){
			$data = $this->data;
		}

		if($this->beforeRender($view))
	    {
	    	ob_start();
	        $this->renderFragment($view,$data,$cacheKey,$dependencyKeys,$options);
	        $output = ob_get_clean();
	        if(($layoutFile=$this->getLayoutFile($this->layout))!==false)
	            $output=$this->renderFile($layoutFile,array('content'=>$output),true);

	        $this->afterRender($view,$output);

	        $output=$this->processOutput($output);

	    	echo $output;
	    }
	}

	public function renderPartial($view,$data=null,$return=false,$processOutput=false){
		if(!$this->defaultPluginLoaded){
			$this->defaultPluginLoaded = true;
			Son::loadFile("ext.Son.html.code.display_plugin_register");
			Son::loadFile("ext.Son.html.code.input_plugin_register");
		}
		return parent::renderPartial($view,$data,$return,$processOutput);
	}

	public function getThemePath($view){
		$themeName = Yii::app()->theme->name;
		return "webroot.themes.$themeName.views.$view";
	}

	public function renderTheme($view,$data=null,$return=false){
		return $this->render($this->getThemePath($view),$data,$return);
	}

	public function renderPartialTheme($view,$data=null,$return=false,$processOutput=false){
		return $this->renderPartial($this->getThemePath($view),$data,$return,$processOutput);
	}

	protected function doSearchModel(){
		$modelClass = Input::get("model",array(
			"rules" => array(
				array("required"),
				array("length","allowEmpty" => false, "min" => 1)
			)
		),"json");
		$searchTerm = Input::get("term","","json");
		$searchAttr = Input::get("attr",array(
			"rules" => array(
				array("required"),
				array("length","allowEmpty" => false, "min" => 1)
			)
		),"json");
		try {
			$modelClass = ucfirst($modelClass);
			$result = $modelClass::model()->listSearch($searchAttr,$searchTerm);
		} catch(Exception $e){
			Output::returnJsonError("Invalid request");
			return;
		}
		if($result===false)
		{
			Output::returnJsonError("Invalid request");
			return;
		}
		Output::returnJsonSuccess($result);
	}
}