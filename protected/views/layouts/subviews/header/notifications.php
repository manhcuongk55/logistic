<?php
if(Yii::app()->user->isGuest) return;
$simpleNotificationList = new SimpleNotificationList();
$simpleNotificationList->setDynamicInput("user_type",$user_type);
$simpleNotificationList->setDynamicInput("user_id",$this->getUser()->id);
$simpleNotificationList->config["baseUrl"] = $this->createUrl("/home/simple_notification_list?");
?>
<?php $simpleNotificationList->render(); ?>