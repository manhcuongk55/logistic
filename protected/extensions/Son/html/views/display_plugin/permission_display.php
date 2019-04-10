<?php
$permissionObj = Son::load("Permission");
$permissionList = $permissionObj->getPermissionConfig($config["config"]);
$allowedPermissionLabels = array();
foreach($permissionList as $permissionName => $permissionItem){
	if($permissionObj->can($permissionName,$config["config"],$value)){
		$allowedPermissionLabels[] = $permissionItem[0];
	}
}
?>
<div data-value="<?php echo $value ?>">
	<?php foreach($allowedPermissionLabels as $label): ?>
		<div style="margin-bottom: 10px;">
			<span class="<?php echo $config["class"] ?>">
				<?php echo $label ?> <i class="fa fa-check"></i>
			</span>
		</div>
	<?php endforeach; ?>
</div>