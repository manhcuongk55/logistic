<?php
class Util {
    static $uniqueIndex;
    static $params;
    
    public static function urlAppendParams($url,$paramString){
        if(is_array($paramString)){
            $paramString = http_build_query($paramString);
        }
		$paramStringFirstCharIsAnd;
        if(!$paramString){
            $paramString = "";
			$paramStringFirstCharIsAnd = false;
        } else {
			$paramStringFirstCharIsAnd = $paramString[0]=="&";
		}
		
        $urlAlreadyHasParams = parse_url($url, PHP_URL_QUERY);
		$urlLastChar = $url ? $url[strlen($url)-1] : "";
		$urlLastCharIsQuestionMark = $urlLastChar=="?";
		$urlLastCharIsAnd = $urlLastChar=="&";
		
		if($urlAlreadyHasParams){
			if($urlLastCharIsAnd || $paramStringFirstCharIsAnd)
				$url = $url . $paramString;
			else
				$url = "$url&$paramString";
		} else {
			if($urlLastCharIsQuestionMark || $urlLastCharIsAnd)
				$url = $url . $paramString;
			else
				$url = "$url?$paramString";
		}
		
        return $url;
    }

    public static function controller(){
        return Yii::app()->controller;
    }

    public static function l($url,$echo=false){
        $url = Yii::app()->controller->createUrl($url);
        if($echo){
            echo $url;
        }
        return $url;
    }

    public function md($url,$echo=false){
        return self::l("/" . $this->module->getName() . "/" . $url,$echo);
    }

    public static function deleteFile($path)
    {
        if (is_dir($path) === true)
        {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file)
            {
                self::deleteFile(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        }
        else if (is_file($path) === true)
        {
            return unlink($path);
        }

        return false;
    }

	public static function generateRandomString($length = 10,$customAppend="") {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString . $customAppend;
    }

    public static function generateUUID() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public static function generateUniqueStringByRequest(){
        return Util::$uniqueIndex++;
    }

    public static function sendMail($to,$subject,$view,$params,$fromEmail=null,$fromName=null)
    {
        /*$mail = new YiiMailer($view, $params);

        if($mailTitle = Util::controller()->mailTitle){
            $subject = $mailTitle;
        }*/

        $content = Util::controller()->renderPartial("application.views.mail." . $view,$params,true);

        $mail = new YiiMailer("empty",array(
            "content" => $content
        ));

        if($mailTitle = Util::controller()->mailTitle){
            $subject = $mailTitle;
        }

        $setting = Util::param2("accounts","mail");

        if($fromEmail==null){
            $fromEmail = $setting["adminEmail"];
        }

        if($fromName==null){
            $fromName = $setting["admin"];
        }

        $mail->setFrom($fromEmail,$fromName);
        $mail->setSubject($subject);

        if(is_array($to)){
            foreach ($to as $toItem) {
                $mail->AddAddress($toItem);
            }
        } else {
            $mail->setTo($to);
        }

        $mail->IsSMTP();
        $mail->Host       = $setting["host"];
        $mail->SMTPDebug  = 0;                     
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = $setting["smtp"]["secure"];
        $mail->Host       = $setting["smtp"]["host"];
        $mail->Port       = $setting["smtp"]["port"];
        $mail->Username   = $setting["smtp"]["username"];
        $mail->Password   = $setting["smtp"]["password"];
        $mail->SMTPDebug  = 0;

        //send
        if ($mail->send()) {
            return true;
        } else {
            echo "Error while sending email: ".$mail->getError();
            return false;
        }
    }

    public static function sendMailWithContent($to,$subject,$content){
        self::sendMail($to,$subject,"empty",array(
            "content" => $content
        ));
    }

    public static function param($name,$default=false)
    {
        if(isset(Yii::app()->params[$name]))
        {
            return Yii::app()->params[$name];
        }
        return $default;
    }

    public static function param2($name,$subName=null,$default="__not_set__")
    {
        $arr = self::param($name);
        if($arr){
            if(!$subName)
                return $arr;
            if($default=="__not_set__")
                return $arr[$subName];
            return ArrayHelper::get($arr,$subName,$default);
        } else {
            if(!isset(self::$params[$name])){
                $path = "application.config.params.$name";
                $file = Yii::getPathOfAlias($path).".php";
                try {
                    self::$params[$name] = include($file);
                } catch(Exception $ex){
                    self::$params[$name] = null;
                }
            }
            if(!$subName){
                return self::$params[$name];
            }
            if(self::$params[$name]){
                if($default=="__not_set__"){
                    return ArrayHelper::get(self::$params[$name],$subName);
                }
                return ArrayHelper::get(self::$params[$name],$subName,$default);
            }
            else {
                return $default;   
            }
        }
    }
    
    public static function session($name,$default=false)
    {   
        if(isset(Yii::app()->session[$name]))
        {
            return Yii::app()->session[$name];
        }
        return $default;
    }

    public static function setSession($name,$value){
        Yii::app()->session[$name] = $value;
    }

    public static function deleteSession($name){
        if(isset(Yii::app()->session[$name]))
            unset(Yii::app()->session[$name]);
    }

    public static function date($timestamp)
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);
        return $dateTime;
    }

    public static function log($content,$name="default"){
        $folder = "./logs/";
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }
        $filename = $folder . $name . ".txt";
        file_put_contents($filename, $content."\n",FILE_APPEND);
    }

    public static function slugify($text) {
        $text = self::toAscii($text);
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public static function toAscii($str){
        // a ă â e ê i o ô ơ u ư y đ
        // a à á ạ ả ã
        $map = array(
            "a" => "a", "à" => "a", "á" => "a", "ạ" => "a", "ả" => "a", "ã" => "a",
            "ă" => "a", "ằ" => "a", "ắ" => "a", "ặ" => "a", "ẳ" => "a", "ẵ" => "a",
            "â" => "a", "ầ" => "a", "ấ" => "a", "ậ" => "a", "ẩ" => "a", "ẫ" => "a",
            "e" => "e", "è" => "e", "é" => "e", "ẹ" => "e", "ẻ" => "e", "ẽ" => "e",
            "ê" => "e", "ề" => "e", "ế" => "e", "ệ" => "e", "ể" => "e", "ễ" => "e",
            "i" => "i", "ì" => "i", "í" => "i", "ị" => "i", "ỉ" => "i", "ĩ" => "i",
            "o" => "o", "ò" => "o", "ó" => "o", "ọ" => "o", "ỏ" => "o", "õ" => "o",
            "ô" => "o", "ồ" => "o", "ố" => "o", "ộ" => "o", "ổ" => "o", "ỗ" => "o",
            "ơ" => "o", "ờ" => "o", "ớ" => "o", "ợ" => "o", "ở" => "o", "ỡ" => "o",
            "u" => "u", "ù" => "u", "ú" => "u", "ụ" => "u", "ủ" => "u", "ũ" => "u",
            "ư" => "u", "ừ" => "u", "ứ" => "u", "ự" => "u", "ử" => "u", "ữ" => "u",
            "y" => "y", "ỳ" => "y", "ý" => "y", "ỵ" => "y", "ỷ" => "y", "ỹ" => "y",
            "đ" => "d",
            "A" => "A", "À" => "A", "Á" => "A", "Ạ" => "A", "Ả" => "A", "Ã" => "A",
            "Ă" => "A", "Ằ" => "A", "Ắ" => "A", "Ặ" => "A", "Ẳ" => "A", "Ẵ" => "A", 
            "Â" => "A", "Ầ" => "A", "Ấ" => "A", "Ậ" => "A", "Ẩ" => "A", "Ẫ" => "A", 
            "E" => "E", "È" => "E", "É" => "E", "Ẹ" => "E", "Ẻ" => "E", "Ẽ" => "E", 
            "Ê" => "E", "Ề" => "E", "Ế" => "E", "Ệ" => "E", "Ể" => "E", "Ễ" => "E", 
            "I" => "I", "Ì" => "I", "Í" => "I", "Ị" => "I", "Ỉ" => "I", "Ĩ" => "I", 
            "O" => "O", "Ò" => "O", "Ó" => "O", "Ọ" => "O", "Ỏ" => "O", "Õ" => "O", 
            "Ô" => "O", "Ồ" => "O", "Ố" => "O", "Ộ" => "O", "Ổ" => "O", "Ỗ" => "O", 
            "Ơ" => "O", "Ờ" => "O", "Ớ" => "O", "Ợ" => "O", "Ở" => "O", "Ỡ" => "O", 
            "U" => "U", "Ù" => "U", "Ú" => "U", "Ụ" => "U", "Ủ" => "U", "Ũ" => "U",
            "Ư" => "U", "Ừ" => "U", "Ứ" => "U", "Ự" => "U", "Ử" => "U", "Ữ" => "U",
            "Y" => "Y", "Ỳ" => "Y", "Ý" => "Y", "Ỵ" => "Y", "Ỷ" => "Y", "Ỹ" => "Y",
            "Đ" => "D"
        );
        return strtr($str, $map);
    }

    public static function cleanXss($str){
        return htmlspecialchars($str,ENT_NOQUOTES,false);
    }

    public static function getFullPath($filePath){
        return Yii::getPathOfAlias("webroot") . "/" . $filePath;
    }

    public static function getTempFile($fileName){
        $file = self::getFullPath("temp/" . $fileName);
        if(!is_dir($file)){
            file_put_contents($file, "");
            chmod($file,0777);
        }
        return $file;
    }
}

Util::$uniqueIndex = 0;
Util::$params = array();