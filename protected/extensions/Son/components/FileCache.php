<?php
/**
 * CApcCache class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CApcCache provides APC caching in terms of an application component.
 *
 * The caching is based on {@link http://www.php.net/apc APC}.
 * To use this application component, the APC PHP extension must be loaded.
 *
 * See {@link CCache} manual for common cache operations that are supported by CApcCache.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.caching
 * @since 1.0
 */
class FileCache extends CFileCache
{
	public function getAndReturnDependency($id)
	{
		$value = $this->getValue($this->generateUniqueKey($id));
		if($value===false || $this->serializer===false)
			return array($value,null);
		if($this->serializer===null)
			$value=unserialize($value);
		else
			$value=call_user_func($this->serializer[1], $value);
		if(is_array($value) && (!$value[1] instanceof ICacheDependency || !$value[1]->getHasChanged()))
		{
			Yii::trace('Serving "'.$id.'" from cache','system.caching.'.get_class($this));
			
			return array($value[0],$value[1]);
		}
		else
			return array(false,null);
	}
}