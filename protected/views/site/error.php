<?php 
	if($code==404){
		$this->layout = "simple";
		$this->renderPartial("application.views.site.error-404",array(
			"code" => $code,
			"message" => $message
		));
		return;
	}
?>

<meta charset="utf-8" />
<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';

?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>