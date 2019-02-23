<?php
return array(
	"modelInvalidateIds" => array(
		"Admin" => array(
			"chat-receivers"
		),
		"Banner" => array(
			
		),
		"Collaborator" => array(
			"chat-receivers"
		),
		"CollaboratorGroup" => array(
		
		),
		"DeliveryOrder" => array(
		
		),
		"Notification" => array(
			function($model){
				return CacheHelper::getKeyForUser("home-simple_notification_list",$model->user_id);
			},
			function($model){
				return CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY,$model->user_id);
			},
			function($model){
				return CacheHelper::getKeyForUser("web-user",$model->user_id);
			}
		),
		"Order" => array(
			function($model){
				return CacheHelper::getKeyForUser("user-orders",$model->user_id);
			},
			function($model){
				return CacheHelper::getKeyForUser("user-order-" . $model->id,$model->user_id);
			},
			function($model){
				return CacheHelper::getKeyForUser("user-order_list",$model->user_id);
			},
			"order_list",
			"collaborator-report3",
			function($model){
				return "user-report-" . $model->user_id;
			}
		),
		"OrderProduct" => array(
			function($model){
				return CacheHelper::getKeyForUser("user-order-" . $model->order_id,$model->order->user_id);
			},
		),
		"Page" => array(
			"home-page",
			function($model){
				return "home-page-" . $model->page_id;
		  	}
		),
		"Post" => array(
			"home-posts",
			function($model){
				return "home-post-" . $model->id;
			}
		),
		"ShopDeliveryOrder" => array(
		
		),
		"User" => array(
			"chat-receivers"
		),
	)
);
