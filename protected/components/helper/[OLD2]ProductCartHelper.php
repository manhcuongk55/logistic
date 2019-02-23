<?php
class ProductCartHelper {
	static $items = null;

	public static function getTotalCount(){
		$total = 0;
		$items = self::getItems();
		foreach($items as $item){
			$total += $item->count;
		}
		return $total;
	}

	public static function getFilePath($sessionID=null){
		if($sessionID==null){
			$sessionID = @Yii::app()->request->cookies["sessionID"]->value;
		}
		if(!$sessionID){
			return null;
		}
		return "./fetched_data/$sessionID.json";
	}

	public static function getSavedItems($sessionID=null){
		$data = @file_get_contents(self::getFilePath($sessionID));
		if(!$data){
			return array();
		}
		$arr = json_decode($data,true);
		if(!$arr){
			return array();
		}
		return $arr;
	}

	public static function saveItems($items,$sessionID=null){
		$toBeSavedItems = array();
		foreach($items as $i => $item){
			if(ArrayHelper::get($item,"count",0) <= 0){
				continue;
			} else if(!isset($item["type"])){
				$item = ProductCartItem::fetch($item);
			}
			$toBeSavedItems[] = $item;
		}
		if($sessionID==null){
			$sessionID = @Yii::app()->request->cookies["sessionID"]->value;
		}
		if($sessionID==null){
			$sessionID = Util::generateRandomString();
			Yii::app()->request->cookies["sessionID"] = new CHttpCookie('sessionID', $sessionID, array(
				"expire" => time()+60*60*24*180
			));
		}
		file_put_contents(self::getFilePath($sessionID),json_encode($toBeSavedItems));
		CacheHelper::clearAllPage();
	}

	public static function clearSavedItems($sessionID=null){
		@unlink(self::getFilePath($sessionID));
		CacheHelper::clearAllPage();
	}

	public static function getItems($sessionID=null){
		if(self::$items===null){
			$arr = self::getSavedItems($sessionID);
			$items = array();
			foreach($arr as $item){
				$newItem = new ProductCartItem();
				$newItem->parse($item);
				$items[] = $newItem;
			}
			self::$items = $items;
		}
		return self::$items;
	}

	public static function handle(){
		try {
			$action = Input::post("action");
			if($action){
				$redirectUrl = Input::post("redirect_url");
			} else {
				$action = Input::get("action");
				$redirectUrl = Input::get("redirect_url");
			}

			$returnResult = function($errorMessage=null) use ($redirectUrl){
				if($redirectUrl){
					if($errorMessage){
						$redirectUrl .= "?error=" . $errorMessage;
					}
					Util::controller()->redirect($redirectUrl);
				} else {
					Output::returnJsonSuccess();
				}
			};

			switch ($action) {
				case "get_num_item_in_cart":
					$url = Input::post("url");
					$items = self::getSavedItems();
					$count = 0;
					foreach($items as $item) {
						if($item["url"]==$url){
							$count = ArrayHelper::get($item,"count",1);
							break;
						}
					}
					Output::returnJsonSuccess(array(
						"count" => $count,
						"total" => count($items)
					));
					break;
				case "get_items":
					$items = self::getSavedItems();
					Output::returnJsonSuccess(array(
						"items" => $items
					));
					break;
				case "add_get":
					$url = Input::get("url");
					$count = Input::get("count",1);
					$items = self::getSavedItems();
					$items[] = array(
						"url" => $url,
						"count" => $count
					);
					self::saveItems($items);
					$returnResult();
					break;
				case "add":
					$addedItems = Input::post("items");
					$items = self::getSavedItems();
					$items = array_merge($items,$addedItems);
					self::saveItems($items);
					$returnResult();
					break;
				case "update_all":
					$items = Input::post("items");
					if(!$items){
						$items = array();
					}
					self::saveItems($items);
					$returnResult();
					break;
				case "upload_file":
					try {
						$file = $_FILES["file"];
						$fileName = $file["tmp_name"];
						Son::loadFile("ext.PHPExcel.PHPExcel.IOFactory");
						$excelReader = PHPExcel_IOFactory::createReaderForFile($fileName);
						$excelReader->setReadDataOnly();
						$excelReader->setLoadSheetsOnly(array("Sheet1"));
						$excelObj = @$excelReader->load($fileName);
						$excelObj->setActiveSheetIndexByName("Sheet1");
						$arr = $excelObj->getActiveSheet()->toArray(null, true,true,true);
						$addedItems = array();
						foreach($arr as $i => $row){
							if ($i > 14){
								if(!$row["C"]){
									break;
								}
								$item = array(
									"url" => $row["C"],
									"count" => intval($row["G"]),
									"description" => $row["E"],
									//"name" => $row["D"] 
								);
								$addedItems[] = $item;
							}
						}
						$items = self::getSavedItems();
						$items = array_merge($items,$addedItems);
						self::saveItems($items);
					} catch(Exception $ex){
						$returnResult("File không hợp lệ");
						return;
					}
					$returnResult();
					break;
				case "delete":
					$url = Input::post("url");
					$items = self::getSavedItems();
					foreach($items as $i => $item){
						if($item["url"]==$url){
							array_splice($items, $i, 1);
							break;
						}
					}
					self::saveItems($items);
					$returnResult();
					break;
				case "delete_all":
					self::clearSavedItems();
					$returnResult();
					break;
				case "make_order":
					$items = Input::post("items");
					if($items){
						self::saveItems($items);
					}
					OrderHelper::makeOrder();
					break;
				default:
					echo "invalid request";
			}
		} catch(Exception $ex){
			echo $ex->getMessage();
		}
	}
}

class ProductCartItem {
	public $url;
	public $count;
	public $name;
	public $image;
	public $description;
	public $type;
	public $original_name;
	public $web_price;
	public $shop_id;
	public $is_updating;

	public function parse($item){
		$this->url = $item["url"];
		$this->count = ArrayHelper::get($item,"count",1);
		$this->name = ArrayHelper::get($item,"name");
		$this->image = ArrayHelper::get($item,"image");
		$this->description = ArrayHelper::get($item,"description");
		$this->type = ArrayHelper::get($item,"type");
		$this->original_name = ArrayHelper::get($item,"original_name");
		$this->web_price = ArrayHelper::get($item,"web_price");
		$this->shop_id = ArrayHelper::get($item,"shop_id");
		$this->is_updating = ArrayHelper::get($item,"is_updating");
	}

	public static function fetch($item){
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
		return $fetchedItem;
	}
}	