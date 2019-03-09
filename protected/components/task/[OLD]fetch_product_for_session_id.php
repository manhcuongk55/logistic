<?php
ini_set('memory_limit','1000M');
set_time_limit(2000);

$_SERVER["HTTP_USER_AGENT"] = $server_http_user_agent;

$fetchedItems = array();
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
    $fetchedItems[] = $fetchedItem;
}

$items = ProductCartHelper::getSavedItems($session_id);
$newItems = array();

function checkInItems($fetchedItems,$item){
    foreach($fetchedItems as $fetchedItem){
        if($fetchedItem["id"]==$item["id"]){
            return true;
        }
    }
    return false;
}

foreach($items as $item){
    if(checkInItems($fetchedItems,$item))
        continue;
    $newItems[] = $item;
}
$items = array_merge($newItems,$fetchedItems);
ProductCartHelper::saveItems($items,$session_id);