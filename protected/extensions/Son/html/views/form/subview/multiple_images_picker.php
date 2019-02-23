<?php 
	$sAsset = Son::load("SAsset");
	$sAsset->addExtension("angular");
	$uniqueId = Util::generateUniqueStringByRequest();
	$id = "multiple-images-picker-" . $uniqueId;
?>
<div class="form-group" id="<?php echo $id ?>">
	<label class="control-label <?php echo $form->viewParam("labelCol","col-lg-3 col-md-4 col-sm-12 col-xs-12") ?>"><?php echo $label ?></label>
	<div class="<?php echo $form->viewParam("inputCol","col-lg-9 col-md-8 col-sm-12 col-xs-12") ?>">
		<div ng-app="<?php echo $id ?>" ng-controller="MainController" style="margin-left: 10px; margin-right: 10px;">
			<div class="row">
				<div class="col-xs-12">
					<input type="file" class="hidden" id="<?php echo $id ?>-file" multiple="multiple" ng-on-files-picked="files-picked" />
					<button type="button" class="btn btn-sm btn-danger" onclick="$('#<?php echo $id ?>-file').click()"><i class="fa fa-plus"></i> Thêm ảnh</button>
				</div>
				<div class="col-lg-4 col-xs-6" ng-repeat="item in data" style="position: relative;" ng-controller="ItemController" ng-class="{'hidden' : deleteFlag}">
					<input type="hidden" ng-attr-name="<?php echo $name ?>[{{$index}}][<?php echo $idAttr ?>]" ng-attr-value="{{item.<?php echo $idAttr ?>}}" ng-if="item.<?php echo $idAttr ?>" />
					<input type="hidden" ng-attr-name="<?php echo $name ?>[{{$index}}][delete_flag]" value="1" ng-if="deleteFlag" />
					<input type="hidden" ng-attr-name="<?php echo $name ?>[{{$index}}][<?php echo $imageAttr ?>][base64]" ng-attr-value="{{item.<?php echo $imageAttr ?>}}" ng-if="item.fileBase64" ng-disabled="deleteFlag" />
					<img ng-attr-src="{{item.<?php echo $imageAttr ?>}}" style="width: 100%; margin: 10px;" />
					<button type="button" style="position: absolute; top: 13px; right: 13px; border: none; background: transparent; padding: 0px;" ng-click="removeImage()">
						<i class="fa fa-close"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $sAsset->startJsCode(); ?>
<script>
(function(){
	var app = $__$.angular.module('<?php echo $id ?>');
	app.controller("MainController",function($scope){
		$scope.data = [];
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

		$scope.$on("files-picked",function(event,obj){
			var images = obj.data;
			for(var i in images){
				var image = images[i];
				var item = {};
				item.image = image;
				item.fileBase64 = 1;
				if(!$scope.data){
					$scope.data = [];
				}
				$scope.data.push(item);
			}
		});

		$scope.init();
	}).controller("ItemController",function($scope){
		$scope.$on("data-changed",function(){
			
		});
		$scope.removeImage = function(){
			$scope.deleteFlag = true;
		}
	});
})();
</script>
<?php $sAsset->endJsCode(); ?>