<style>
	.config-page-form h5, .config-page-form label {
		font-weight: bold !important;
	}

	.array-field-container {
		margin-bottom: 10px;
		border: 1px solid #eee;
		border-left: 2px solid rgb(183,40,199);
	}

	.array-field-container > * {
		margin-left: 30px;
		margin-right: 30px;
	}

	.object-field-container {
		margin-bottom: 10px;
		border: 1px solid #eee;
		border-left: 2px solid rgb(40,148,199);
	}

	.object-field-container > * {
		margin-left: 30px;
		margin-right: 30px;
	}

	[array-items] > *:before {
		content: '';
	}

	[array-field-item] > .array-remove-container {
		visibility: hidden;
		padding-left: 5px;
	}

	[array-field-item]:hover > .array-remove-container {
		visibility: visible;
	}	
</style>
<div class="z-depth-1 pd-a10">
    <div id="config-page">
        <?php if($title): ?>
            <h3><?php echo $title; ?></h3>
            <hr/>
        <?php endif; ?>
        <form id="config-page-form" class="config-page-form" action="<?php echo $actionUrl ?>" method="POST">
            <?php foreach($fields as $fieldName => $field): $value = ArrayHelper::get($values,$fieldName); ?>
                <?php $this->renderPartial($fieldViewPath,array(
                    "fieldName" => $fieldName,
                    "item" => $field,
                    "value" => $value,
                    "configPage" => $configPage,
                    "fieldViewPath" => $fieldViewPath,
                    "level" => 0,
                    "isValid" => "1"
                )) ?>
            <?php endforeach; ?>
            <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-cog"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<script>
	$(function(){
		var $configPage = $("#config-page");
		$configPage.find("[array-field-collapse]").click(function(){
			var $itemWrapper = $(this).parents(".array-field-container:first").find(".field-items");
			$itemWrapper.toggle();
			if($itemWrapper.is(":visible")){
				$(this).children("i").removeClass().addClass("fa fa-minus");
			} else {
				$(this).children("i").removeClass().addClass("fa fa-plus");
			}
		});

		$configPage.find("[object-field-collapse]").click(function(){
			var $itemWrapper = $(this).parents(".object-field-container:first").find(".field-items");
			$itemWrapper.toggle();
			if($itemWrapper.is(":visible")){
				$(this).children("i").removeClass().addClass("fa fa-minus");
			} else {
				$(this).children("i").removeClass().addClass("fa fa-plus");
			}
		});

		$configPage.find("[array-remove]").click(function(){
			$(this).parents("[array-field-item]:first").remove();
		});

		$configPage.find("[array-add]").click(function(){
			var $container = $(this).parents(".array-field-container:first");
			var $template = $container.find("[array-item-template]:first").children(":first");
			var $newItem = $template.clone(true);
			$container.find(".array-field-items:first > [array-items]").append($newItem);
			$newItem.find("[data-name]:first").attr("is-valid","1");
			$newItem.find("input,select").filter(":visible").first().focus();
		});

		$(function(){
			var $configForm = $("#config-page-form");
			$configForm.on("submit",function(e){

				function completeInputNameWithParentName($div,parentName, index){
					if($div.is("[is-simulate]"))
						return;
					var itemName = $div.attr("data-name");
					if(!itemName)
						itemName = index;
					var level = parseInt($div.attr("data-level"));
					var name = parentName + "[" + itemName + "]";
					$div.attr("name",name);
					if($div.attr("data-array")!=undefined){
						var childLevel = level + 1;
						$div.find("[data-name][data-level='" + childLevel + "'][is-valid='1']").each(function(k,v){
							completeInputNameWithParentName($(this),name,k);
						});
					} else {

					}
				}

				$(this).find("[data-name][data-level='0'][is-valid='1']").each(function(k,v){
					completeInputNameWithParentName($(this),"config",k);
				});

				$__$.__ajax(this,e);
			});
			$configForm.on("form-success",function(){
				location.reload();
			});
		});
	});
</script>