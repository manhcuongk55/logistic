<?php
class ModelSeoHelper {
	public static $metaGenerated = false;

	public static function run($model){
		self::$metaGenerated = true;
		SeoHelper::generateMetaKeywordsTag($model->meta_keyword);
		SeoHelper::generateMetaDescriptionTag($model->meta_description);
	}
}