<?php 
	$sAsset = Son::load("SAsset");
	$sAsset->addExtension("angular");
	$displayClass = $display["model"];
	$displayCriteria = ArrayHelper::get($display,"criteria");
	$pivotAttr = $pivot["currentAttr"];
	$pivotDisplayAttr = $pivot["displayAttr"];

	$displayItems = $displayClass::model()->findAll($displayCriteria);
	$uniqueId = Util::generateUniqueStringByRequest();
	$id = "pivot-table-checkbox-" . $uniqueId;
?>
<div class="form-group" id="<?php echo $id ?>">
	<label class="control-label <?php echo $form->viewParam("labelCol","col-lg-3 col-md-4 col-sm-12 col-xs-12") ?>"><?php echo $label ?></label>
	<div class="<?php echo $form->viewParam("inputCol","col-lg-9 col-md-8 col-sm-12 col-xs-12") ?>">
		<div ng-app="<?php echo $id ?>" ng-controller="MainController">
			<span ng-repeat="displayItem in displayData" ng-controller="ItemController">
				<input type="hidden" ng-attr-name="<?php echo $name ?>[{{$index}}][<?php echo $pivot["idAttr"] ?>]" ng-attr-value="{{item.<?php echo $pivot["idAttr"] ?>}}" ng-if="hasId" />
				<input type="hidden" ng-attr-name="<?php echo $name ?>[{{$index}}][delete_flag]" value="1" ng-if="deleteFlag" />
				<input type="hidden" ng-attr-name="<?php echo $name ?>[{{$index}}][<?php echo $pivotDisplayAttr ?>]" ng-attr-value="{{displayItem.<?php echo $display["valueAttr"] ?>}}" ng-if="isChecked()" />
				<button type="button" class="btn btn-default btn-sm" ng-class="{'btn-success' : isChecked(), 'btn-default' : !isChecked()}" style="margin-bottom: 5px;" ng-click="toggle()">{{displayItem.<?php echo $display["displayAttr"] ?>}}</button>
			</span>
		</div>
	</div>
</div>
<?php $sAsset->startJsCode(); ?>
<script>
(function(){
	var app = $__$.angular.module('<?php echo $id ?>');
	app.controller("MainController",function($scope){
		$scope.data = {};
		$scope.displayData = <?php echo CJSON::encode($displayItems); ?>;
		$scope.init = function(){
			var $elem = $("#<?php echo $id ?>");
			var $input = $elem.parent("form:first").find('<?php echo $inputSelector ?>');
			$input.on("change",function(){
				var value = $input.val();
				var arr = JSON.parse(value);
				$scope.data = arr;
				$scope.$broadcast("data-changed");
				$scope.$apply();
			});
		}

		$scope.init();
	}).controller("ItemController",function($scope){
		$scope.deleteFlag = false;
		$scope.selected = false;
		$scope.hasId = false;
		$scope.item = null;

		$scope.$on("data-changed",function(){
			$scope.getItem();
		});

		$scope.getItem = function(){
			for(var i in $scope.data){
				var item = $scope.data[i];
				if(item['<?php echo $pivotDisplayAttr ?>']==$scope.displayItem['<?php echo $display["valueAttr"] ?>']){
					$scope.hasId = true;
					$scope.item = item;
					return;
				}
			}
			$scope.hasId = false;
			$scope.item = null;
		}
		$scope.toggle = function(){
			if($scope.hasId){
				$scope.deleteFlag = !$scope.deleteFlag;
			} else {
				$scope.selected = !$scope.selected;
			}
		}
		$scope.isChecked = function(){
			return ($scope.hasId && !$scope.deleteFlag) || (!$scope.hasId && $scope.selected);
		}
	});
})();
</script>
<?php $sAsset->endJsCode(); ?>