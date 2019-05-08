<?php
class ProductFetcher {
    public static function fetch($url,$item){
        $brand = self::checkBrand($url);
        if(!$brand)
            return false;
        $fetchedItem = null;
        switch($brand){
            case OrderProduct::WEBSITE_TYPE_TAOBAO:
                $fetchedItem = self::fetchTaobao($url,$item);
                break;
            case OrderProduct::WEBSITE_TYPE_TMALL:
                $fetchedItem = self::fetchTmall($url,$item);
                break;
            case OrderProduct::WEBSITE_TYPE_1688:
                $fetchedItem = self::fetch1688($url,$item);
                break;
            case OrderProduct::WEBSITE_TYPE_OTHER:
                $fetchedItem = array();
                break;
            default:
                return false;
        }

        if($fetchedItem===false)
            return false;
        if(!@$fetchedItem["url"]){
            $fetchedItem["url"] = $url;
        }
        if(!@$fetchedItem["shop_id"]){
            $fetchedItem["shop_id"] = $url;
        }

        Yii::import("ext.GoogleTranslateHelper.GoogleTranslateHelper");
        if(@$fetchedItem["web_price"]){
            $fetchedItem["web_price"] = self::getPrice($fetchedItem["web_price"]);
        }

        if(@$item["name"]){
            $fetchedItem["name"] = $item["name"];
        }else if(@$fetchedItem["name"]){
            $fetchedItem["name"] = GoogleTranslateHelper::translate($fetchedItem["original_name"],"auto","vi");
        }
        $fetchedItem["type"] = $brand;

        //them fetche item moi

         $fetchedItem["LinkNhaCungCap"] = $item["LinkNhaCungCap"];
        return $fetchedItem;
    }

    private static function getPrice($priceStr){
        return @floatval(str_replace(",",".",$priceStr));
    }

    private static function checkBrand($url){
        $parse = parse_url($url);
        $domain = @$parse['host'];
        if(!$domain){
            return false;
        }
        if(strpos($domain,"taobao")!==false){
            return OrderProduct::WEBSITE_TYPE_TAOBAO;
        } else if(strpos($domain,"tmall")!==false){
            return OrderProduct::WEBSITE_TYPE_TMALL;
        } else if(strpos($domain,"1688")!==false){
            return OrderProduct::WEBSITE_TYPE_1688;
        }

        return OrderProduct::WEBSITE_TYPE_OTHER;
    }

    private static function fetch1688($url,$item){
		Yii::import("ext.ogp.MetaData");
		$graph = @MetaData::fetch($url);
        if(!$graph)
            return array();
        $arr = array(
            "url" => $url,
            "original_name" => @mb_convert_encoding($graph->{"og:title"}, 'UTF-8', 'GB2312'),
            "web_price" => @$graph->{"og:product:price"}
        );
        if(!isset($item["image"]) || !$item["image"]){
            $arr["image"] = @$graph->{"og:image"};
        } else {
            $arr["image"] = $item["image"];
        }
        if(!isset($item["shop_id"]) || !$item["shop_id"]){
            preg_match('/<a href\="([^"]*)"><div class\="company\-name"/',$graph->_HTML,$result);
            @$arr["shop_id"] = @$result[1];
        }
        return $arr;
    }

    private static function fetchTaobao($url,$item){
       Yii::import("ext.ogp.MetaData");
		$graph = @MetaData::fetch($url);
        if(!$graph)
            return array();
        $arr = array(
            //"url" => $graph->{"og:url"},
            "original_name" => @mb_convert_encoding($graph->{"og:title"}, 'UTF-8', 'GB2312'),
            "web_price" => @$graph->{"product:price:amount"}
        );
        if(!isset($item["image"]) || !$item["image"]){
            $arr["image"] = @$graph->{"og:image"};
        } else {
            $arr["image"] = $item["image"];
        }
        if(!isset($item["shop_id"]) || !$item["shop_id"]){
            preg_match('/<div class\="tb\-shop\-icon">\s*<a href\="([^"]*)">/',$graph->_HTML,$result);
            @$arr["shop_id"] = @$result[1];
        }
        return $arr;
    }

    private static function fetchTmall($url,$item){
        $arr = array(
            "url" => $url
        );
        $html = file_get_contents($url);
        preg_match('/<div class\="tb\-detail\-hd">[^<]*<h1[^>]*>([^<]*)<\/h1>/',$html,$result);
        if($result){
            $originalName = $result[1];
            $arr["original_name"] = @mb_convert_encoding($originalName, 'UTF-8', 'GB2312');
        }

        if(!isset($item["shop_id"]) || !$item["shop_id"]){
            preg_match("/shopUrl: '([^']*)'/",$html,$result);
            if($result){
                $arr["shop_id"] = $result[1];
            }
        }

        $arr["web_price"] = 0;

        if(!isset($item["image"]) || !$item["image"]){
            preg_match('/<img id\="J_ImgBooth".* src\="([^"]*)"/',$html,$result);
            @$arr["image"] = $result[1];
        } else {
            $arr["image"] = $item["image"];
        }

        return $arr;
    }
}