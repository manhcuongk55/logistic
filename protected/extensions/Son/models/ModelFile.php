<?php
class ModelFile
{
    public $model;
    public $key, $name, $type, $tmp_name, $size, $error;
    public $fromUrl = null;
    public $fromBase64 = null;
    public $needResize = true;
    /**
        * $config is an array like array( "type" => "image", "extension" => "png", "size" => array(1,100), "targetExtension" => "png", "image" => array("fixSize" => array(), "minSize" => array(), "maxSize" => array(), "resize" => array()), "folder" => function(){}, "fileName" => function(){} ), "avatar_thumbnail" => array( "src" => "avatar", "image" => array( "resize" => array()) )
    */
    public $config;
    public $targetExtension;
    public $savedFilePath;

    public static function getFileArrayFromInput($wrapper,$className="ModelFile"){
        $receivedFile = ArrayHelper::get($_FILES,$wrapper);
        if(!$receivedFile)
            return null;
        $returnItems = array();
        $numFile = count($receivedFile["name"]);
        for($i=0; $i<$numFile; $i++){
            $file = new $className();
            $file->key = $i;
            $file->name = $receivedFile["name"][$i];
            $file->type = $receivedFile["type"][$i];
            $file->tmp_name = $receivedFile["tmp_name"][$i];
            $file->size = filesize($file->tmp_name);
            $file->error = $receivedFile["error"][$i];
            $returnItems[] = $file;
        }
        return $returnItems;
    }

    // functions

    public function init(){

    }

    public function setConfig($config){
        $this->config = $config;
        $this->targetExtension = ArrayHelper::get($this->config,"targetExtension",$this->getExtension());
    }

    public function getLabel()
    {
        return $this->model->getAttributeLabel($this->key);
    }

    var $_extension = false;
    var $_labelName = false;

    public function getLabelName(){
        if($this->_labelName===false){
            $name = $this->name;
            if($this->fromUrl)
                $name = $this->fromUrl;
            $this->_labelName = strtolower(pathinfo($name, PATHINFO_FILENAME));
        }
        return $this->_labelName;
    }

    public function getExtension()
    {
        if($this->_extension===false){
            $name = $this->name;
            if($this->fromUrl)
                $name = $this->fromUrl;
            $fileParts = pathinfo($name);
            if(!isset($fileParts["extension"])){
                $this->_extension = $this->targetExtension;
            } else {
                $this->_extension = strtolower($fileParts["extension"]);
            }
        }
        return $this->_extension;
    }

    public function checkType($types,$errorMessage=false){
        if($types==null || !$this->type)
            return true;
        $arr = $types;
        if(!is_array($types)){
            switch($types){
                case "_image_":
                    $arr = array('image/jpeg','image/jpg','image/pjpeg', 'image/x-png','image/png');
                    break;
                case "_document_":
                    $arr = array("application/pdf","application/x-pdf");
                    break;
                default:
                    $arr = array($types);
                    break;
            }
        }
        $result = in_array($this->type, $arr);
        if(!$result){
            if($errorMessage===false){
                $errorMessage = $this->getLabel() . " has an invalid file type";
            }
            $this->model->addError($this->key,$errorMessage);
        }
        return $result;
    }

    public function checkExt($types,$errorMessage=false){
        if($types==null)
            return true;
        $arr = $types;
        if(!is_array($types)){
            switch($types){
                case "_image_":
                    $arr = array('jpg','jpeg','gif','png');
                    break;
                case "_document_":
                    $arr = array("pdf","doc","docx","html");
                    break;
                default:
                    $arr = array($types);
                    break;
            }
        }
        $result = in_array($this->getExtension(), $arr);
        if(!$result){
            if($errorMessage===false){
                $errorMessage = $this->getLabel() . " has an invalid extension";
            }
            $this->model->addError($this->key,$errorMessage);
        }
        return $result;
    }

    public function checkSize($min=-1,$max=-1,$errorMessage=false){
        $mb = $this->size / 1024 / 1024;
        $result = true;
        if($min!=-1){
            if($mb<$min){
                $result = false;
                if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s file size must be greater than " . $min);
                }
            }
        }
        if($max!=-1){
            if($mb>$max){
                $result = false;
                if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s file size must be less than " . $max);
                }
            }
        }

        if(!$result && $errorMessage!==false){
            $this->model->addError($this->key,$errorMessage);
        }

        return $result;
    }

    public function checkWidthHeightFixed($fixedWidth=-1, $fixedHeight=-1, $errorMessage=false){
        list($width, $height, $type, $attr) = getimagesize($this->tmp_name);
        $result = true;

        if($fixedWidth!=-1){
            if($width!=$fixedWidth){
                $result = false;
                if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s width must be equal to " . $fixedWidth);
                }
            }
        }

        if($fixedHeight!=-1){
            if($height!=$fixedHeight){
                $result = false;
                 if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s height must be equal to " . $fixedHeight);
                }
            }
        }

        if(!$result && $errorMessage!==false){
            $this->model->addError($this->key,$errorMessage);
        }

        return $result;
    }

    public function checkWidthHeightMin($minWidth=-1, $minHeight=-1, $errorMessage=false){
        list($width, $height, $type, $attr) = getimagesize($this->tmp_name);
        $result = true;

        if($minWidth!=-1){
            if($width<$minWidth){
                $result = false;
                if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s width must be greater than " . $minWidth);
                }
            }
        }

        if($minHeight!=-1){
            if($height<$minHeight){
                $result = false;
                 if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s height must be less greater " . $minHeight);
                }
            }
        }

        if(!$result && $errorMessage!==false){
            $this->model->addError($this->key,$errorMessage);
        }

        return $result;
    }

    public function checkWidthHeightMax($maxWidth=-1, $maxHeight=-1, $errorMessage=false){
        list($width, $height, $type, $attr) = getimagesize($this->tmp_name);
        $result = true;

        if($maxWidth!=-1){
            if($width>$maxWidth){
                $result = false;
                if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s width must be less than " . $maxWidth);
                }
            }
        }

        if($maxHeight!=-1){
            if($height>$maxHeight){
                $result = false;
                 if($errorMessage===false){
                    $this->model->addError($this->key,$this->getLabel() . "'s height must be less than " . $maxHeight);
                }
            }
        }

        if(!$result && $errorMessage!==false){
            $this->model->addError($this->key,$errorMessage);
        }

        return $result;
    }

    public function validateWithConfig(){
        if($this->fromUrl){
            $attr = $this->key;
            $this->model->$attr = "__" . $attr . "__temp__not_null__";
            return true;
        }
        if($src = ArrayHelper::get($this->config,"src"))
        {
            // will not validate this
            $attr = $this->key;
            $this->model->$attr = "__" . $attr . "__temp__not_null__";
            return true;
        }

        $type = ArrayHelper::get($this->config,"type");
        $extension = ArrayHelper::get($this->config,"extension");
        $size = ArrayHelper::get($this->config,"size");
        $image = ArrayHelper::get($this->config,"image");

        if($type){
            list($type,$message) = $this->readConfigAndErrorMessage($type);
            if(!$this->checkType($type,$message)){
                return false;
            }
        }

        if($extension){
            list($extension,$message) = $this->readConfigAndErrorMessage($extension);
            if(!$this->checkExt($extension,$message)){
                return false;
            }
        }

        if($size){
            list($size,$message) = $this->readConfigAndErrorMessage($size);
            if(!$this->checkSize($size[0],$size[1],$message)){
                return false;
            }
        }

        if($image){
            $fixSize = ArrayHelper::get($image,"fixSize");
            $minSize = ArrayHelper::get($image,"minSize");
            $maxSize = ArrayHelper::get($image,"maxSize");

            if($fixSize){
                list($fixSize,$message) = $this->readConfigAndErrorMessage($fixSize);
                if(!$this->checkWidthHeightFixed($fixSize[0],$fixSize[1],$message)){
                    return false;
                }
            }

            if($minSize){
                list($minSize,$message) = $this->readConfigAndErrorMessage($minSize);
                if(!$this->checkWidthHeightMin($minSize[0],$minSize[1],$message)){
                    return false;
                }
            }

            if($maxSize){
                list($maxSize,$message) = $this->readConfigAndErrorMessage($maxSize);
                if(!$this->checkWidthHeightMax($maxSize[0],$maxSize[1],$message)){
                    return false;
                }
            }
        }

        $attr = $this->key;
        $this->model->$attr = "__" . $attr . "__temp__not_null__";

        return true;
    }

    public function saveWithConfig($isThumbnail=false){
        // save thumbnail first
        $thumbnails = ArrayHelper::get($this->config,"thumbnails");
        if($thumbnails){
            foreach($thumbnails as $thumbnail){
                $thumbnailFile = $this->model->fileList[$thumbnail];
                $result = $thumbnailFile->saveWithConfig(true);
                if(!$result){
                    return false;
                }
            }
        }

        $this->generateFilePath();
        $key = $this->key;
        if(isset($this->config["image"]) && isset($this->config["image"]["resize"]) && $this->needResize){
            //var_dump($this->needResize); die();
            $resizeWidthHeight = $this->config["image"]["resize"];
            $width = $resizeWidthHeight[0];
            $height = $resizeWidthHeight[1];
            $crop = ArrayHelper::get($this->config,2,false);
            $result = true;

            $resource = $this->resize($width,$height,$crop);
            if($resource!==false){
                // still need to resize
                if(!$resource){
                    $this->model->$key = "";
                    return true;
                }
                $func = "image" . $this->targetExtension;
                if(!function_exists($this->targetExtension)){
                    $func = "imagepng";
                }
                $filePath = Yii::getPathOfAlias("webroot") . "/" . $this->savedFilePath;
                $folder = dirname($filePath);
                if (!file_exists($folder)) {
                    $old = umask(0);
                    mkdir($folder, 0777, true);
                    umask($old);
                }
                $result = $func($resource,$filePath);
                if($result){
                    $this->model->$key = "/" . $this->savedFilePath;
                }
                return $result;
            }
        }
        //$this->savedFilePath = "/" . $this->savedFilePath;
        $result = $this->save(Yii::getPathOfAlias("webroot") . "/" . $this->savedFilePath);
        if($result){
            $this->model->$key = "/" . $this->savedFilePath;
        } else {
            //var_dump("move uploaded file failed"); die();
        }
        return $result;
    }

    public function generateFilePath(){
        $folder = $this->config["folder"];
        if(is_callable($folder)){
            $folder = $folder($this->model);
        }
        if(ArrayHelper::get($this->config,"fileName")){
            $fileName = $this->config["fileName"]($this->model);
        } else {
            $fileName = $this->getLabelName();
        }
       
        $filePath = $folder . "/" . $fileName . "." . $this->targetExtension;
        $this->savedFilePath = $filePath;
    }

    public function save($filePath){
        $folder = dirname($filePath);
         if (!file_exists($folder)) {
            $old = umask(0);
            mkdir($folder, 0777, true);
            umask($old);
        }
        if(false && ArrayHelper::get($this->config,"type")=="_image_"){
             if($this->fromUrl){
                $result = $this->compressImage($this->fromUrl,$filePath);
                return $result;
            }
            if($this->fromBase64){
                $result = $this->compressImage($this->tmp_name,$filePath);
            } else {
                $result = $this->compressImage($this->tmp_name,$filePath);
            }
            if(!$result){
                echo $this->tmp_name . "\n" . $filePath; die();
            }
        } else {
             if($this->fromUrl){
                $fileContent = file_get_contents($this->fromUrl);
                $result = file_put_contents($filePath, $fileContent);
                //$result = copy($this->fromUrl,$filePath);
                //$result = true;
                return $result!==false;
            }
            if($this->fromBase64){
                $result = file_put_contents($filePath, file_get_contents($this->tmp_name));
            } else {
                $result = move_uploaded_file($this->tmp_name, $filePath);
            }
            if(!$result){
                echo $this->tmp_name . "\n" . $filePath; die();
            }
        }
        return $result;
    }

    public function resize($w, $h, $crop = false){
        if($this->fromUrl){
            //$tempPath = tempnam(sys_get_temp_dir(), 'prefix');
            $tempPath = Yii::getPathOfAlias("webroot") . "/temp/" . md5($this->fromUrl);
            $fileContent = file_get_contents($this->fromUrl);
            $result = file_put_contents($tempPath, $fileContent);
            if(!$result){
                return false;
            }
            $this->tmp_name = $tempPath;
        }

        list($width, $height) = getimagesize($this->tmp_name);

        if($width==$w && $height==$h){
            return false;
        }

        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            /*if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }*/
            $newwidth = $w;
            $newheight = $h;
        }
        $ext = $this->getExtension();
        /*$func = "imagecreatefrom" . $ext;
        //var_dump($func); die();
        if(!function_exists($func))
            $func = "imagecreatefromjpeg";
        $src = @$func($this->tmp_name);*/
        try {
            $src = imagecreatefromstring(file_get_contents($this->tmp_name));
            if(!$src)
                return null;
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            return $dst;
        }
        catch(Exception $ex){
            return false;
        }
    }

    private function compressImage($source, $destination, $quality=50) {
        
        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($source);
        }

        elseif ($info['mime'] == 'image/gif'){
            $image = imagecreatefromgif($source);
        }

        elseif ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($source);
        }
        imagejpeg($image, $destination, $quality);
        return $destination;
    }

    private function readConfigAndErrorMessage($item){
        if(is_array($item) && isset($item["message"]))
            return array($item[0],$item["message"]);
        return array($item,false);
    }

}