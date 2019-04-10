<?php
class SModel extends GxActiveRecord {

	/* FILE CONSTATNS */

	const SCENARIO_UPDATE_FILE_NAME = "SCENARIO_UPDATE_FILE_NAME";

	/* DYNAMIC PROPERTIES VARIABLES */

	protected $dynamicPropertyValues = array();

	/* MODEL VARIABLES */

	protected $deactiveInsteadOfDelete = false;
	protected $autoUpdateUpdatedTime = "updated_time";
	protected $autoUpdateCreatedTime = "created_time";
	public $listDropdownConfig = null;
	protected $defaultValues = array();
	protected $preventDeleteRelations = null;
	protected $preventDeleteAttr = "active";
	protected $deleteTogetherRelations = null;
	protected $saveHandleBlocked = false;

	public $delete_flag = false;
    public $file_base64_flag = array();

	public $modelUpdateTimeFlag = false;

	/* PASSWORD VARIABLES */
	protected $passwordField = null;
	public $passwordBackup = "";
	protected $passwordRaw = "";
	protected $passwordChangedFlag = false;
	protected $passwordHolder = "-----------------";

	/* FILE UPLOAD VARIABLES */

	/**
		* $fileConfig is an array like array(avatar => array( "type" => "image", "extension" => "png", "size" => array(1,100), "image" => array("fixSize" => array(), "minSize" => array(), "maxSize" => array(), "resize" => array()), "folder" => function(){}, "fileName" => function(){}, "thumbnail" => array("thumbnail") ), "thumbnail" => array( "image" => array( "resize" => array()) ))
	*/
	protected $fileUploadEnabled = false;
	protected $fileClassName = "ModelFile";
	protected $fileOldPath = array();
	public $fileUpdateFileNameAfterSave = false;
	public $fileConfig = array();
	public $fileList = array();

	/* CACHE VARIABLES */
	protected $cacheEnabled = null;
	protected $cacheDependencyType = "file";
	protected $cacheDuration = 86400;
	protected $cacheName = null;
	protected $cacheChangedColumn = "max(updated_date)";
	protected $cacheDependencyId = null;
	protected $cacheInvalidateIds = null;
    protected $cacheLoadDataDisabled = false;

	/* CONSTRUCTOR */

    public function __construct($scenario="insert"){
        parent::__construct($scenario);
        //if($scenario===null)
            //return;
        $this->fileProcessFileConfig();
        $this->initializeDefaultValues();
        $defaultProps = Util::param2("default.model");
        if(!$defaultProps)
            return;
        foreach($defaultProps as $prop => $value){
            if($this->$prop===null){
                $this->$prop = $value;
            }
        }
    }

	/* DYNAMIC PROPERTIES VARIABLE */

	public function dynamicPropertyIsSet($property){
		return ArrayHelper::get($this->dynamicPropertyValues,$property);
	}

	public function __isset($key){
		return parent::__isset($key) || Son::load("SModelHelper")->hasProperty(get_class($this),$key);
	}

	public function __get($key){
		if(Son::load("SModelHelper")->hasProperty(get_class($this),$key)){
			return ArrayHelper::get($this->dynamicPropertyValues,$key);
		}
		return parent::__get($key);
	}

	public function __set($key,$value){
		if(Son::load("SModelHelper")->hasProperty(get_class($this),$key)){
			$this->dynamicPropertyValues[$key] = $value;
			return;
		}
		parent::__set($key,$value);
	}

	/* MODEL FUNCTIONS */

	protected function beforeDeactive(){
		
	}

	protected function afterDeactive(){

	}

	protected function blockSaveHandle(){
		$this->saveHandleBlocked = true;
	}

	protected function releaseSaveHandle(){
		$this->saveHandleBlocked = false;
	}


	protected function getExtendedRules(){
		return null;
	}

	protected function initializeDefaultValues(){
		foreach ($this->defaultValues as $key => $value) {
			if($this->$key)
				continue;
			$this->$key = $value;
		}
	}

	protected function autoUpdateTimeFunction(){
		return time();
	}

	private function updateTime(){
		if($this->modelUpdateTimeFlag)
			return;
		if($autoUpdateCreatedTime = $this->autoUpdateCreatedTime){
			if($this->getIsNewRecord()){
				$this->$autoUpdateCreatedTime = $this->autoUpdateTimeFunction();
			}
		}
		if($autoUpdateUpdatedTime = $this->autoUpdateUpdatedTime){
			$this->$autoUpdateUpdatedTime = $this->autoUpdateTimeFunction();
		}
		$this->modelUpdateTimeFlag = true;
	}

	public function getFirstError(){
        foreach($this->getErrors() as $errorsOfAttr){
            return $errorsOfAttr[0];
        }
        return null;
    }

    public function getListDropdownConfig($key=null){
    	$dropdownConfig = $this->getListDropdownConfigBase();
    	if($key!==null){
    		return $dropdownConfig[$key];
    	}
    	return $dropdownConfig;	
    }

    protected function getListDropdownConfigBase(){
    	return $this->listDropdownConfig;
    }

    public function listSearch($searchAttr,$searchTerm){
    	$searchConfig = $this->getListDropdownConfig($searchAttr);
    	$criteria = ArrayHelper::get($searchConfig,"criteria",function(){
    		return new CDbCriteria();
    	});
    	foreach($searchConfig["searchAttrs"] as $attr){
    		$criteria->compare($attr,$searchTerm,true,"or",true);
    	}
    	$model = $searchConfig["model"];
		$items = $model::model()->findAll($criteria);
		$arr = array();
		$valueAttr = $searchConfig["valueAttr"];
		$displayAttr = $searchConfig["displayAttr"];
		foreach($items as $item){
			$arr[] = array($item->$valueAttr,$item->$displayAttr);
		}
		return $arr;
    }

	public function listGetLabel($attr,$value=null){
		if($value===null)
			$value = $this->$attr;
		$dropdownAttrConfig = $this->getListDropdownConfig($attr);
		return l("app",ArrayHelper::get($dropdownAttrConfig,$value));
	}

	/* SECURITY FUNCTIONS */

    protected $xssBackup = null;

    public function xss($str){
        return $this->securityCleanXssItem($str);
    }

    public function getRaw($property){
        if(!$this->xssBackup){
            return $this->$property;
        }
        return $this->xssBackup[$property];
    }

    public function securityStartPreventXss(){
        if($this->xssBackup!=null)
            return;
        $this->xssBackup = $this->getAttributes();
        $this->securityCleanXss();
    }

    public function securityStopPreventXss(){
        $this->setAttributes($this->xssBackup);
        $this->xssBackup = null;
    }

	protected function securityToBeXssCleanProperties(){
		return null;
	}

	protected function securityCleanXss(){
		$properties = $this->securityToBeXssCleanProperties();
		if(!$properties)
			return true;
		foreach ($properties as $property) {
            if(!$this->$property)
                continue;
			$result = $this->securityCleanXssItem($this->$property);
            if($result===false)
                return false;
            $this->$property = $result;
		}
        return true;
	}

    protected function securityCleanXssItem($str){
        $str = Util::cleanXss($str);
        return $str;
    }
	
    /* PASSWORD FUNCTIONS */

    public function verifyPassword($password)
    {
        return CPasswordHelper::verifyPassword($password,$this->passwordBackup);
    }
 
    protected function hashPassword($password)
    {
        return CPasswordHelper::hashPassword($password);
    }

    protected function passwordDoAfterFind(){
    	if(!$this->passwordField)
    		return;
    	$passwordField = $this->passwordField;
    	$this->passwordBackup = $this->$passwordField;
    	$this->$passwordField = null;
    	$this->passwordChangedFlag = false;
    }

    protected function passwordDoBeforeValidate(){
    	if(!$this->passwordField)
    		return;
    	$passwordField = $this->passwordField;
    	if($this->$passwordField){
    		// password has changed => set passwordChangedFlag
    		$this->passwordChangedFlag = true;
    	} else {
    		// password has not changed => set password to passwordHolder to have a valid value and set passwordChangedFlag
    		$this->passwordChangedFlag = false;
    		$this->$passwordField = $this->passwordHolder;
    	}
    }

    protected function passwordDoAfterValidate(){
    	if(!$this->passwordField)
    		return;
    	$passwordField = $this->passwordField;
    	if($this->hasErrors()){
    		// has error => revert backup
    		if(!$this->passwordChangedFlag){
    			// the current password id passwordHolder
    			$this->$passwordField = null;
    		}

    	}
    }

    protected function passwordDoBeforeSave(){
    	if(!$this->passwordField)
    		return;
    	$passwordField = $this->passwordField;
    	if($this->passwordChangedFlag){
    		// password has changed => hash
    		$this->passwordRaw = $this->$passwordField;
    		$this->$passwordField = $this->hashPassword($this->$passwordField);
    	} else {
    		// password has not changed => restore password
    		$this->$passwordField = $this->passwordBackup;
    	}
    }

    protected function passwordDoAfterSave(){
    	if(!$this->passwordField)
    		return;
    	$passwordField = $this->passwordField;
    	if($this->hasErrors()){
    		// error => revert
    		if($this->passwordChangedFlag){
    			$this->$passwordField = $this->passwordRaw;
    		} else {
    			$this->$passwordField = null;
    		}
    	}
    }

    /* FILE UPLOAD FUNCTIONS */

    public function fileAttrIsFile($attr){
    	return isset($this->fileConfig[$attr]);
    }

    protected function getFileConfig(){
    	return null;
    }

    protected function fileGetFolderToBeDeleted(){
    	return null;
    }

    protected function fileInitializeDefaultValues(){
    	if(!$this->fileUploadEnabled)
    		return;
    	foreach($this->fileConfig as $key => $config){
    		$val = ArrayHelper::get($config,"defaultUrl");
    		if(!$this->$key && $val){
    			$this->$key = $val;
    		}
    	}
    }

    public function fileGetFromUrl($arr){
        foreach($arr as $key => $url){
            if(!($config = ArrayHelper::get($this->fileConfig,$key)))
                continue;
            $className = ArrayHelper::get($config,"modelFileClass",$this->fileClassName);
            $file = new $className();
            $file->model = $this;
            $file->key = $key;
            $this->fileList[$key] = $file;
            if(!is_array($url)){
                $file->fromUrl = $url;
            } else {
                $file->fromUrl = $url[0];
                foreach($url[1] as $k => $v){
                    $file->$k = $v;
                }
            }
            $file->init();
            if($thumbnails = ArrayHelper::get($config,"thumbnails")){
                foreach($thumbnails as $thumbnail){
                    $thumbnailFile = new ModelFile();
                    $thumbnailFile->model = $this;
                    $thumbnailFile->key = $thumbnail;
                    $thumbnailFile->fromUrl = $url;
                    $this->fileList[$thumbnail] = $thumbnailFile;
                    $this->fileConfig[$thumbnail]["src"] = $file->key;
                    $thumbnailFile->init();
                }
            }
        }
        $this->fileUpdateFileListNameUpdateMoment();
    }

    public function fileGetFromBase64(){
        foreach($this->fileConfig as $key => $config){
            try {
                $data = $this->$key;
                if(!is_array($data)){
                    continue;
                }
                $base64Data = ArrayHelper::get($data,"base64");
                if(!$base64Data){
                    continue;
                }
                $arr = explode(",", $base64Data);
				if(!isset($arr[1]))
				{
					$rawdata = file_get_contents('php://input');
					echo $rawdata; die();
					//print_r($_POST); die();
                    var_dump($data); die();
				}
                list($typeInfo,$base64Data) = $arr;
                $data = base64_decode($base64Data);
				$folder = Yii::getPathOfAlias("webroot") . "/temp";
				$name = "file-" . Util::generateUUID();
                $tempPath = $folder . "/" . $name . ".jpg";
				if(!file_exists($folder)){
					$old = umask(0);
                    mkdir($folder, 0777, true);
                    umask($old);
				}
                file_put_contents($tempPath, $data);
            } catch(Exception $ex){
                continue;
            }

            $className = ArrayHelper::get($config,"modelFileClass",$this->fileClassName);
            $file = new $className();
            $file->model = $this;
            $file->key = $key;
            $file->fromBase64 = true;
            $file->name = $tempPath;
            $file->tmp_name = $tempPath;
            $file->size = filesize($tempPath);
            $file->type = "image/jpg";
            $file->init();
            $this->fileList[$key] = $file;
            if($thumbnails = ArrayHelper::get($config,"thumbnails")){
                foreach($thumbnails as $thumbnail){
                    $thumbnailFile = new ModelFile();
                    $thumbnailFile->model = $this;
                    $thumbnailFile->key = $thumbnail;
                    $thumbnailFile->name = $tempPath;
                    $thumbnailFile->tmp_name = $tempPath;
                    $thumbnailFile->size = filesize($tempPath);
                    $thumbnailFile->fromBase64 = true;
                    $thumbnailFile->type = "image/jpg";
                    $thumbnailFile->init();
                    $this->fileList[$thumbnail] = $thumbnailFile;
                    $this->fileConfig[$thumbnail]["src"] = $file->key;
                }
            }
        }
        //print_r(json_decode(CJSON::encode($this->fileList))); die();
    }

    /**
    	* @param $wrapper can be null or a string, with the input like User[avatar], the wrapper will be User
    */
    public function fileGetFromInput($wrapper=null){
    	if(!$this->fileUploadEnabled)
    		return;
        $this->fileGetFromBase64();
    	$fileClassName = $this->fileClassName;
    	//$this->fileProcessFileConfig();
    	if($wrapper==null){
    		foreach($_FILES as $key => $fileArr){
    			if(!$fileArr["name"])
    				continue;
    			if(!($config = ArrayHelper::get($this->fileConfig,$key)))
    				continue;
    			if($fileArr["error"]==UPLOAD_ERR_NO_FILE){
	        		continue;
	        	}
                $className = ArrayHelper::get($config,"modelFileClass",$fileClassName);
	            $file = new $className();
	            $file->key = $key;
	            $file->name = $fileArr["name"];
	            $file->type = $fileArr["type"];
	            $file->tmp_name = $fileArr["tmp_name"];
	            $file->size = filesize($file->tmp_name);
	            $file->error = $fileArr["error"];
	            $file->model = $this;
                $file->init();
	            $this->fileList[$key] = $file;

	            if($thumbnails = ArrayHelper::get($config,"thumbnails")){
	            	foreach($thumbnails as $thumbnail){
                        $className = $fileClassName;
	            		$thumbnailFile = new $className();
	            		$thumbnailFile->name = $file->name;
			            $thumbnailFile->tmp_name = $file->tmp_name;
			            $thumbnailFile->type = $file->type;
			            $thumbnailFile->key = $thumbnail;
			            $thumbnailFile->model = $this;
                        $thumbnailFile->init();
			            $this->fileList[$thumbnail] = $thumbnailFile;
			            $this->fileConfig[$thumbnail]["src"] = $file->key;
	            	}
	            }

	        }
    	} else {
    		//var_dump($_FILES); die();
    		if(is_string($wrapper)){
    			$wrapper = array($wrapper);
    		}
    		$modelFiles = ArrayHelper::get($_FILES,$wrapper[0]);
    		if(!$modelFiles){
                $this->fileUpdateFileListNameUpdateMoment();
    			return;
            }

    		$remainWrapper = $wrapper;
    		array_shift($remainWrapper);

    		//print_r($modelFiles); die();
    		//print_r($remainWrapper); die();

    		$arrName = ArrayHelper::get($modelFiles["name"],$remainWrapper);

            //print_r($wrapper); die();

            if(!is_array($arrName)){
                $this->fileUpdateFileListNameUpdateMoment();
                return;
            }

	        foreach($arrName as $key => $item){
	        	$attrWrapper = $remainWrapper;
	        	$attrWrapper[] = $key;
	        	$filename = ArrayHelper::get($modelFiles["name"],$attrWrapper);
	        	//print_r($modelFiles); die();
	        	if(!$filename)
	        		continue;
	        	if(!($config = ArrayHelper::get($this->fileConfig,$key)))
	        		continue;
	        	if(ArrayHelper::get($modelFiles["error"],$attrWrapper)==UPLOAD_ERR_NO_FILE){
	        		continue;
	        	}

                $className = ArrayHelper::get($config,"modelFileClass",$fileClassName);
	        	$file = new $className();
	            $file->key = $key;
	            $file->name = ArrayHelper::get($modelFiles["name"],$attrWrapper);
	            $file->type = ArrayHelper::get($modelFiles["type"],$attrWrapper);
	            $file->tmp_name = ArrayHelper::get($modelFiles["tmp_name"],$attrWrapper);
	            $file->size = filesize($file->tmp_name);
	            $file->error = ArrayHelper::get($modelFiles["error"],$attrWrapper);
	            $file->model = $this;
                $file->init();
	        	$this->fileList[$key] = $file;

	        	if($thumbnails = ArrayHelper::get($config,"thumbnails")){
	            	foreach($thumbnails as $thumbnail){
                        $className = $fileClassName;
	            		$thumbnailFile = new $className();
	            		$thumbnailFile->name = $file->name;
			            $thumbnailFile->tmp_name = $file->tmp_name;
			            $thumbnailFile->type = $file->type;
			            $thumbnailFile->key = $thumbnail;
			            $thumbnailFile->model = $this;
                        $thumbnailFile->init();
			            $this->fileList[$thumbnail] = $thumbnailFile;
			            $this->fileConfig[$thumbnail]["src"] = $file->key;
	            	}
	            }
	        }
    	}
        //var_dump($this->fileList); die();
    	$this->fileUpdateFileListNameUpdateMoment();
    }

    protected function fileUpdateFileListNameUpdateMoment(){
        foreach($this->fileList as $key => $file){
            if($config = ArrayHelper::get($this->fileConfig,$key)){
                $updateFileNameAfterSave = ArrayHelper::get($config,"updateFileNameAfterSave",false);
                if($updateFileNameAfterSave){
                    if(!is_array($this->fileUpdateFileNameAfterSave)){
                        $this->fileUpdateFileNameAfterSave = array();
                    }
                    $this->fileUpdateFileNameAfterSave[] = $key;
                }
            }
        }
    }

    protected function fileValidate(){
    	if(!$this->fileUploadEnabled)
    		return true;
    	foreach($this->fileList as $key => $file){
    		if($file->error){
    			$labels = $this->attributeLabels();
    			$this->addError($key, $labels[$key] . " upload error. Please choose another file");
    			return false;
    		}
    		$config = $this->fileConfig[$key];
    		$file->setConfig($config);
    		$this->fileOldPath[$key] = $this->$key;
    		$result = $file->validateWithConfig();
			//var_dump($this->$key); die();
    		if(!$result){
    			return false;
    		}
    	}
    	return true;
    }

    /**
    	* Replace the old filePath with the new one
    	* This function will be called after all the files were validated and $fileList, $fileOldPath is available
    */
    protected function fileSaveNew(){
    	if(!$this->fileUploadEnabled)
    		return;
    	foreach($this->fileList as $key => $file){
    		$config = $this->fileConfig[$key];
    		if(ArrayHelper::get($config,"src")) // this kind of file will be saved in it parent
    			continue;
    		$result = $file->saveWithConfig();
    		if(!$result)
    			return;
    	}
    }

    protected function fileRemoveOld(){
    	if(!$this->fileUploadEnabled)
    		return;
    	foreach($this->fileOldPath as $key => $oldPath){
    		if($oldPath && ($oldPath != $this->$key)){ // this attr is not empty and has been changed
    			$config = $this->fileConfig[$key];
    			if(ArrayHelper::get($config,"defaultUrl")==$oldPath)
    				continue;
    			$result = @unlink(substr($oldPath,1));
    			//$result = unlink($oldPath);
    		}
    	}
    }

    /**
    	* Remove all path that is contained in the record
    */
    protected function fileRemoveAll(){
    	if(!$this->fileUploadEnabled)
    		return;
    	//$this->fileProcessFileConfig();
    	foreach($this->fileConfig as $key => $file){
    		$filePath = $this->$key;
    		if(!$filePath)
    			continue;
    		$config = $this->fileConfig[$key];
			if(ArrayHelper::get($config,"defaultUrl")==$filePath)
				continue;
    		$result = @unlink(substr($filePath, 1));
    	}
    	$folderToBeDeleted = $this->fileGetFolderToBeDeleted();
    	if(!$folderToBeDeleted)
    		return;
        if(is_array($folderToBeDeleted)){
            foreach($folderToBeDeleted as $folder){
                Util::deleteFile($folder);
            }
        } else {
            Util::deleteFile($folderToBeDeleted);
        }
    }


    protected function fileProcessFileConfig(){
    	if(!$this->fileUploadEnabled)
    		return;
    	$fileConfigFromFunction = $this->getFileConfig();
    	if($fileConfigFromFunction){
    		$this->fileConfig = $fileConfigFromFunction;
    	}
    }

	/* CACHE FUNCTIONS */

	/**
		* Used when query from database
		* @param $dependencyId the id which the requested query will depend on, this can be an array of key value pair or an sql where statement like shop_id = 1
	*/

	public function cacheSetDependencyId($dependencyId="global"){
		$this->cacheDependencyId = $dependencyId;
	}

	/**
		* Used when update a record from database
		* @param $invalidateIds the id (a string or an array) of cache which will be invalidated
	*/
	public function cacheSetInvalidateId($invalidateIds){
		if(!isset($invalidateIds[0])) // is not a sequence array
			$invalidateIds = array($invalidateIds);
		$this->cacheInvalidateIds = $invalidateIds;
	}

	public function cacheGetUniqueOfObject(){
		return ArrayHelper::get($cacheConfig,"name",get_class($this));
	}

    public function noCache(){
        $this->cacheLoadDataDisabled = true;
        return $this;
    }

	public function cacheApplyDependency(){
		if(!$this->cacheEnabled || !CacheHelper::getCacheEnabled())
			return;
        if($this->cacheLoadDataDisabled)
            return;
		$dependency = null;
		switch($this->cacheDependencyType){
			case "file":
                $dependency = CacheHelper::getDependency($this->cacheGetUniqueOfObject(),$this->cacheDependencyId);
				break;
			case "db":
				$changedColumn = $this->changedColumn;
				$table = $this->tableName();
				$where = "";
				if($this->cacheDependencyId!=null){
					$where = " where " . $this->cacheDependencyId;
				}
				$dependency = new CDbCacheDependency("select $changedColumn from $table $where");
				break;
		}
		if($dependency==null)
			return;
		$this->cache($this->cacheDuration,$dependency);
	}

	public function cacheInvalidate(){
		if(!$this->cacheEnabled)
			return;
		switch($this->cacheDependencyType){
			case "file":
                $name = $this->cacheGetUniqueOfObject();
				$now = time();
                CacheHelper::setLastUpdatedTimeOfDependencyKey($name,$now,null);
                if($this->cacheInvalidateIds){
    				foreach($this->cacheInvalidateIds as $invalidateId){
                        CacheHelper::setLastUpdatedTimeOfDependencyKey($name,$now,$invalidateId);
    				}
                }
                $invalidateCaches = Util::param2("cache","modelInvalidateIds");
                $relatedDependencies = ArrayHelper::get($invalidateCaches,$name);
                if($relatedDependencies){
                    foreach($relatedDependencies as $relatedDependency){
                        if(is_callable($relatedDependency)){
                            $relatedDependency = $relatedDependency($this);
                        }
                        if($relatedDependency==null){
                            continue;
                        }
                        if(is_array($relatedDependency)){
                            foreach($relatedDependency as $dependency){
                                CacheHelper::setLastUpdatedTimeOfDependencyKey($dependency,$now,null,false);
                            }
                        } else {
                            CacheHelper::setLastUpdatedTimeOfDependencyKey($relatedDependency,$now,null,false);
                        }
                    }
                }
				break;
			case "db":
				// do nothing
				break;
		}
	}

	/* APPLY TO OVERRIDE FUNCTIONS */

	public function init(){
		parent::init();
	}

	public function findAll($condition='', $params=array()){
		$this->cacheApplyDependency();
		return parent::findAll($condition,$params);
	}

	public function findAllByAttributes($attributes,$condition='',$params=array())
	{
		$this->cacheApplyDependency();
		return parent::findAllByAttributes($attributes,$condition,$params);
	}

	public function findAllBySql($sql,$params=array())
	{
		$this->cacheApplyDependency();
		return parent::findAllBySql($sql,$params);
	}

	public function findByAttributes($attributes,$condition='',$params=array())
	{
		$this->cacheApplyDependency();
		return parent::findByAttributes($attributes,$condition,$params);
	}

	public function findBySql($sql,$params=array()){
		$this->cacheApplyDependency();
		return parent::findBySql($sql,$params);
	}

	public function findByPk($pk,$condition='', $params=array()){
		if($this->cacheDependencyId==null){
			$arr = array();
			$arr[$this->tableSchema->primaryKey] = $pk;
			$this->cacheSetDependencyId($arr);
		}
		$this->cacheApplyDependency();
		return parent::findByPk($pk,$condition,$params);
	}

    protected function query($criteria,$all=false){
        $result = parent::query($criteria,$all);
        $this->cacheLoadDataDisabled = false;
        return $result;
    }
	
	public function validate($attributes=null, $clearErrors=true){
		$result = parent::validate($attributes,$clearErrors);
		$this->passwordDoAfterValidate();
		return $result;
	}

	public function save($runValidation=true, $attributes=null){
		if($attributes!=null){
			$this->updateTime();
			if($autoUpdateCreatedTime = $this->autoUpdateCreatedTime){
				$attributes[] = $autoUpdateCreatedTime;
			}
			if($autoUpdateUpdatedTime = $this->autoUpdateUpdatedTime){
				$attributes[] = $autoUpdateUpdatedTime;
			}
		}
        $this->cacheSetInvalidateId(array(
            "id" => $this->{$this->tableSchema->primaryKey}
        ));
		$result = parent::save($runValidation,$attributes);
		$this->passwordDoAfterSave();
		if($result)
		{
			$this->cacheInvalidate();
		}
		return $result;
	}

	public function delete(){
		if($this->preventDeleteRelations){
			foreach($this->preventDeleteRelations as $relationName){
				if($this->$relationName){
					$this->deactiveInsteadOfDelete = $this->preventDeleteAttr;
					break;
				}
			}
		}
		if($activeAttr = $this->deactiveInsteadOfDelete){
			$this->beforeDeactive();
			$this->$activeAttr = 0;
			$result = $this->save(false,array(
				$activeAttr
			));
			if($result){
				$this->afterDeactive();
			}
		} else {
			$result = parent::delete();
			if($result)
			{
				$this->cacheInvalidate();
			}
		}
		
		return $result;
	}

	/**
		* Be careful, updateAll method will not affect files
	*/
	public function updateAll($attributes, $condition='', $params=array()){
		if($autoUpdateUpdatedTime = $this->autoUpdateUpdatedTime){
			$attributes[$autoUpdateUpdatedTime] = $this->autoUpdateTimeFunction();
		}
		$result = parent::updateAll($attributes,$condition,$params);
		if($result)
		{
			$this->cacheInvalidate();
		}
		return $result;
	}

	/**
		* Be careful, deleteAll method will not affect files
	*/
	public function deleteAll($condition='', $params=array()){
		$result;
		if($activeAttr = $this->deactiveInsteadOfDelete){
			$arr = array();
			$arr[$activeAttr] = 0;
			$result = $this->updateAll($arr,$condition,$params);
		} else {
			$result = parent::deleteAll($condition,$params);
		}
		if($result)
		{
			$this->cacheInvalidate();
		}
		return $result;
	}

	protected function beforeValidate(){
		if(!parent::beforeValidate())
			return false;
		if(!$this->fileValidate())
			return false;
        /*if(!$this->securityCleanXss()){
            return false;
        }*/
		$this->passwordDoBeforeValidate();
		$this->updateTime();
		return true;
	}

	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		if($this->saveHandleBlocked){
			return true;
		}
		if($this->scenario==self::SCENARIO_UPDATE_FILE_NAME){
			return true;
		}
		$this->passwordDoBeforeSave();
		if(!$this->fileUpdateFileNameAfterSave){
			// update file name before save
			$this->fileSaveNew();
			$this->fileList = array();
		}
		return true;	
	}

	protected function afterSave(){
		if($this->saveHandleBlocked){
			return;
		}
		if($this->scenario==self::SCENARIO_UPDATE_FILE_NAME){
			return;
		}
		if($this->fileUpdateFileNameAfterSave){
			$this->fileSaveNew();
			$previousScenario = $this->scenario;
			$this->scenario = self::SCENARIO_UPDATE_FILE_NAME;
			$this->setIsNewRecord(false);
			$this->blockSaveHandle();
			$this->save(false,$this->fileUpdateFileNameAfterSave);
			$this->fileList = array();
			$this->fileUpdateFileNameAfterSave = null;
			$this->releaseSaveHandle();
			$this->scenario = $previousScenario;
		}
		$this->fileRemoveOld();
		parent::afterSave();
	}

	protected function afterDelete(){
		$this->fileRemoveAll();
		$result = parent::afterDelete();
		if($this->deleteTogetherRelations){
			foreach($this->deleteTogetherRelations as $relation){
				if(!$this->$relation){
					continue;
				}
				if(is_array($this->$relation)){
					foreach($this->$relation as $item){
						$item->delete();
					}
				} else {
					$this->$relation->delete();
				}
			}
		}

		return $result;
	}

	protected function afterFind(){
		parent::afterFind();
		$this->passwordDoAfterFind();
		$this->fileInitializeDefaultValues();
		$this->initializeDefaultValues();
	}

	public function newRules(){
		$arr = parent::rules();
		if($extendedRules = $this->getExtendedRules()){
			foreach($extendedRules as $extendedRule){
				$arr[] = $extendedRule;
			}
		}
		return $arr;
	}

	public function url($attr,$isAbsoluteUrl=true){
		$val = $this->$attr;
		if(!$val){
			$val = ArrayHelper::get($this->fileConfig[$attr],"defaultUrl");
			//var_dump($this->fileConfig); die();
		}
		if($isAbsoluteUrl)
			return Util::controller()->createAbsoluteUrl($val);
		else
			return Util::controller()->createUrl($val);
	}

    public function isDefaultUrl($attr){
        if(!$this->$attr)
            return true;
        $val = ArrayHelper::get($this->fileConfig[$attr],"defaultUrl");
        return $this->$attr == $val;
	}
	
	public function getRealAttributes()
	{
		$attributes = $this->getAttributes();
		return $this->populateRecord($attributes,true);
	}

	public function populateRecord($attributes,$callAfterFind=true){
		if (is_array($attributes))
			foreach ($attributes as $name => &$value)
				if ($this->hasAttribute($name) and $value !== null)
					settype($value, $this->getMetaData()->columns[$name]->type);
		return parent::populateRecord($attributes, $callAfterFind);
	}

}