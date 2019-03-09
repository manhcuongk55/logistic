<?php
abstract class SList {
	var $configDefault = array(
		"class" => array(
			"pagination" => "SListPagination",
			"html" => "SListHtml",
			"model" => "SListModel",
			"item" => "SListItem"
		),
		"fields" => array(
			"__item" => array(
				"order" => false,
				"label" => null,
				"inlineEditEnabled" => false,
				"inputType" => "text",
				"displayType" => "text",
				"advancedSearchInputType" => false,
				"exportType" => "",
				"select" => true,
				"value" => null
			),
		),
		"actions" => array(
			"action" => array(
				"update" => false,
				"delete" => false,
				"insert" => false,
				"data" => array(
					"search" => array(
					),
					"advancedSearch" => false,
					"order" => false,
					"limit" => false,
					"offset" => true,
					"page" => true,
					"export" => false
				)
			),
			"actionTogether" => array(
				"deleteTogether" => false	
			),
			"extendedAction" => array(
			),
			"extendedActionTogether" => array(
			),
			"method" => "ajax"
		),
		"model" => array(),
		"view" => array(),
		"pagination" => array(),
		"mode" => "php",
		"baseUrl" => "?",
		"onRun" => false,
		"autoRenderPage" => true
	);
	var $config = array();
	//
	var $inputErrorWarningType = null;
	//
	var $successMessages = array();
	var $errorMessages = array();
	//
	var $alias = null;

	public $pagination = null;
	public $html = null;
	public $model = null;

	public function __construct($config=null){
		if($config){
			$this->config = $config;
		}
		$this->init();
	}

	public function init(){
		if($tempConfig=$this->getConfig()){
			$this->config = $tempConfig;
		}
		$this->config = array_replace_recursive($this->configDefault, $this->config);
		ArrayHelper::processItemDefault($this->config["fields"]);
		$this->inputErrorWarningType = $this->isModePhp() ? "html" : "json";
		if(!$this->alias){
			$this->alias = ArrayHelper::get($this->config,"alias",get_class($this));
		}
		Son::loadFile("ext.Son.super-modules.list.code.advanced_search_register");
	}

	public function getPagination(){
		if($this->pagination==null){
			$paginationClass = $this->config["class"]["pagination"];
			$this->pagination = new $paginationClass($this->config["pagination"],$this);
		}
		return $this->pagination;
	}

	public function getHtml(){
		if($this->html==null){
			$htmlClass = $this->config["class"]["html"];
			$this->html = new $htmlClass($this->config["view"],$this);
		}
		return $this->html;
	}

	public function getModel(){
		if($this->model==null){
			$modelClass = $this->config["class"]["model"];
			$this->model = new $modelClass($this->config["model"],$this);
		}
		return $this->model;
	}

	// outside function

	public function addCondition($arr){
		$this->getModel()->conditions = array_replace_recursive($this->getModel()->conditions, $arr);
	}

	public function setDynamicInput($inputName, $value){
		$this->getModel()->dynamicInputs[$inputName] = $value;
	}

	// public functions

	public function render(){
		$this->getHtml()->render();
	}

	public function run(){
		if($onRun = $this->config["onRun"]){
			$onRun($this);
		}
		$action = Input::get("action","html_view");
		switch($action){
			case "html_view":
				return $this->actionHtmlView();
			case "data":
				$this->actionData();
				break;
			case "update":
				$this->actionUpdate();
				break;
			case "insert":
				$this->actionInsert();
				break;
			case "delete":
				$this->actionDelete();
				break;
			case "delete_together":
				$this->actionDeleteTogether();
				break;
			case "update_inline":
				$this->actionUpdateInline();
				break;
			case "extended_action":
				$this->actionExtendedAction();
				break;
			case "extended_action_together":
				$this->actionExtendedActionTogether();
				break;
			case "export_download":
				$this->actionExportDownload();
				break;
			default:
				Output::show404();
				return false;
		}
		return true;
	}

	// action functions

	protected function actionHtmlView(){
		// render page
		if($this->isModePhp() || $this->getModel()->config["preloadData"]){
			$this->getModel()->loadDataWithInput();
		}
		if($this->config["autoRenderPage"]){
			$this->getHtml()->renderPage();
			return true;
		}
		return false;
	}

	protected function actionData(){
		switch($this->getMode()){
			case "angular":
				$this->getModel()->loadDataWithInput();
				$returnData = array(
					"data" => $this->data,
					"num_row_total" => $this->dataNumRowTotal
				);
				if($this->pagination){
					$returnData["pagination"] = $this->pagination->getCalculatedResult();
				}
				Output::returnJsonSuccess($returnData);
				break;
			case "jquery":
				$this->getModel()->loadDataWithInput();
				$returnData = array(
					"data_html" => $this->getHtml()->renderItems(true),
					"num_row_total" => $this->getModel()->dataNumRowTotal
				);
				if($this->pagination){
					$returnData["pagination_html"] = $this->pagination->render(true);
				}
				Output::returnJsonSuccess($returnData);
				break;
			default: // php
				Output::showError("data action is not allowed in php mode!");
				break;
		}
	}

	protected function actionUpdate(){
		if(!$this->config["actions"]["action"]["update"]){
			Output::showPermissionDenied("update action prohibited");
			return;
		}
		$form = $this->getModel()->getUpdateForm();
		$form->handle();
		if(!$form->isError()){
			$form->returnJson();
		} else {
			$this->returnError($form->getFirstError());
		}
	}

	protected function actionInsert(){
		if(!$this->config["actions"]["action"]["insert"]){
			Output::showPermissionDenied("insert action prohibited");
			return;
		}
		$form = $this->getModel()->getInsertForm();
		$form->handle();
		if(!$form->isError()){
			$form->returnJson();
		} else {
			$this->returnError($form->getFirstError());
		}
	}

	protected function actionDelete(){
		if(!$this->config["actions"]["action"]["delete"]){
			Output::showPermissionDenied("delete action prohibited");
			return;
		}
		$item = $this->getModel()->loadItemWithInput();
		$item->scenario = $this->getModel()->config["deleteScenario"];
		$result = $item->delete();
		if(!$result){
			$this->returnError($item->getFirstError());
			return;
		}
		$this->returnSuccess("Item deleted successfully!");
	}

	protected function actionDeleteTogether(){
		if(!$this->config["actions"]["actionTogether"]["deleteTogether"]){
			Output::showPermissionDenied("delete together action prohibited");
			return;
		}
		$ids = $this->getModel()->getIdArrayFromInput();
		$modelClass = $this->config["model"]["class"];
		$criteria = new CDbCriteria();
		$criteria->addInCondition($this->config["model"]["primaryField"],$ids);
		$itemCount = $modelClass::model()->deleteAll($criteria);
		$this->returnSuccess("$itemCount " . $itemCount==1 ? "item" : "items" . " has been deleted successfully");
	}

	protected function actionUpdateInline(){
		$prop = Input::post("prop");
		$value = Input::post("value");
		if(!$prop || $value===null){
			Output::showError("prop or value input missing");
			return;
		}
		$field = ArrayHelper::get($this->config["fields"],$prop);
		if(!$field){
			Output::showError("invalid inline update field");
			return;
		}
		if(!$field["inlineEditEnabled"]){
			Output::showPermissionDenied("inline update action for the field $prop prohibited");
			return;
		}
		$item = $this->getModel()->loadItemWithInput();
		$item->$prop = $value;
		$result = $item->save(true,array($prop));
		if(!$result){
			$this->returnError($item->getFirstError(),$item->getErrors());
			return;
		}
		$this->returnSuccess("Propety updated successfully");
	}

	protected function actionExtendedAction(){
		$actionName = Input::get("action_name");
		$actionFunction = null;
		if(!$actionName || !($actionFunction = ArrayHelper::get($this->config["actions"]["extendedAction"],$actionName))){
			Output::showPermissionDenied("this action is not available");
			return;
		}
		$success = null;
		$message = null;
		$data = null;
		if(is_callable($actionFunction)){
			$item = $this->getModel()->loadItemWithInput();
			$arr = $actionFunction($item);
			$success = $arr[0];
			$message = ArrayHelper::get($arr,1,"");
			$data = ArrayHelper::get($arr,2);
			
		} else {
			$actionForm = $this->getModel()->getExtendedActionForm($actionName,$actionFunction);
			$actionForm->handle();
			$success = !$actionForm->isError();
			if(!$success){
				$message = $actionForm->getFirstError();
			}
		}
		if(!$success){
			$this->returnError($message,$data);
		} else {
			$this->returnSuccess($message,$data);
		}
	}

	protected function actionExtendedActionTogether(){
		$actionName = Input::get("action_name");
		$actionFunction = null;
		if(!$actionName || !($actionConfig = ArrayHelper::get($this->config["actions"]["extendedActionTogether"],$actionName))){
			Output::showPermissionDenied("this action is not available");
			return;
		}
		$actionFunction = $actionConfig[0];
		$success = null;
		$message = null;
		$data = null;
		if(is_callable($actionFunction)){
			$ids = $this->getModel()->getIdArrayFromInput();
			$arr = $actionFunction($ids);
			$success = $arr[0];
			$message = ArrayHelper::get($arr,1,"");
			$data = ArrayHelper::get($arr,2);
		} else {
			$actionForm = $this->getModel()->getExtendedActionTogetherForm($actionName,$actionFunction);
			$actionForm->idArray = explode(",", $actionForm->ids);
			$actionForm->handle();
			$success = !$actionForm->isError();
			if(!$success){
				$message = $actionForm->getFirstError();
			}
		}
		if(!$success){
			$this->returnError($message,$data);
		} else {
			$this->returnSuccess($message,$data);
		}
	}
	
	protected function actionExportDownload(){
		if(!($config = $this->config["actions"]["action"]["data"]["export"])){
			Output::showPermissionDenied("export action prohibited");
			return;
		}
		$exportType = Input::get("export_type",array(
			"rules" => array(
				array(
					"in", "range" => $config["types"], "allowEmpty" => false, "message" => "Export type is not supported"
				)
			),
			"label" => "Export type"
		),"html");
		$this->getModel()->loadDataWithInput(true);
		//
		$data = array();
		$data[0] = array();
		foreach($config["columns"] as $fieldName){
			$data[0][] = $this->getLabel($fieldName);
		}
		foreach($this->getModel()->data as $row){
			$item = array();
			foreach($config["columns"] as $fieldName){
				$field = $this->config["fields"][$fieldName];
				$val = $row->$fieldName;
				$itemExportType = $field["exportType"];
				if(is_callable($itemExportType)){
					$val = $itemExportType($row);
				} else {
					switch($itemExportType){
						case "url":
							$val = $row->url($fieldName);
							break;
						case "dropdownList":
							$val = $row->listGetLabel($fieldName,$val);
							break;
						case "boolean":
							$val = $val ? "true" : "false";
							break;
						case "timestamp":
							$val = date("d/m/Y H:i:s",$val);
							break;
						default:
							break;
					}
				}
				$item[] = $val;
			};
			$data[] = $item;
		}
		$fileName = $config["name"].'_'.date("d-m-Y");
		if($exportType=="excel"){
			Output::downloadExcel($data,$fileName);
		} elseif($exportType=="csv"){
			Output::downloadCsv($data,$fileName);
		}
	} 

	// protected functions

	public function returnSuccess($message=null,$data=null){
		if($this->config["actions"]["method"]=="ajax"){
			Output::returnJsonSuccess($data,$message);
		} else {
			$successMessages[] = $message;
		}
	}

	public function returnError($message,$data=null){
		if($this->config["actions"]["method"]=="ajax"){
			Output::returnJsonError($message,$data);
		} else {
			$errorMessages[] = $message;
		}
	}

	public function getMode(){
		return $this->config["mode"];
	}

	public function isModePhp(){
		return $this->getMode()=="php";
	}

	public function isModeJquery(){
		return $this->getMode()=="jquery";
	}

	public function isModeAngular(){
		return $this->getMode()=="angular";
	}

	public function getLabel($attr){
		$model = $this->getModel()->config["class"];
		$label = $this->config["fields"][$attr]["label"];
		if(!$label)
			$label = $model::model()->getAttributeLabel($attr);
		if(!$label){
			$label = ucfirst($attr);
		}
		return $label;
	}

	// abstract and virtual functions

	protected function getConfig() { return null; }

	var $baseUrl = false;
	public function getBaseUrl(){
		if($this->baseUrl===false){
			$url = $this->config["baseUrl"];
			if(is_callable($url))
				$url = $url();
			if($this->getModel()->config["dynamicInputs"]){
				$url = Util::urlAppendParams($url,$this->getModel()->dynamicInputs);
				$url .= "&";
			}
			$this->baseUrl = $url;
		}
		return $this->baseUrl;
	}

	var $baseUrlWithoutDynamicInputs = false;
    public function getBaseUrlWithoutDynamicInputs(){
        if($this->baseUrlWithoutDynamicInputs===false){
            $url = $this->config["baseUrl"];
            if(is_callable($url))
                $url = $url();
            $this->baseUrlWithoutDynamicInputs = $url;
        }
        return $this->baseUrlWithoutDynamicInputs;
    }


}