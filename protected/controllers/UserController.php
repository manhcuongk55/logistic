<?php
class UserController extends FrontPageBaseController {
	public function init(){
		parent::init();
		$this->layout = "application.views.layouts.user_main";
		$this->viewType = View::TYPE_USER;
	}

	protected function beforeAction($action){
		if(!parent::beforeAction($action))
			return false;
		if(!LoginHelper::requireLogin())
			return false;
		return true;
	}

	public function actionIndex(){
		if(!$this->checkUserPhone()){
			return;
		}
		$this->redirect("/user/active_orders");
		/*if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-index"),
		)))
			return;

		$this->pageTitle = "Trang cá nhân";STATUS_WAIT_FOR_PRICE

		$this->renderWithHttpCacheInfo("index",null,array(
			"differentByUser" => true
		));*/
	}

	public function actionCreate_order(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-orders"),
		)))
			return;

		$this->pageTitle = "Tạo đơn hàng";

		$this->renderWithHttpCacheInfo("create_order",null,array(
			"differentByUser" => true
		));
	}

	public function actionActive_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-orders"),
		)))
			return;

		$this->pageTitle = "Đơn hàng đang hoạt động";

		$this->renderWithHttpCacheInfo("active_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionCompleted_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-orders"),
		)))
			return;

		$this->pageTitle = "Đơn hàng đã hoàn thành";

		$this->renderWithHttpCacheInfo("completed_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionCanceled_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-orders"),
		)))
			return;

		$this->pageTitle = "Đơn hàng đã hủy";
		
		$this->renderWithHttpCacheInfo("canceled_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionAll_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-orders"),
		)))
			return;

		$this->pageTitle = "Tất cả đơn hàng";

		$this->renderWithHttpCacheInfo("all_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionCreate_delivery_order(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-delivery-orders"),
		)))
			return;

		$this->pageTitle = "Tạo vận đơn";

		$this->renderWithHttpCacheInfo("create_delivery_order",null,array(
			"differentByUser" => true
		));
	}

	public function actionActive_delivery_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-delivery-orders"),
		)))
			return;

		$this->pageTitle = "Đơn hàng đang hoạt động";

		$this->renderWithHttpCacheInfo("active_delivery_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionCompleted_delivery_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-delivery-orders"),
		)))
			return;

		$this->pageTitle = "Đơn hàng đã hoàn thành";

		$this->renderWithHttpCacheInfo("completed_delivery_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionCanceled_delivery_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-delivery-orders"),
		)))
			return;

		$this->pageTitle = "Đơn hàng đã hủy";
		
		$this->renderWithHttpCacheInfo("canceled_delivery_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionAll_delivery_orders(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-delivery-orders"),
		)))
			return;

		$this->pageTitle = "Tất cả đơn hàng";

		$this->renderWithHttpCacheInfo("all_delivery_orders",null,array(
			"differentByUser" => true
		));
	}

	public function actionReport(){
		if(!$this->checkUserPhone()){
			return;
		}
		$this->render("report");
	}

	public function actionOrder($order_id){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			"user-order",
			"user-order-$order_id",
			CacheHelper::getKeyForUser("user-order-$order_id"),
		)))
			return;

		$order = $this->getOrder($order_id);
		if(!$order){
			return;
		}
		$this->data["order"] = $order;

		$this->pageTitle = "Đơn hàng" . " #" . $order->id;
		$this->layout = "application.views.layouts.user_main2";
		$this->renderWithHttpCacheInfo("order",null,array(
			"differentByUser" => true
		));
	}

	public function actionDelivery_order($delivery_order_id){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			"user-delivery-order",
			"user-delivery-order-$delivery_order_id",
			CacheHelper::getKeyForUser("user-delivery-order-$delivery_order_id"),
		)))
			return;

		$deliveryOrder = $this->getDeliveryOrder($delivery_order_id);
		if(!$deliveryOrder){
			return;
		}
		$this->data["deliveryOrder"] = $deliveryOrder;

		$this->pageTitle = "Vận đơn" . " #" . $deliveryOrder->id;
		$this->renderWithHttpCacheInfo("delivery_order",null,array(
			"differentByUser" => true
		));
	}

	public function actionAccount_info(){
		if(!$this->checkUserPhone()){
			return;
		}
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-account_info"),
		)))
			return;
		$this->renderWithHttpCacheInfo("account_info",null,array(
			"differentByUser" => true
		));
	}

	public function actionChange_password(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-change_password"),
		)))
			return;

		$this->pageTitle = "Đổi mật khẩu";
		
		$this->renderWithHttpCacheInfo("change_password",null,array(
			"differentByUser" => true
		));
	}

	public function actionOrder_products($order_id){
		if(!$this->checkUserPhone()){
			return;
		}
		$order = Order::model()->findByPk($order_id);
		if(!$order){
			Output::show404();
			return;
		}
		if($order->user_id != Yii::app()->user->id || $order->status != Order::STATUS_WAIT_FOR_PRICE){
			Output::show404();
			return;
		}
		$this->pageTitle = "Đơn hàng #" . $order->id;
		$this->data["order"] = $order;

		$this->render("order_products");
	}

	/*public function actionContact_message(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,array(
			CacheHelper::getKeyForUser("user-contact_message"),
		)))
			return;

		$this->pageTitle = l("home/main","Gửi tin nhắn");

		$this->renderWithHttpCacheInfo("contact_message",null,array(
			"differentByUser" => true
		));
	}*/


	public function actionContact(){
		$this->pageTitle = "Liên hệ CTV";
		$this->render("contact");
	}

	public function actionContact_message_form(){
		$contactMessageForm = new ContactMessageForm();
		$contactMessageForm->user_id = Yii::app()->user->id;
		if($contactMessageForm->run()){
			return;
		}
		$this->data["contactMessageForm"] = $contactMessageForm;
		$this->render("messages");
	}

	public function actionChange_password_form(){
		$form = new ChangePasswordForm();
		$form->run();
	}

	public function actionDelivery_order_form(){
		$form = new DeliveryOrderForm();
		$form->run();
	}

	public function actionUser_form(){
		$form = new UserForm();
		$form->run();
	}

	public function actionOrder_product_list(){
		$list = new OrderProductList();
		$list->run();
	}

	public function actionOrder_list(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,CacheHelper::getKeyForUser("user-order_list")))
			return;
		if(CacheHelper::beginFragmentWithHttpCacheInfo(array(
			"differentByUser" => true
		))){
			$orderList = new OrderList();
			$orderList->run();
			CacheHelper::endFragment();
		}
	}

	public function actionDelivery_order_list(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,CacheHelper::getKeyForUser("user-delivery_order_list")))
			return;
		if(CacheHelper::beginFragmentWithHttpCacheInfo(array(
			"differentByUser" => true
		))){
			$deliveryOrderList = new DeliveryOrderList();
			$deliveryOrderList->run();
			CacheHelper::endFragment();
		}
	}

	private function getOrder($id){
		if(!LoginHelper::requireLogin())
			return false;
		$order = Order::model()->findByAttributes(array(
			"user_id" => Yii::app()->user->id,
			"id" => $id
		));
		if(!$order){
			Output::show404();
			return false;
		}
		return $order;
	}

	private function getDeliveryOrder($id){
		if(!LoginHelper::requireLogin())
			return false;
		$deliveryOrder = DeliveryOrder::model()->findByAttributes(array(
			"user_id" => Yii::app()->user->id,
			"id" => $id
		));
		if(!$deliveryOrder){
			Output::show404();
			return false;
		}
		return $deliveryOrder;
	}

	private function checkUserPhone(){
		if($this->getUser()->phone){
			return true;
		}
		$this->redirect("/user/contact?require_phone_number=1");
	}
	
}