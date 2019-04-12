<?php
class TestController extends Controller {
	public function actionIndex(){
		return;
		$arr = array(
			"shop_name", "delivery_price_ndt", "real_price", "shop_id", "order_code",
		);
		$step = 1000;
		$offset = 0;
		while(true){
			$shopDeliveryOrders = ShopDeliveryOrder::model()->findAll(array(
				"limit" => $step,
				"offset" => $offset,
				"condition" => "id > 6122"
			));
			if(!count($shopDeliveryOrders)){
				break;
			}
			foreach($shopDeliveryOrders as $shopDeliveryOrder){
				$order = $shopDeliveryOrder->order;
				if(!$order){
					continue;
				}
				foreach($arr as $prop){
					$order->$prop = $shopDeliveryOrder->$prop;
				}
				$result = @$order->save($arr);
				if(!$result){
					echo "$order->id FALSE<br/>";
				} else {
					echo "$order->id TRUE<br/>";
				}
			}
			$offset += $step;
		}
		// $model = User::model()->findByAttributes(array("email" => "thanhtung9630@gmail.com"));
		// $model->password = "123456";
		// $result = $model->save();
		// var_dump($result);
		// echo '<meta charset="utf-8">';
		// echo $this->renderPartial("application.views.messages.money",array(
		// 	"value" => 1000000000,
		// ),true);
	}

	public function actionChange_header(){
		$_SERVER['HTTP_USER_AGENT'] = "abc";
		echo $_SERVER["HTTP_USER_AGENT"];
	}

	public function actionGet_session_id(){
		$sessionID = Yii::app()->session->sessionID;
		echo $sessionID;
	}

	public function actionSet_var_with_session_id($session_id,$var,$value){
		Yii::app()->session->setSessionID($session_id);
		Yii::app()->session[$var] = $value;
		echo "success";
	}

	public function actionGet_var($var){
		echo Yii::app()->session[$var];
	}

	function HumanSize($bytes)
	{
		$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
    	$base = 1024;
    	$class = min((int)log($bytes , $base) , count($si_prefix) - 1);
    	return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
	}

	public function actionDisk(){
		echo '<meta charset="utf-8">';
		echo "Dung lượng còn lại: <b>" . $this->HumanSize(disk_free_space("/")) . "</b>" . "  /  " . "<b>" . $this->HumanSize(disk_total_space("/")) . "</b>";
	}

	public function actionClear_http_cache(){
		CacheHelper::clearAllHttpCache();
	}
	
	public function actionClear_cache(){
		Yii::app()->cache->flush();
	}

	public function actionClear_all_cache(){
		CacheHelper::clearAllHttpCache();
		Yii::app()->cache->flush();
	}

	public function actionClear_recent_activities(){
		Yii::app()->setGlobalState("recentActivities",array());
	}

	public function actionFix(){
		return;
		$orders_8 = Order::model()->findAllByAttributes(array(
			"status" => 8
		));
		$orders_9 = Order::model()->findAllByAttributes(array(
			"status" => 9
		));
		$i = 0;
		$j = 0;
		foreach($orders_8 as $order8){
			$order8->status = 9;
			$order8->save(array(
				"status"
			));
			$i++;
		}
		foreach($orders_9 as $order8){
			$order8->status = 8;
			$order8->save(array(
				"status"
			));
			$j++;
		}
		echo "done $i $j";
	}
}