<?php
class SListModel {
	var $configDefault = array(
		"class" => null,
		"primaryField" => "id",
		"conditions" => null,
		"onCriteria" => null,
		"with" => array(),
		"defaultQuery" => array(
			"orderBy" => "id",
			"orderType" => "desc",
			"limit" => 10,
			"page" => 1,
			"search" => "",
			"advancedSearch" => array(
			)
		),
		"dynamicInputs" => null,
		"preloadData" => true,
		"forms" => array(

		),
		"cacheDependency" => array(
			"options" => array(
				"duration" => 600 // 5 minutes
			)
		),
		"cacheLoadDataDisabled" => true,
		"insertScenario" => "insert_from_admin",
		"updateScenario" => "update_from_admin",
		"deleteScenario" => "delete_from_admin"
	);

	var $config = array();
	public $list = null;

	var $data = null;
	var $dataNumRowTotal = 0;
	var $inputs = array();
	var $query = array();
	var $conditions = array();
	var $dynamicInputs = array();
	var $foreignConfigArray = array();
	var $manualJoinForeignConfigArray = array();
	var $calculatedValueFieldArray = array();
	var $insertForm = null;
	var $updateForm = null;

	public function __construct($config=null,$list=null){
		$this->config = $this->configDefault;
		if($config!=null){
			$this->config = array_replace_recursive($this->config, $config);
		}
		$this->list = $list;
		$modelClass = $this->config["class"];
		foreach($this->list->config["fields"] as $fieldName => $field){
			if($foreignConfig = ArrayHelper::get($field,"foreignConfig"))
			{
				Son::load("SModelHelper")->addProperty($modelClass,$fieldName);
				$this->foreignConfigArray[$fieldName] = $foreignConfig;
			} elseif($manualJoinForeignConfig = ArrayHelper::get($field,"manualJoinForeignConfig")){
				//Son::load("SModelHelper")->addProperty($modelClass,$fieldName);
				//$this->manualJoinForeignConfigArray[$fieldName] = $manualJoinForeignConfig;
			}
			if($field["value"]!==null){
				$this->calculatedValueFieldArray[$fieldName] = $field["value"];
			}
		}
	}

	public function beginCache(){
		$cacheDependency = $this->config["cacheDependency"];
		if(!$cacheDependency)
			return false;
		return CacheHelper::beginFragment($cacheDependency["key"],$cacheDependency["options"]);
	}

	public function endCache(){
		$cacheDependency = $this->config["cacheDependency"];
		if(!$cacheDependency)
			return false;
		return CacheHelper::endFragment();
	}

	public function loadDataWithInput($withoutLimit=false){
		$modelClass = $this->config["class"];
		$paginationParam = null;
		if($this->list->config["pagination"])
			$paginationParam = array();
		$criteria = $this->getCriteriaFromInput($paginationParam);
		if($this->config["cacheLoadDataDisabled"]){
			$modelClass::model()->cacheLoadDataDisabled = true;
		}
		if($withoutLimit){
			$criteria->limit = -1;
		}
		$this->data = $modelClass::model()->findAll($criteria);
		foreach($this->data as $item){
			foreach($this->calculatedValueFieldArray as $fieldName => $valueFunction){
				if(is_callable($valueFunction)){
					$item->$fieldName = $valueFunction($item);
				} else {
					$item->$fieldName = $valueFunction;
				}
			}
			foreach ($this->foreignConfigArray as $fieldName => $foreignConfig) {
				$obj = $item;
				foreach($foreignConfig as $part){
					$obj = $obj->$part;
					if(!$obj)
						break;
				}
				$item->$fieldName = $obj;
			}
			$item->securityStartPreventXss();
		}
		//if(true){}
		$this->dataNumRowTotal = $modelClass::model()->count($criteria);
		if($this->list->config["pagination"])
			$this->list->getPagination()->calculate($paginationParam["baseUrl"],$this->dataNumRowTotal,$paginationParam["activePage"],$paginationParam["numItemPerPage"]);
	}

	public function getCriteriaFromInput(&$paginationParam){
		$criteria = new CDbCriteria();
		//
		$criteria->select = array();
		foreach($this->list->config["fields"] as $fieldName => $field){
			if(!$field["select"])
				continue;
			if(ArrayHelper::get($field,"foreignConfig"))
				continue;
			if($manualJoinForeignConfig=ArrayHelper::get($field,"manualJoinForeignConfig")){
				$prop = implode(".", $manualJoinForeignConfig);
				$criteria->select[] = $prop . " as " . $fieldName;
			} else {
				$criteria->select[] = "t.$fieldName";
			}
		}

		// input
		$limit; 
		$orderBy;
		$orderType;
		$search;
		$advancedSearch;
		$offset;

		if($this->list->config["actions"]["action"]["data"]["limit"]){
			$this->query["limit"] = $limit = Input::get("limit",array(
				"default" => $this->config["defaultQuery"]["limit"],
				"rules" => array(
					array('numerical','integerOnly'=>true,'min'=>1, "allowEmpty" => false)
				)
			),"html");
			$this->inputs["limit"] = Input::get("limit");
		} else {
			$this->list->query["limit"] = $limit = $this->config["defaultQuery"]["limit"];
		}

		if($this->list->config["actions"]["action"]["data"]["order"]){
			$orderFields = array();
			foreach ($this->list->config["fields"] as $fieldName => $field) {
				if($field["order"])
					$orderFields[] = $fieldName;
			}
			$this->query["orderBy"] = $orderBy = Input::get("order_by",array(
				"default" => $this->config["defaultQuery"]["orderBy"],
				"rules" => array(
					array("in","range" => $orderFields, "allowEmpty" => false)
				)
			),"html");
			$this->query["orderType"] = $orderType = Input::get("order_type",array(
				"default" => $this->config["defaultQuery"]["orderType"],
				"rules" => array(
					array("in","range" => array("asc","desc"), "allowEmpty" => false)
				)
			),"html");
			$this->inputs["order_by"] = Input::get("order_by");
			$this->inputs["order_type"] = Input::get("order_type");
		} else {
			$this->query["orderBy"] = $orderBy = $this->config["defaultQuery"]["orderBy"];
			$this->query["orderType"] = $orderType = $this->config["defaultQuery"]["orderType"];
		}
		
		if($this->list->config["actions"]["action"]["data"]["search"]){
			$this->query["search"] = $search = Input::get("search");
			$this->inputs["search"] = Input::get("search");
		} else {
			$this->query["search"] = $search = null;
		}

		if($this->list->config["actions"]["action"]["data"]["advancedSearch"]){
			$this->query["advancedSearch"] = $advancedSearch = Input::get("advanced-search",array( 
				"default" => $this->config["defaultQuery"]["advancedSearch"]
			));
			$this->inputs["advanced_search"] = Input::get("advanced-search");
		} else {
			$this->query["advancedSearch"] = $advancedSearch = $this->config["defaultQuery"]["advancedSearch"];
		}
		$isTypePage = ArrayHelper::has($this->list->config,"pagination");
		
		//
		if($isTypePage){
			// pagination mode
			$this->query["page"] = $activePage = Input::get("page",$this->config["defaultQuery"]["page"]);
			$numItemPerPage = $limit;
			$offset = $numItemPerPage * ($activePage - 1);
			$paginationParam["activePage"] = $activePage;
			$paginationParam["numItemPerPage"] = $numItemPerPage;
			$this->inputs["page"] = $activePage;
		} else {
			// normal mode
			if($this->list->config["actions"]["action"]["data"]["offset"]){
				$offset = $this->query["offset"] = Input::get("offset",false);
				$this->inputs["offset"] = $offset;
			} else {
				$offset = $this->query["offset"] = false;
			}
		}

		$criteria->limit = $limit;
		if($offset!==false)
			$criteria->offset = $offset;
		if($orderBy && $orderType){
			$field = $this->list->config["fields"][$orderBy];
			$tableAlias = "t";
			if($foreignConfig = ArrayHelper::get($field,"foreignConfig")){
				$tableAlias = $foreignConfig[0];
				$orderBy = $foreignConfig[1];
			}
			$criteria->order = "$tableAlias.$orderBy $orderType";
		}
		// dynamic
		if($dynamicInputs = $this->config["dynamicInputs"]){
			foreach($dynamicInputs as $input => $func){
				$this->query[$input] = $value = ArrayHelper::get($this->dynamicInputs,$input,Input::get($input));
				if($value!==null){
					$func($criteria,$value);
				}
				//$this->inputs[$input] = $value;
			}
		}
		// condition
		$conditions = array_replace_recursive($this->config["conditions"], $this->conditions);
		foreach($conditions as $k => $v){
			if(is_numeric($k)){
				$criteria->addCondition($v);
			} else {
				$criteria->compare($k,$v);
			}
		}
		// search
		if(($toBeSearchFields = $this->list->config["actions"]["action"]["data"]["search"]) && $search){
			$condition = "";
			foreach ($toBeSearchFields as $k => $col) {
                if(!isset($this->list->config["fields"][$col])){
                    if($k > 0)
                        $condition .= " OR ";
                    $condition .= " $col like :search ";
                    continue;
                }

				$field = $this->list->config["fields"][$col];
				if(($foreignConfig = ArrayHelper::get($field,"foreignConfig"))){
					$col = $foreignConfig[0] . "." . $foreignConfig[1];
				} else {
					$col = "t.$col";
				}
				if($k > 0)
					$condition .= " or ";
				$condition .= " $col like :search ";
			}
			$criteria->params[":search"] = "%$search%";
			$criteria->addCondition("($condition)");
		}
		// advanced search
		if($advancedSearch && is_array($advancedSearch)){
			$condition = "";
			foreach($advancedSearch as $col => $value){
				$field = @$this->list->config["fields"][$col];
				if(!$field || !($advancedSearchInputType = ArrayHelper::get($field,"advancedSearchInputType")))
					continue;
				$attr = $col;
				if(($foreignConfig = ArrayHelper::get($field,"foreignConfig"))){
					$col = implode(".", $foreignConfig);
				}  else if($manualJoinForeignConfig = ArrayHelper::get($field,"manualJoinForeignConfig")) {
					$col = implode(".", $manualJoinForeignConfig); 
				} else {
					$col = "t.$col";
				}
				Son::load("SListHelper")->advancedSearchCompare($criteria,$field["advancedSearchInputType"],$col,$value,ArrayHelper::get($field,"advancedSearchOptions"));
			}
		}
		// relations
		$criteria->with = $this->config["with"];
		//print_r($criteria); die();
		//
		$baseUrl = $this->list->getBaseUrl();
		$urlParams = $this->inputs;
		unset($urlParams["page"]);
		if(count($urlParams))
			$baseUrl = Util::urlAppendParams($baseUrl,$urlParams);
		$paginationParam["baseUrl"] = $baseUrl;
		//var_dump($baseUrl); die();
		//var_dump($this->getQuery("orderBy")); die();
		if($onCriteria=$this->config["onCriteria"]){
			$onCriteria($criteria);
		}
		return $criteria;
	}

	public function loadItemWithInput(){
		$idField = "id";
		$modelClass = $this->config["class"];
		$id = Input::post($idField,null,$this->list->inputErrorWarningType);
		$item = $modelClass::model()->findByPk($id);
		if($item==null){
			$this->list->returnError("Item doesn't exist. Please check again!");
			return;
		}
		return $item;
	}

	public function getIdArrayFromInput(){
		$ids = Input::post("ids",null,$this->list->inputErrorWarningType);
		if(!is_array($ids)){
			Output::showError("id array must be an array");
			return false;
		}
		foreach($ids as $id){
			if(!ctype_digit($id)){
				Output::showError("id array must be an array of intergers");
				return false;
			}
		}
		return $ids;
	}

	public function getQuery($queryAttr){
		return ArrayHelper::get($this->query,$queryAttr,$this->config["defaultQuery"][$queryAttr]);
	}

	public function getInsertForm(){
		if($this->insertForm===null){
			if(!($insertFields = $this->list->config["actions"]["action"]["insert"])){
				$this->insertForm = false;
				return $this->insertForm;
			}
			$self = $this;
			$config = array(
				"inputs" => array(
					"__item" => array(
						"model" => "model",
					)
				),
				"models" => array(
					"model" => $this->config["class"]
				),
				"method" => "post",
				"ajaxSubmit" => $this->list->config["actions"]["method"],
				"htmlAttributes" => array(
					"list-form-insert" => ""
				),
				"actionUrl" => Util::urlAppendParams($this->list->getBaseUrl(),array(
					"action" => "insert"
				)),
				"onHandleInput" => function($form) use($self,$insertFields){
					$result = $form->readInputToModel();
					if(!$result){
						$form->setError(true);
						return true;
					}
					$form->model->scenario = $self->config["insertScenario"];
					$result = $form->model->save(true,$insertFields);
					$form->setError(!$result);
					if($result){
						$form->saveModels(false);
					}
					return true;
				},
				"viewParams" => array(
					"submitHtmlAttributes" => array(
						"list-form-insert-submit" => ""
					)
				),
				"csrfAjaxRegenerate" => true
			);

			foreach($insertFields as $fieldName){
				$field = $this->list->config["fields"][$fieldName];

				$input = array();
				$input["label"] = $this->list->getLabel($fieldName);

				if($type=ArrayHelper::get($field,"inputType")){
					$input["type"] = $type;
				}

				if($rules=ArrayHelper::get($field,"inputRules")){
					$input["rules"] = $rules;
				}

				if(($defaultValue=ArrayHelper::get($field,"default",null))!==null){
					$input["default"] = $defaultValue;
				}

				$input["htmlAttributes"] = ArrayHelper::get($field,"inputHtmlAttributes",array());

				$displayInput=ArrayHelper::get($field,"displayInput");
				if($displayInput!==null){
					$input["displayInput"] = $displayInput;
				}

				if($inputConfig=ArrayHelper::get($field,"inputConfig")){
					if(is_callable($inputConfig)){
						$classObject = Son::load($this->config["class"]);
						$inputConfig = $inputConfig($classObject);
					}
					$input["config"] = $inputConfig;
				}
				$config["inputs"][$fieldName] = $input;
			}

			if($extendConfig = ArrayHelper::get($this->config["forms"],"insert_update")){
				$config = array_replace_recursive($config, $extendConfig);
			}

			if($extendConfig = ArrayHelper::get($this->config["forms"],"insert")){
				$config = array_replace_recursive($config, $extendConfig);
			}

			$this->insertForm = new SForm($config);
			$className = $this->config["class"];
			$this->insertForm->model = new $className();
		}

		return $this->insertForm;
	}

	public function getUpdateForm(){
		if($this->updateForm===null){
			if(!($updateFields = $this->list->config["actions"]["action"]["update"])){
				$this->updateForm = false;
				return $this->updateForm;
			}
			$self = $this;
			$config = array(
				"inputs" => array(
					"__item" => array(
						"model" => "model",
					)
				),
				"models" => array(
					"model" => $this->config["class"]
				),
				"method" => "post",
				"ajaxSubmit" => $this->list->config["actions"]["method"],
				"htmlAttributes" => array(
					"list-form-update" => ""
				),
				"actionUrl" => Util::urlAppendParams($this->list->getBaseUrl(),array(
					"action" => "update"
				)),
				"onHandleInput" => function($form) use($self,$updateFields){
					$result = $form->readInputToModel();
					if(!$result)
						return false;
					$className = $self->config["class"];
					$primaryField = $self->config["primaryField"];
					$id = null;
					if(!$form->model || !$form->model->$primaryField){
						$form->setError(true);
						$form->addError("global","Invalid request. Please check again!");
						return true;
					}
					$id = $form->model->$primaryField;
					$existModel = $className::model()->findByPk($id);
					if(!$existModel){
						$form->setError(true);
						$form->addError("global","Item doesn't exist. Please check again!");
						return true;
					}
					$updateFieldsClone = $updateFields;
					unset($updateFieldsClone[$primaryField]);
					$form->replaceModelWith("model",$existModel,$updateFieldsClone);
					//var_dump($form->model->fileList); die();
					//print_r($form->model); die();
					$form->model->scenario = $self->config["updateScenario"];
					$result = $form->model->save(true,$updateFieldsClone);
					$form->setError(!$result);
					if($result){
						$form->saveModels(false);
					}
					return true;
				},
				"viewParams" => array(
					"submitHtmlAttributes" => array(
						"list-form-update-submit" => "",
						"disabled" => "disabled"
					)
				),
				"csrfAjaxRegenerate" => true
			);

			foreach($updateFields as $fieldName){
				$field = $this->list->config["fields"][$fieldName];
				$input = array();
				$input["label"] = $this->list->getLabel($fieldName);

				if($type=ArrayHelper::get($field,"inputType")){
					$input["type"] = $type;
				}

				if($rules=ArrayHelper::get($field,"inputRules")){
					$input["rules"] = $rules;
				}

				if($defaultValue=ArrayHelper::get($field,"default")){
					$input["default"] = $defaultValue;
				}

				$input["htmlAttributes"] = ArrayHelper::get($field,"inputHtmlAttributes",array());

				$displayInput=ArrayHelper::get($field,"displayInput");
				if($displayInput!==null){
					$input["displayInput"] = $displayInput;
				}
				if($inputConfig=ArrayHelper::get($field,"inputConfig")){
					if(is_callable($inputConfig)){
						$className = Son::load($this->config["class"]);
						$inputConfig = $inputConfig($className::model());
					}
					$input["config"] = $inputConfig;
				}
				$input["htmlAttributes"]["item-attr"] = $fieldName;
				$config["inputs"][$fieldName] = $input;
			}
			$primaryField = $this->config["primaryField"];
			$config["inputs"][$primaryField] = array(
				"type" => "hidden",
				"htmlAttributes" => array(
					"item-attr" => $primaryField
				)
			);

			if($extendConfig = ArrayHelper::get($this->config["forms"],"insert_update")){
				$config = array_replace_recursive($config, $extendConfig);
			}

			if($extendConfig = ArrayHelper::get($this->config["forms"],"update")){
				$config = array_replace_recursive($config, $extendConfig);
			}

			$this->updateForm = new SForm($config);
		}
		return $this->updateForm;
	}

	public function getExtendedActionForm($actionName,$config){
		$config = array_replace_recursive(array(
			"inputs" => array(
				"id" => array(
					"type" => "hidden"
				)
			),
			"method" => "post",
			"ajaxSubmit" => true,
			"view" => "ext.Son.html.views.form.form-bootstrap-modal",
			"viewParams" => array(
				"modalId" => "modal_extended_action_$actionName" . "_" . Util::generateUniqueStringByRequest()
			),
			"actionUrl" => Util::urlAppendParams($this->list->getBaseUrl(),array(
				"action" => "extended_action",
				"action_name" => $actionName
			))
		), $config);
		$form = new SForm($config);
		return $form;
	}


	public function getExtendedActionTogetherForm($actionName,$config){
		$config = array_replace_recursive(array(
			"inputs" => array(
				"ids" => array(
					"type" => "hidden"
				)
			),
			"method" => "post",
			"ajaxSubmit" => true,
			"view" => "ext.Son.html.views.form.form-bootstrap-modal",
			"viewParams" => array(
				"modalId" => "modal_extended_action_together_$actionName"
			),
			"actionUrl" => Util::urlAppendParams($this->list->getBaseUrl(),array(
				"action" => "extended_action_together",
				"action_name" => $actionName
			))
		), $config);
		$form = new SForm($config);
		return $form;
	}
}