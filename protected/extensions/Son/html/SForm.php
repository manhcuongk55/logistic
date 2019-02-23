<?php
class SForm extends CFormModel {
	var $defaultConfig = array(
		"title" => null,
		"inputs" => array(
			"__item" => array(
				"rules" => false,
				"value" => "",
				"model" => false,
				"displayInput" => true,
				"pressEnterToSubmit" => true,
				"itemModelClass" => false,
				"criteria" => array(),
				"primaryField" => "id"
			)
		),
		"models" => array(),
		"method" => "post",
		"viewParams" => array(
			"submitHtmlAttributes" => array(
				"class" => "btn btn-primary"
			),
		),
		"validate" => true,
		"uploadEnabled" => false,
		"uploadCheck" => false,
		"ajaxSubmit" => false,
		"confirm" => false,
		"htmlAttributes" => array(),
		"actionUrl" => "",
		"pressEnterToSubmit" => true,
		"view" => "ext.Son.html.views.form.form-bootstrap",
		"preView" => null,
		"postView" => null,
		"multipleLanguageEnabled" => false,
		"replaceAfterSuccess" => false,
		"csrfProtect" => false,
		"csrfAjaxRegenerate" => false
	);
	var $config = array();
	var $__data = array();
	public $__error = false;

	public function __get($key){
		if(isset($this->$key))
			return parent::__get($key);
		return ArrayHelper::get($this->__data,$key,"");
	}

	public function __set($key,$value){
		if(isset($this->$key))
			return parent::__set($key,$value);
		$this->__data[$key] = $value;
	}

	public function __construct($config=null){
		parent::__construct();
		if($config){
			$this->config = $config;
		}
		$this->formInit();
	}

	protected function formInit(){
		if($tempConfig=$this->getConfig()){
			$this->config = $tempConfig;
		}
		$this->config = array_replace_recursive($this->defaultConfig,$this->config);
		ArrayHelper::processItemDefault($this->config["inputs"]);
		foreach($this->config["inputs"] as $key => $input) {
			$this->$key = $input["value"];
		}
	}


	public function run(){
		return $this->handle();
	}

	public function handle(){
		if(Input::isPost() && $this->config["csrfProtect"] && !$this->validateCsrfToken()){
			$this->addError("global",l("form","Form is pending for too long. Please refresh your browser"));
			$this->setError(true);
			if($this->config["ajaxSubmit"]){
				$this->returnJson();
			}
			return false;
		}
		$result = $this->onHandleInput();
		if($result && $this->config["csrfProtect"] && !$this->isError()){
			$this->clearCsrfToken();
		}
		return $result;
	}

	public function readInput($multipleError=false){
		$formValid = true;
		foreach($this->config["inputs"] as $inputName => $input){
			if($input["model"] || $input["itemModelClass"])
				continue;
			$hasError = false;
			if($this->config["method"]=="post"){
				list($this->$inputName,$hasError,$firstErrorMessage,$errors) = Input::post($inputName,$input,"return");
			} else {
				list($this->$inputName,$hasError,$firstErrorMessage,$errors) = Input::get($inputName,$input,"return");
			}
			if($hasError){
				$formValid = false;
				foreach($errors as $error){
					$this->addError($inputName,$error);
				}
			}
			if($hasError && $multipleError)
				return false;
		}
		if(!$formValid){
			$this->setError(true);
		}
		return $formValid;
	}

	public function readInputToModel($multipleError=false,$validateModels=false,$validateItems=true){
		foreach($this->config["models"] as $model => $className){
			if(!$this->$model)
				$this->$model = new $className();
		}
		$formValid = true;
		foreach($this->config["models"] as $model => $className){
			$attrs = array();
			if($this->config["method"]=="post"){
				$attrs = Input::post($model,array());
			} else {
				$attrs = Input::get($model,array());
			}
			foreach($this->config["inputs"] as $inputName => $input){
				if($input["model"]!=$model)
					continue;
				/*if($this->$model->fileConfig && isset($this->$model->fileConfig[$inputName])){
					continue;
				}*/
				$isFile = $this->$model->fileConfig && isset($this->$model->fileConfig[$inputName]);
				if(!isset($input["label"])){
					$input["label"] = $this->$model->getAttributeLabel($inputName);
				}
				list($value,$hasError,$firstErrorMessage,$errors) = Input::getInput($attrs,$inputName,$input,"return");
				if($value!==null){
					$this->$model->$inputName = $value;
				}
				if($isFile)
					continue;
				if($hasError){
					foreach($errors as $error){
						$this->$model->addError($inputName,$error);
					}
					$formValid = false;
				}
				if($hasError && !$multipleError){
					$this->setError(true);
					return false;
				}
			}
			if($this->config["uploadEnabled"] || $this->config["uploadCheck"]){
				$this->$model->fileGetFromInput($model);
			}
			if($validateModels){
				$result = $this->$model->validate();
				if(!$result){
					$this->setError(true);
					if(!$multipleError){
						return false;
					}
				}
			}
		}

		if($formValid || $multipleError){
			foreach($this->config["inputs"] as $inputName => $input){
				if(!$input["itemModelClass"])
					continue;
				$this->$inputName = array();
				$itemModelClass = $input["itemModelClass"];
				$itemArr = array();
				if($this->config["method"]=="post"){
					$itemArr = Input::post($inputName,array());
				} else {
					$itemArr = Input::get($inputName,array());
				}
				if(!$itemArr){
					$itemArr = array();
				}
				$primaryField = $input["primaryField"];
				$criteria = $input["criteria"];
				$this->$inputName = array();
				foreach($itemArr as $i => $itemAttrs){
					$item = null;
					if(isset($itemAttrs[$primaryField]) && $itemAttrs[$primaryField]){
						$item = $itemModelClass::model()->findByPk($itemAttrs[$primaryField],$criteria);
						if(!$item){
							continue;
						}
					} else {
						$item = new $itemModelClass();
					}
					foreach($input["items"] as $itemAttrName => $itemAttrConfig){
						$items = $input["items"];
						/*if(isset($item->fileConfig[$itemAttrName])){
							continue;
						}*/
						if(isset($itemAttrConfig["value"])){
							$value = $itemAttrConfig["value"];
							if(is_callable($value)){
								$v = $value($this);
							} else {
								$v = $value;
							}
							$item->$itemAttrName = $v;
							continue;
						}
						try {
							list($value,$hasError,$firstErrorMessage,$errors) = Input::getInput($itemAttrs,$itemAttrName,$itemAttrConfig,"return");
							if($value!==null){
								$item->$itemAttrName = $value;
							}
						} catch(Exception $ex) {
							//echo $ex->getMessage();
						}
						if($hasError){
							foreach($errors as $error){
								$item->addError($itemAttrName,$error);
							}
							$formValid = false;
						}
						if($hasError && !$multipleError){
							return false;
						}
					}
					if($item->getIsNewRecord() && $item->delete_flag){
						continue;
					}
					if($this->config["uploadEnabled"] || $this->config["uploadCheck"]){
						$item->fileGetFromInput(array($inputName,$i));
					}
					$arr = $this->{$inputName};
					$arr[] = $item;
					$this->{$inputName} = $arr;
				}
			}
		}

		if($validateItems){
			if($formValid){
				$formValid = $this->validateModels();
			}
		}
		if(!$formValid){
			$this->setError(true);
		}
		return $formValid;
	}

	public function validateModels(){
		foreach($this->config["inputs"] as $inputName => $input){
			if(!$input["itemModelClass"])
				continue;
			foreach($this->$inputName as $item){
				if($item->delete_flag){
					continue;
				}
				$validateProperties = ArrayHelper::get($input,"validateProperties");
				$result = true;
				if(is_array($validateProperties)){
					$result = $item->validate($validateProperties);
				} else if($validateProperties) {
					$result = $item->validate();
				}
				if(!$result){
					$this->setError(true);
					$this->addError("global",$item->getFirstError());
					return false;
				}
			}
		}
		return true;
	}

	public function saveModels($saveModels=true){
		//print_r(json_decode(CJSON::encode($this->announcement_images))); die();
		if($saveModels){
			foreach($this->config["models"] as $model => $className){
				$this->$model->save();
			}
		}
		foreach($this->config["inputs"] as $inputName => $input){
			if(!$input["itemModelClass"])
				continue;
			if(!is_array($this->$inputName)){
				var_dump($inputName);
				var_dump($this->$inputName);
				die();
			}
			foreach($this->$inputName as $item){
				if($item->delete_flag){
					$result = $item->delete();
				} else {
					foreach($input["items"] as $itemAttrName => $itemAttrConfig){
						if(isset($itemAttrConfig["value"])){
							$value = $itemAttrConfig["value"];
							if(is_callable($value)){
								$v = $value($this);
							} else {
								$v = $value;
							}
							$item->$itemAttrName = $v;
							continue;
						}
					}
					$result = $item->save();
					if(!$result){
						//print_r($item->file_base64_flag); die();
						//print_r($item->getAttributes());
						echo $item->getFirstError(); die();
					}
				}
			}
		}
		return true;
	}

	public function replaceModelWith($model,$newModel,$attrs=null){
		if($attrs){
			foreach($attrs as $attr){
				if($this->$model->$attr!==null)
					$newModel->$attr = $this->$model->$attr;
			}
		} else {
			foreach($this->config["inputs"] as $inputName => $attr){
				if($this->$model->$attr!==null)
					$newModel->$attr = $this->$model->$attr;
			}
		}
		if($this->config["uploadEnabled"] || $this->config["uploadCheck"]){
			$newModel->fileList = $this->$model->fileList;
			$newModel->fileConfig = $this->$model->fileConfig;
			$newModel->fileUpdateFileNameAfterSave = $this->$model->fileUpdateFileNameAfterSave;
			foreach($newModel->fileList as $key => $file){
				$file->model = $newModel;
			}
		}
		$this->$model = $newModel;
	}

	public function arrayCount($name){
		$count = 0;
		foreach($this->$name as $item){
			if($item->getIsNewRecord() && $item->delete_flag){
				continue;
			} else if($item->delete_flag) {
				$count--;
			} else {
				$count++;
			}
		}
		return $count;
	}

	public function getInput($inputName){
		return ArrayHelper::get($this->config["inputs"],$inputName);
	}

	// Get Property

	public function l($message){
		if($formName = $this->config["multipleLanguageEnabled"]){
			$message = l("forms/$formName",$message);
		}
		return $message;
	}

	public function l_($message){
		echo $this->l($message);
	}

	public function inputLabel($inputName){
		$input = $this->getInput($inputName);
		$self = $this;
		$label = ArrayHelper::get($input,"label",function() use ($inputName,$input,$self){
			if($modelName = $input["model"]){
				$modelClass = $self->config["models"][$modelName];
				return $modelClass::model()->getAttributeLabel($inputName);
			}
			return Yii::t("app",ucfirst($inputName));
		},true);
		return $this->l($label);
	}

	public function isError(){
		return $this->__error;
	}

	public function setError($err){
		$this->__error = $err;
	}

	public function inputError($inputName){
		$arr = $this->inputErrors($inputName);
		return ArrayHelper::get($arr,0,"");
	}

	public function inputErrors($inputName){
		$input = $this->config["inputs"][$inputName];
		$modelName = $input["model"];
		if($modelName){
			if(!$this->$modelName)
				return null;
			$arr = $this->$modelName->getErrors();
			return ArrayHelper::get($arr,$inputName,array());
		} else {
			$arr = $this->getErrors();
			return ArrayHelper::get($arr,$inputName,array());
		}
	}

	public function otherError($multiple=false,$separateError=false){
		$error = "";
		if($multiple){
			$error = array();
		}
		foreach($this->config["models"] as $model => $className){
			if(!$this->$model)
				continue;
			$errorList = $this->$model->getErrors();
			foreach($errorList as $inputName => $errors){
				if(!isset($this->config["inputs"][$inputName])){
					// other attribute
					if(!$multiple)
					{
						return $errors[0];
					}
					if($separateError){
						$error[$inputName] = $errors;
					} else {
						$error = array_merge($error,$errors);
					}
					
				}
			}
		}
		if($this->hasErrors()){
			$errorList = $this->getErrors();
			foreach($errorList as $inputName => $errors){
				if(!$multiple)
				{
					return $errors[0];
				}
				if($separateError){
					$error["__form"] = $errors;
				} else {
					$error = array_merge($error,$errors);
				}
			}
		}
		return $error;
	}

	public function getFirstError(){
		return $this->getAllErrors(false);
	}

	public function getAllErrors($multiple=true,$separateError=true){
		$error = "";
		if($multiple){
			$error = array();
		}
		foreach($this->config["models"] as $model => $className){
			if(!$this->$model)
				continue;
			$errorList = $this->$model->getErrors();
			foreach($errorList as $inputName => $errors){
				if(!$multiple)
				{
					return $errors[0];
				}
				if($separateError){
					$error[$inputName] = $errors;
				} else {
					$error = array_merge($error,$errors);
				}
			}
		}
		if($this->hasErrors()){
			$errorList = $this->getErrors();
			foreach($errorList as $inputName => $errors){
				if(!$multiple)
				{
					return $errors[0];
				}
				if($separateError){
					$error["__form"] = $errors;
				} else {
					$error = array_merge($error,$errors);
				}
			}
		}
		return $error;
	}

	public function viewParam($paramName,$default=null){
		return ArrayHelper::get($this->config["viewParams"],$paramName,$default);
	}

	// HTML

	public function open($htmlAttributes=array()){
		$actionUrl = $this->getActionUrl();
		$method = $this->config["method"];
		if($this->config["uploadEnabled"]){
			$method = "post";
			ArrayHelper::set($htmlAttributes,"enctype","multipart/form-data");
		}
		if($this->config["replaceAfterSuccess"]){
			$htmlAttributes["data-replace-after-success"] = "true";
		}

		$htmlAttributes = array_merge($this->config["htmlAttributes"],$htmlAttributes);

		if($this->config["confirm"]){
			ArrayHelper::set($htmlAttributes,"data-confirm",$this->config["confirm"]);
		}

		if($this->config["ajaxSubmit"]==false){
			if($this->config["validate"] || $this->config["confirm"]){
				$htmlAttributes["data-type"] = "validate";
				// then change the submit button also
			}
		} else {
			if($this->config["uploadEnabled"]){
				$htmlAttributes["data-type"] = "iframe";
				unset($htmlAttributes["onsubmit"]);
				// then change the submit button also
			} else {
				$htmlAttributes["data-type"] = "ajax";
				$htmlAttributes["onsubmit"] = '$__$.__ajax(this,event)';
			}
		}

		if(!$this->config["pressEnterToSubmit"])
			$htmlAttributes["onkeypress"] = "return event.keyCode != 13;";

		if($this->config["csrfProtect"] && $this->config["csrfAjaxRegenerate"]){
			$htmlAttributes["data-csrf"] = "";
		}

		echo CHtml::beginForm($actionUrl,$method,$htmlAttributes);

		if($this->config["csrfProtect"]){
			echo CHtml::hiddenField($this->getCsrfName(),$this->generateCsrfToken(),array(
				"csrf" => ""
			));
		}
	}

	public function close(){
		echo CHtml::endForm();
	}

	public function submitButton($content,$htmlAttributes=array()){
		$formUpload = $this->config["ajaxSubmit"] && $this->config["uploadEnabled"];
		$formValidate = !$this->config["ajaxSubmit"] && ($this->config["validate"] || $this->config["confirm"]);
		if($formUpload || $formValidate){
			$htmlAttributes["type"] = "button";
			$htmlAttributes["onclick"] = '$__$.__ajax(this.form,event)';
		} else {
			$htmlAttributes["type"] = "submit";
		}
		echo CHtml::htmlButton($this->l($content),$htmlAttributes);
	}

	public function inputField($inputName,$htmlAttributes=array()){
		$input = $this->getInput($inputName);
		/*if(!$input["displayInput"]){
			return;
		}*/
		$modelName = $input["model"];
		$arr = ArrayHelper::get($input,"htmlAttributes",array());
		$htmlAttributes = array_merge($arr,$htmlAttributes);
		$value = "";
		if($modelName){
			if($this->$modelName){
				$value = $this->$modelName->getRaw($inputName);
			}
			$name = $modelName . "[" . $inputName . "]";
		} else {
			$value = $this->$inputName;
			$name = $inputName;
		}
		$type = ArrayHelper::get($input,"type");
		$config = ArrayHelper::get($input,"config");
		if($type!="textarea" && !$input["pressEnterToSubmit"]){
			$htmlAttributes["onkeypress"] = "return event.keyCode != 13;";
		}
		Son::load("SPlugin")->renderInput($name,$value,$type,$config,$htmlAttributes);
	}

	protected function getView(){
		return $this->config["view"];
	}

	public function renderPreView(){
		if($preView = $this->config["preView"]){
			$this->renderSubView($preView);
		}
	}

	public function renderPostView(){
		if($postView = $this->config["postView"]){
			$this->renderSubView($postView);
		}
	}

	public function renderSubView($arr){
		foreach($arr as $i => $view){
			$data = array(
				"form" => $this
			);
			if(!is_numeric($i)){
				$moreData = $view;
				$view = $i;
				$data = array_replace_recursive($data, $moreData);
			}
			Util::controller()->renderPartial($view,$data);
		}
	}

	public function render($view=null,$viewParams=null,$return=false){
		if($view==null){
			$view = $this->getView();
		}
		if($viewParams){
			$this->config["viewParams"] = array_replace_recursive($this->config["viewParams"], $viewParams);
		}
		return Util::controller()->renderPartial($view,array(
			"form" => $this
		),$return);
	}

	public function renderPage($view=null,$viewParams=null,$return=false){
		if($view==null){
			$view = $this->getView();
		}
		if($viewParams){
			$this->config["viewParams"] = array_replace_recursive($this->config["viewParams"], $viewParams);
		}
		return Util::controller()->render($view,array(
			"form" => $this
		),$return);
	}

	public function returnJson($successData=null,$includeAllErrors=false){
		if($this->isError()){
			Output::returnJsonError($this->getFirstError(),200,1,$includeAllErrors ? $this->getAllErrors() : null);
		} else {
			if($successData===null){
				$successData = array();
			}
			if($this->config["replaceAfterSuccess"]){
				$successData["html"] = $this->render(null,null,true);
			}
			if($this->config["csrfProtect"] && $this->config["csrfAjaxRegenerate"]){
				$successData["csrf"] = $this->generateCsrfToken();
			}
			Output::returnJsonSuccess($successData);
		}
	}

	public function setCurrentUrl(){
		$this->config["actionUrl"] = Util::controller()->createUrl();
	}

	protected function getActionUrl(){
		return $this->config["actionUrl"];
	}

	protected function getConfig(){
		return null;
	}

	protected function onHandleInput(){
		if($onHandleInput = ArrayHelper::get($this->config,"onHandleInput")){
			return $onHandleInput($this);
		}
		return false;
	}

	protected function generateCsrfToken(){
		$token = md5(uniqid(rand() . time(), TRUE));
		$arrToken = Util::session("csrf_tokens",array());
		$arrToken[$token] = time();
		Util::setSession("csrf_tokens",$arrToken);
		return $token;
	}

	protected function getCsrfName(){
		return "csrf_token";
	}

	protected function getCsrfExpiredTime(){
		return 3600 * 24; // 1 day
	}

	protected function validateCsrfToken(){
		$arrToken = Util::session("csrf_tokens",array());
		$token = Input::post($this->getCsrfName());
		if(!$token){
			return false;
		} else if(!isset($arrToken[$token])){
			return false;
		} else {
			$tokenTime = $arrToken[$token];
			if($tokenTime + $this->getCsrfExpiredTime() < time()){
				unset($arrToken[$token]);
				Util::setSession("csrf_tokens",$arrToken);
				return false;
			} else {
				return true;
			}
		}
	}

	protected function clearCsrfToken($token=null){
		if($token==null){
			$token = Input::post($this->getCsrfName());
		}
		$arrToken = Util::session("csrf_tokens",array());
		if(isset($arrToken[$token])){
			unset($arrToken[$token]);
		}
		Util::setSession("csrf_tokens",$arrToken);
	}
}