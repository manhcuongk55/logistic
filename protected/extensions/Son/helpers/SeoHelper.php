<?php
class SeoHelper {
	public static function generateMetaKeywordsTag($keywords){
		Yii::app()->clientScript->registerMetaTag($keywords, "keywords");
	}

	public static function generateMetaDescriptionTag($description){
		Yii::app()->clientScript->registerMetaTag($description, "description");
	}
}