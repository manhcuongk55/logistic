<?php
class ScribdHelper {
	private static $instance = null;
	public static function getInstance(){
		if(!self::$instance){
			Yii::import("ext.scribd.*");
			$config = Util::param2("accounts","scribd");
			self::$instance = new Scribd($config["api_key"],$config["secret_key"]);
		}
		return self::$instance;
	}

	public static function getPdfAndThumbnail($filePath,$ext,$width,$height){
		$scribd = self::getInstance();
		$data = $scribd->upload($filePath,$ext);
		$docId = $data["doc_id"];

		self::waitUntilDone($docId);

		$data = $scribd->postRequest("docs.getDownloadUrl",array(
	    	"doc_id" => $docId,
	    	"doc_type" => "pdf"
	    ));
	    $url =  $data["download_link"];

	    $pdfFilePath = Yii::getPathOfAlias("webroot") . "/temp/" . md5($url) . ".pdf";
        file_put_contents($pdfFilePath, file_get_contents($url));

        $data = $scribd->postRequest("thumbnail.get",array(
	    	"doc_id" => $docId,
	    	"width" => $width,
	    	"height" => $height
	    ));
	    $url =  $data["thumbnail_url"];

	    $thumbnailFilePath = Yii::getPathOfAlias("webroot") . "/temp/" . md5($url) . ".jpg";
        file_put_contents($thumbnailFilePath, file_get_contents($url));
	   	
	    $scribd->delete($docId);

	    return array(
	    	$pdfFilePath, $thumbnailFilePath
	    );
	}

	public static function getPdf($filePath,$ext){
		$scribd = self::getInstance();
		$data = $scribd->upload($filePath,$ext);
		$docId = $data["doc_id"];

		self::waitUntilDone($docId);

		$data = $scribd->postRequest("docs.getDownloadUrl",array(
	    	"doc_id" => $docId,
	    	"doc_type" => "pdf"
	    ));
	    $url =  $data["download_link"];

	    $filePath = Yii::getPathOfAlias("webroot") . "/temp/" . md5($url) . ".pdf";
        file_put_contents($filePath, file_get_contents($url));
	    
	    $scribd->delete($docId);

	    return $filePath;

	}

	public static function getThumbnail($filePath,$ext,$width,$height){
		$scribd = self::getInstance();
		$data = $scribd->upload($filePath,$ext);
		$docId = $data["doc_id"];

		self::waitUntilDone($docId);

		$data = $scribd->postRequest("thumbnail.get",array(
	    	"doc_id" => $docId,
	    	"width" => $width,
	    	"height" => $height
	    ));
	    $url =  $data["thumbnail_url"];

	    $filePath = Yii::getPathOfAlias("webroot") . "/temp/" . md5($url) . ".jpg";
        file_put_contents($filePath, file_get_contents($url));
	    
	    $scribd->delete($docId);

	    return $filePath;

	    //echo $url . "\n";

	    //sleep(5);

	    //die($url);

	    /*$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);

        $raw = curl_exec($ch);
        $filePath = Yii::getPathOfAlias("webroot") . "/temp/" . md5($url) . ".jpg";
        file_put_contents($filePath, $raw);

        //die();

	    //$scribd->delete($docId);
        return $filePath;*/
	}

	public static function isDone($docId){
		$scribd = self::getInstance();
		$data = $scribd->postRequest("docs.getConversionStatus",array(
	    	"doc_id" => $docId,
	    ));
	    $status = $data["conversion_status"];
	    return $status == "DONE" || $status == "ERROR";
	}

	public static function waitUntilDone($docId){
		while(!self::isDone($docId)){
			sleep(1);
		}
	} 
}