<?php
ini_set('memory_limit','1000M');
set_time_limit(2000);

$_SERVER["HTTP_USER_AGENT"] = $server_http_user_agent;

foreach($items as $item){
    $fetchedItem = ProductFetcher::fetch($item["url"],$item);
    if(!$fetchedItem){
        $fetchedItem = $item;
    } else {
        $fetchedItem["count"] = $item["count"];
        $fetchedItem["description"] = ArrayHelper::get($item,"description");
        if($fetchedItem["description"]){
            if(!@json_encode(array(
                "test" => $fetchedItem["description"]
            ))){
                $fetchedItem["description"] = @mb_convert_encoding($fetchedItem["description"], 'UTF-8', 'GB2312');
            }
        }
    }
    $fetchedItem["id"] = $item["id"];
    $folder = ProductCartHelper::getFolderPath($session_id);
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }
    file_put_contents(ProductCartHelper::getFilePath($session_id . "/" . $item["id"]),json_encode($fetchedItem));
    CacheHelper::clearAllPage();
}
