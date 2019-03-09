<?php
class SListHtml {
	var $configDefault = array(
		"viewPath" => array(
			"list" => null,
			"itemWrapped" => null, // for jquery mode
			"item" => null,
		),
		"itemSelectable" => array(
			"type" => "checkbox",
			"selectedClass" => "active",
			"multiple" => true
		),
		"onRender" => null,
		"limitSelectList" => array(10,20,40,60),
		"trackUrl" => false,
		"refreshOnInit" => true,        
		"insertSuccessMessage" => "Item has been inserted successfully!",
        "updateSuccessMessage" => "Item has been updated successfully!",
        "infiniteScroll" => false

	);
	public $config = null;
	public $list = null;
	var $_currentItem = null;
	var $_currentIndex = -1;

	public function __construct($config=null,$list=null){
		$this->configDefault["insertSuccessMessage"] = l("list",$this->configDefault["insertSuccessMessage"]);
        $this->configDefault["updateSuccessMessage"] = l("list",$this->configDefault["updateSuccessMessage"]);

		$this->config = $this->configDefault;
		if($config!=null){
			$this->config = array_replace_recursive($this->config, $config);
		}
		$this->list = $list;
		if($this->config["viewPath"]["itemWrapped"]==null){
			$this->config["viewPath"]["itemWrapped"] = $this->config["viewPath"]["item"];
		}
	}

	// render

	protected function renderConfig($renderAssetsImmediately=true){
		$sAsset = Son::load("SAsset");
		$sAsset->setJsDefaultPosition("top");
		$sAsset->addExtension("a-list");
		$sAsset->restoreJsDefaultPosition();
		$jsConfig = array(
			"fields" => array(),
			"baseUrl" => $this->list->getBaseUrl(),
			"mode" => $this->list->getMode(),
			"actions" =>  $this->list->config["actions"],
			"query" => $this->list->getModel()->query,
			"itemSelectable" => $this->config["itemSelectable"],
			"preloadData" => $this->list->getModel()->config["preloadData"],
			"trackUrl" => $this->config["trackUrl"],
			"defaultQuery" => $this->list->getModel()->config["defaultQuery"],
			"alias" => $this->list->alias,
			"primaryField" => $this->list->getModel()->config["primaryField"],
			"refreshOnInit" => $this->config["refreshOnInit"],
            "updateSuccessMessage" => $this->config["updateSuccessMessage"],
            "insertSuccessMessage" => $this->config["insertSuccessMessage"],
            "baseUrlWithoutDynamicInputs" => $this->list->getBaseUrlWithoutDynamicInputs(),
            "dynamicInputs" => $this->list->getModel()->dynamicInputs,
            "infiniteScroll" => $this->config["infiniteScroll"]

		);
		foreach($this->list->config["fields"] as $fieldName => $field){
			$jsConfig["fields"][$fieldName] = $field;
		}
		if(!$renderAssetsImmediately){
			$sAsset->startJsCode();
		}
		?>
			<script>
				SList.ListManager.getInstance().config["<?php echo $this->list->alias ?>"] = <?php echo json_encode($jsConfig); ?>;
			</script>
		<?php
		if(!$renderAssetsImmediately){
			$sAsset->endJsCode();
		}
	}

	public function render(){
		Son::load("SAsset")->addExtension("a-list");
		Util::controller()->renderPartial($this->config["viewPath"]["list"],array(
			"html" => $this,
			"list" => $this->list
		));
		$this->renderConfig();
	}

	public function renderPage(){
		$this->renderConfig(false);
		$this->onRender($this->list);
		Util::controller()->render($this->config["viewPath"]["list"],array(
			"html" => $this,
			"list" => $this->list
		));
	}

	protected function onRender(){
		$newList = array();
		foreach($this->config["limitSelectList"] as $key => $value){
			$newList["$value"] = $value;
		}
		$this->config["limitSelectList"] = $newList;

		$onRender = $this->config["onRender"];
		if($onRender)
			$onRender($this->list);
	}

	// items

	public function loop(){
		$itemClass = $this->list->config["class"]["item"];
		$this->_currentIndex = $this->_currentIndex + 1;
		$this->_currentItem = ArrayHelper::get($this->list->getModel()->data,$this->_currentIndex,false);
		if($this->_currentItem){
			$this->_currentItem = new $itemClass($this->_currentItem,$this->list);
			$this->_currentItem->itemIndex = $this->_currentIndex;
		}
		return $this->_currentItem;
	}

	public function resetLoop(){
		$this->_currentIndex = -1;
		$this->_currentItem = null;
		return true;
	}

	public function renderItems($return=false){
		$itemClass = $this->list->config["class"]["item"];
		$this->onRender($this->list);
		$html = "";
		foreach($this->list->getModel()->data as $i => $item){
			$listItem = new $itemClass($item,$this->list);
			$listItem->itemIndex = $i;
			$result = $this->renderItem($listItem,$return);
			if($return){
				$html .= $result;
			}
		}
		if($return){
			return $html;
		}
	}

	public function renderItem($item,$return=false,$wrap=true){
		if($wrap)
			$itemView = "itemWrapped";
		else
			$itemView = "item";
		$result = Util::controller()->renderPartial($this->config["viewPath"][$itemView],array(
			"item" => $item,
			"html" => $this,
			"list" => $this->list
		),$return);
		if($return)
			return $result;
	}

	public function renderCurrentItem($return=false,$wrap=true){
		return $this->renderItem($this->currentItem(),$return);
	}

	public function currentItem(){
		return $this->_currentItem;
	}

	public function getLabel($attr){
		return $this->list->getLabel($attr);
	}

	// components

	protected function getHtmlPositionAlias($position){
		return $this->list->alias . "_" . $position;
	}

	public function htmlAt($position,$html){
		Son::load("SHtml")->htmlAt($this->getHtmlPositionAlias($position),$html);
	}

	public function renderHtmlAt($position){
		Son::load("SHtml")->renderHtmlAt($this->getHtmlPositionAlias($position));
	}

	public function begin(){
		$listModel = $this->list->getModel();
		?>
			<?php $this->renderHtmlAt("before_list"); ?>
			<div list-id="<?php echo $this->list->alias ?>">
				<?php $this->renderHtmlAt("begin_list");  ?>
				<?php if($this->list->config["actions"]["action"]["data"]["order"]): ?>
					<input type="hidden" name="order_by" value="<?php echo $listModel->getQuery("orderBy") ?>" list-input="order_by" track-change />
					<input type="hidden" name="order_type" value="<?php echo $listModel->getQuery("orderType") ?>" list-input="order_type" track-change />
				<?php endif; ?>
				<?php if($this->list->config["actions"]["action"]["data"]["page"]): ?>
					<input type="hidden" name="page" value="<?php echo $listModel->getQuery("page") ?>" list-input="page" track-change />
				<?php endif; ?>
		<?php
	}

	public function end(){
		?>
			<?php $this->renderHtmlAt("end_list"); ?>
			</div>
			<?php $this->renderHtmlAt("after_list"); ?>
		<?php
	}

	public function hasAdvancedSearchFor($attr){
		return $this->list->config["fields"][$attr]["advancedSearchInputType"];
	}

	public function renderAdvancedSearchInputFor($attr,$htmlAttributes=null,$inputConfig=null){
		$advancedSearch = $this->list->getModel()->getQuery("advancedSearch");
		$value = ArrayHelper::get($advancedSearch,$attr,"");
		$field = $this->list->config["fields"][$attr];
		$advancedSearchInputType = $field["advancedSearchInputType"];
		if(!$htmlAttributes){
			$htmlAttributes = ArrayHelper::get($field,"advancedSearchHtmlAttributes",array());
		}
		if(!$inputConfig){
			$inputConfig = ArrayHelper::get($field,"advancedSearchConfig",array());
		}
		$htmlAttributes["list-input"] = "advanced-search";
		$htmlAttributes["list-input-advanced-search"] = $attr;
		$htmlAttributes["track-change"] = "";
		//var_dump($inputConfig);
		$result = Son::load("SListHelper")->advancedSearchDisplayInput($advancedSearchInputType,"advanced-search[$attr]",$value,$htmlAttributes,$inputConfig);
		if(!$result){
			$type = ArrayHelper::get($field,"inputType");
			$config = $inputConfig;
			if(!$config || empty($config)){
				$config =  ArrayHelper::get($field,"inputConfig");
				if(is_callable($config)){
					$modelClass = $this->list->model->config["class"];
					$config = $config(new $modelClass());
				}
			}
			Son::load("SListHelper")->advancedSearchRenderHtmlAttributesFromConfig($config,$htmlAttributes);
			Son::load("SPlugin")->renderInput("advanced-search[$attr]",$value,$type,$config,$htmlAttributes);
		}
	}

	public function renderCheckboxSelectAll($htmlAttributes=array()){
		$htmlAttributes["list-do-selected-all-items"] = "";
		echo CHtml::checkBox(null,false,$htmlAttributes);
	}

	public function renderSearchInput($htmlAttributes=array()){
		$defaultHtmlAttributes = array(
			"placeholder" => "Search"
		);
		$htmlAttributes = array_replace_recursive($defaultHtmlAttributes, $htmlAttributes);
		$htmlAttributes["list-input"] = "search";
		$htmlAttributes["track-change"] = "";
		echo CHtml::textField("search",$this->list->getModel()->getQuery("search"),$htmlAttributes);
	}

	public function hasOrderFor($attr){
		return $this->list->config["actions"]["action"]["data"]["order"] && $this->list->config["fields"][$attr]["order"];
	}

	public function renderOrderFor($attr,$label=null){
		if(!$label){
			$label = $this->getLabel($attr);
		}
		$canOrder = $this->hasOrderFor($attr);
		$orderBy = $this->list->getModel()->getQuery("orderBy");
		$orderType = $this->list->getModel()->getQuery("orderType");
		?>
			<div class="list-title div-inline" <?php if($canOrder): ?> list-do-order list-order-by="<?php echo $attr ?>" list-order-type="<?php echo ($orderBy==$attr) ? $orderType : "desc" ?>"<?php endif; ?>>
				<?php echo $label ?>
				<?php if($canOrder): ?>
					<i class="icon-sort-down fa fa-sort-desc" list-order-display list-order-by="<?php echo $attr ?>" list-order-type="desc" <?php if($orderBy!=$attr || $orderType!="desc"): ?>hidden<?php endif; ?>></i>
					<i class="icon-sort-up fa fa-sort-asc" list-order-display list-order-by="<?php echo $attr ?>" list-order-type="asc" <?php if($orderBy!=$attr || $orderType!="asc"): ?>hidden<?php endif; ?>></i>
					<i class="icon-sort fa fa-sort" list-order-display list-order-by="<?php echo $attr ?>" list-order-type="nothing" <?php if($orderBy==$attr): ?>hidden<?php endif; ?> ></i>
				<?php endif; ?>
			</div>
		<?php
	}

	public function renderLimitInput($htmlAttributes=array()){
		$htmlAttributes["list-input"] = "limit";
		$htmlAttributes["track-change"] = "";
		Son::load("SPlugin")->renderInput("limit",$this->list->getModel()->getQuery("limit"),"dropdown",array(
			"data" => $this->config["limitSelectList"]
		),$htmlAttributes);
	}

	public function renderTotalCount(){
		?>
			<span list-total-count><?php echo $this->list->getModel()->dataNumRowTotal ?></span>
		<?php
	}
	

	// forms

	public function hasInsertForm(){
		return $this->list->config["actions"]["action"]["insert"];
	}

	public function hasUpdateForm(){
		return $this->list->config["actions"]["action"]["update"];
	}

	public function renderInsertForm($view=null,$viewParams=null){
		if(!($form = $this->list->getModel()->getInsertForm()))
			return;
		$form->render($view,$viewParams);
	}

	public function renderUpdateForm($view=null,$viewParams=null){
		if(!($form = $this->list->getModel()->getUpdateForm()))
			return;
		$form->render($view,$viewParams);
	}

	public function renderItemForm($fields,$item,$includePrimary){
		$itemClass = $this->list->config["class"]["item"];
		$item = new $itemClass($item,$this->list);
		Util::controller()->renderPartial($this->config["viewPath"]["itemForm"],array(
			"item" => $item,
			"fields" => $fields,
			"includePrimary" => $includePrimary,
			"html" => $this
		));
	}

	public function renderItemLabel($fieldName){
		echo $this->getLabel($fieldName);
	}

	public function willUseCheckbox(){
		return $this->config["itemSelectable"] && $this->config["itemSelectable"]["type"]=="checkbox";
	}

	public function hasActionTogether(){
		return $this->hasActionDeleteTogether() || $this->hasExtendedActionsTogether();
	}

	public function hasActionDeleteTogether(){
		$actionDeleteConfig = $this->list->config["actions"]["actionTogether"]["deleteTogether"];
		return $actionDeleteConfig;
	}

	public function renderActionDeleteTogether(){
		$actionDeleteConfig = $this->list->config["actions"]["actionTogether"]["deleteTogether"];
		?>
			<li><a href="javascript:;" list-action-delete-together list-message="<?php echo ArrayHelper::get($actionDeleteConfig,1,"Are you sure to delete these items?") ?>"><i class="icon-trash"></i> <?php echo ArrayHelper::get($actionDeleteConfig,0,"Delete") ?></a></li>
		<?php
	}

	public function hasExtendedActionsTogether(){
		$config = $this->list->config["actions"]["extendedActionTogether"];
		return $config && !empty($config);
	}

	public function renderExtendedActionsTogether(){
		$config = $this->list->config["actions"]["extendedActionTogether"];
		?>
			<?php foreach($config as $actionName => $item): ?>
				<?php 
					$form = null;
					if(!is_callable($item[0])){
						$form = $this->list->getModel()->getExtendedActionTogetherForm($actionName,$item[0]);
						$this->htmlAt("end_list",$form->render(null,null,true));
					}
				?>
				<li>
					<a href="javascript:;" list-extended-action-together="<?php echo $actionName ?>" <?php if(isset($item[2])): ?> list-message="<?php echo $item[2] ?>"<?php endif; ?> <?php if($form): ?> list-form-modal="#<?php echo $form->viewParam("modalId") ?>" <?php endif; ?>><?php echo $item[1] ?></a>
				</li>
			<?php endforeach; ?>
		<?php
	}
	
	public function hasActionExport(){
		$config = ArrayHelper::get($this->list->config["actions"]["action"]["data"],"export");
		return $config;
	}
	
	public function renderActionExport(){
		$config = $this->list->config["actions"]["action"]["data"]["export"];
		foreach($config["types"] as $type){
			if($type=="csv"){
				?>
					<li><a href="javascript:;" list-export="csv"><i class="fa fa-file-text-o"></i> CSV</a></li>
				<?php
			} elseif($type=="excel"){
				?>
					<li><a href="javascript:;" list-export="excel"><i class="fa fa-file-excel-o"></i> Excel</a></li>
				<?php
			}
		}
	}

}