<?php
Yii::import("ext.Son.super-modules.admin-table.*");
class CollaboratorController extends AdminTableBaseController{
	protected $userType = "collaboratorUser";
	protected $menuName = "Cộng tác viên";

    protected function getConfig(){ 
		$arr = array(
			"pageList" => array(
				"collaborators" => "Quản lý thành viên",
				"delivery_orders" => "Quản lý ký gửi",
				"order_products" => "Quản lý sản phẩm",
				"shop_delivery_orders" => "Quản lý vận đơn",
				"orders" => "Quản lý đơn hàng",
				"users" => "Quản lý khách hàng"
			),
			"menu" => array(
				"collaborators" => array(
					"content" => "Thành viên",
					"icon" => "fa fa-users",
				),
				"users" => array(
					"content" => "Khách hàng",
					"icon" => "fa fa-user-md"
				),
				"delivery_orders" => array(
					"content" => "Ký gửi",
					"icon" => "fa fa-truck",
				),
				"orders" => array(
					"content" => "Đơn hàng",
					"icon" => "fa fa-envelope",
				),
				"shop_delivery_orders" => array(
					"content" => "Vận đơn",
					"icon" => "fa fa-envelope",
				),
				"reports" => array(
					"content" => "Thống kê",
					"icon" => "fa fa-line-chart"
				),
				"info" => array(
					"content" => "Thông tin",
					"icon" => "fa fa-info"
				),
				"china_store" => array(
					"content" => "Về kho TQ",
					"icon" => "fa fa-envelope",
				),
				"vietnam_store" => array(
					"content" => "Về kho VN",
					"icon" => "fa fa-envelope",
				),
				"export" => array(
					"content" => "Xuất kho",
					"icon" => "fa fa-envelope",
				),
			),
			"folderName" => "collaborator",
			"defaultPage" => "orders",
			"authenticate" => function($controller){
				if(in_array($controller->action->id,array(
					"login", "logout"
				))){
					return true;
				}
				$loggedIn = !Yii::app()->collaboratorUser->isGuest;
				if(!$loggedIn){
					$controller->redirect("/collaborator/login");
				}
				return $loggedIn;
			},
			"view" => array(
				"layout" => "webroot.themes.giaodichtrungquoc.views.layouts.layout_sidebar"
			),
			"listDefaultConfig" => array(
				"view" => array(
					"viewPath" => array(
						"list" => "webroot.themes.giaodichtrungquoc.views.admin.list_popup",
						"item" => "webroot.themes.giaodichtrungquoc.views.admin.item",
					)
				)
			)
		);

		$user = $this->getUser();
		if($user){
			if(!$user->is_manager){ // TEST
				unset($arr["pageList"]["collaborators"]);
				unset($arr["menu"]["collaborators"]);
			}
			if($user->type!=Collaborator::TYPE_SHIP){
				unset($arr["menu"]["china_store"]);
			}
			if($user->type!=Collaborator::TYPE_STORE){
				unset($arr["menu"]["vietnam_store"]);
				unset($arr["menu"]["export"]);
			}
		}

		return $arr;
	}

	protected function getCurrentFile(){
		return __FILE__;
	}

	protected function onBuildList($list){
		$iframeView = Util::controller()->data["iframeView"];
		$list->setDynamicInput("iframeView",$iframeView);
		parent::onBuildList($list);
	}

	public function getBaseUrl($path=""){
		return $this->createAbsoluteUrl("/collaborator") . "/" . $path . "/";
	}

	public function init(){
		parent::init();
		Yii::app()->theme = "giaodichtrungquoc";
		$this->data["collaboratorNotificationEnabled"] = true;
		$this->data["brandName"] = 'Admin';
		$this->data["homeUrl"] = $this->createUrl("/admin");
		$this->layout = "webroot.themes.giaodichtrungquoc.views.layouts.layout_sidebar";
		parent::init();

		$iframeView = isset($_GET["iframe_view"]);
		$this->data["iframeView"] = $iframeView;
		if($iframeView){
			$this->layout = "webroot.themes.giaodichtrungquoc.views.layouts.layout_iframe";
			$this->config["view"]["layout"] = null;
		}

		$user = $this->getUser();
		if($user){
			Son::load("SAsset")->arrayToJs(array(
				"userID" => "collaborator-$user->id",
				"userInfo" => array(
					"name" => $user->name,
				)
			),"chatUserInfo");
		}
		$this->viewType = View::TYPE_COLLABORATOR;
	}

	public function renderFile($viewFile,$data=null,$return=false){
		Son::load("AdminTableHelper")->defaultButtonClass = "btn btn-sm btn-default";
		return parent::renderFile($viewFile,$data,$return);
	}

	// actions

	public function actionLogin(){
		$this->layout = "webroot.themes.giaodichtrungquoc.views.layouts.layout_login";
		$form = Son::load("CollaboratorUserLoginForm");
		if($form->handle() && !$form->isError())
		{
			return;
		}
		$this->pageTitle = "Đăng nhập";
		$form->renderPage();
	}

	public function actionLogout(){
		Yii::app()->{$this->userType}->logout();
		$key = CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY,Yii::app()->collaboratorUser->id);
		CacheHelper::setLastUpdatedTimeOfDependencyKey($key,time());
		$this->redirect("/collaborator/login");
	}

	public function actionInfo(){
		$this->pageTitle = "Thông tin";
		$this->render("info");
	}

	public function actionReports($id=null){
		$this->render("reports",array(
			"id" => $id
		));
	}

	public function actionOrder_pdf($order_id){
		$order = Order::model()->findByPk($order_id);
		if(!$order){
			return Output::show404();
		}
		$html = $this->renderPartial("order_pdf",array(
			"order" => $order
		),true);
		//echo $html; die();
		Son::loadFile("ext.mpdf.mpdf");
		$mpdf = new mPDF();
		$mpdf->setTitle("ORDERHIP_COM_ORDER #" . $order->id);
		$mpdf->WriteHTML($html);
		$mpdf->Output("ORDERHIP_COM_ORDER_" . $order->id,"I");
	}

	public function actionChina_store(){
		$this->pageTitle = "Về kho Trung Quốc";
		$this->render("china_store_2",array());
	}

	public function actionVietnam_store(){
		$this->pageTitle = "Về kho Việt Nam";
		$this->render("vietnam_store",array());
	}

	public function actionExport(){
		$this->pageTitle = "Xuất kho";
		$this->render("export",array());
	}

	public function actionConfirm_china_shop_delivery_order(){
		if(Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
			return false;
		}
		$deliveryOrderCode = Input::get("delivery_order_code");
		$shopDeliveryOrder = ShopDeliveryOrder::model()->findByAttributes(array(
			"delivery_order_code" => $deliveryOrderCode,
		));
		if(!$shopDeliveryOrder){
			echo CJSON::encode(array(
				"Error" => 1,
				"Message" => "Invalid request",
			));
			return;
		} else {
			$result = $shopDeliveryOrder->setDeliveredStorehouseChina(0);
			if(!$result){
				$errorMessage = $shopDeliveryOrder->getFirstError();
				if(!$errorMessage){
					$errorMessage = $shopDeliveryOrder->listGetLabel("status");
				}
				echo CJSON::encode(array(
					"Error" => 1,
					"Message" => $errorMessage,
					"Data" => $shopDeliveryOrder->getRealAttributes(),
				));
				return;
			}
		}
		echo CJSON::encode(array(
			"Error" => 0,
			"Data" => $shopDeliveryOrder->getRealAttributes(),
		));
	}

	public function actionConfirm_vietnam_shop_delivery_order(){
		if(Util::controller()->getUser()->type!=Collaborator::TYPE_STORE){
            return false;
        }
		$deliveryOrderCode = Input::get("delivery_order_code");
		$shopDeliveryOrder = ShopDeliveryOrder::model()->findByAttributes(array(
			"delivery_order_code" => $deliveryOrderCode,
		));
		if(!$shopDeliveryOrder){
			echo CJSON::encode(array(
				"Error" => 1,
				"Message" => "Invalid request",
			));
			return;
		} else {
			$result = $shopDeliveryOrder->setDeliveredStorehouseVietnam();
			if(!$result){
				$errorMessage = $shopDeliveryOrder->getFirstError();
				if(!$errorMessage){
					$errorMessage = $shopDeliveryOrder->listGetLabel("status");
				}
				echo CJSON::encode(array(
					"Error" => 1,
					"Message" => $errorMessage,
					"Data" => $shopDeliveryOrder->getRealAttributes(),
				));
			}
		}
		echo CJSON::encode(array(
			"Error" => 0,
			"Data" => $shopDeliveryOrder->getRealAttributes(),
		));
	}

	public function actionConfirm_export_shop_delivery_order(){
		if(Util::controller()->getUser()->type!=Collaborator::TYPE_STORE){
            return false;
        }
		$deliveryOrderCode = Input::get("delivery_order_code");
		$shopDeliveryOrder = ShopDeliveryOrder::model()->findByAttributes(array(
			"delivery_order_code" => $deliveryOrderCode,
		));
		if(!$shopDeliveryOrder){
			echo CJSON::encode(array(
				"Error" => 1,
				"Message" => "Invalid request",
			));
			return;
		} else {
			$result = $shopDeliveryOrder->setExported();
			if(!$result){
				$errorMessage = $shopDeliveryOrder->getFirstError();
				if(!$errorMessage){
					$errorMessage = $shopDeliveryOrder->listGetLabel("status");
				}
				echo CJSON::encode(array(
					"Error" => 1,
					"Message" => $errorMessage,
					"Data" => $shopDeliveryOrder->getRealAttributes(),
				));
			}
		}
		echo CJSON::encode(array(
			"Error" => 0,
			"Data" => $shopDeliveryOrder->getRealAttributes(),
		));
	}

	public function actionUpdate_item(){
		$params = json_decode(file_get_contents('php://input'),true);
		$id = Input::get("id");
		$shopDeliveryOrder = ShopDeliveryOrder::model()->findByPk($id);
		if($shopDeliveryOrder){
			$prevTotalWeight = $shopDeliveryOrder->total_weight;
			if(isset($params["block_id"]) && $params["block_id"]){
				$shopDeliveryOrder->block_id = $params["block_id"];
			}
			if(isset($params["total_weight"]) && $params["total_weight"]){
				$shopDeliveryOrder->total_weight = $params["total_weight"];
			}
			if(isset($params["total_volume"]) && $params["total_volume"]){
				$shopDeliveryOrder->total_volume = $params["total_volume"];
			}
			if(isset($params["num_block"]) && $params["num_block"]){
				$shopDeliveryOrder->num_block = $params["num_block"];
			}
			if(isset($params["purchase_price"]) && $params["purchase_price"]){
				$shopDeliveryOrder->purchase_price = $params["purchase_price"];
			}
			if(isset($params["note"]) && $params["note"]){
				$shopDeliveryOrder->note = $params["note"];
			}
			$result = $shopDeliveryOrder->save(true,array(
				"block_id", "total_weight", "num_block", "purchase_price", "note",
			));
			if(!$result){
				echo CJSON::encode(array(
					"Error" => 1,
					"Message" => $shopDeliveryOrder->getFirstError(),
				));
				return;
			}
			if($prevTotalWeight != $shopDeliveryOrder->total_weight){
				$shopDeliveryOrder->order->reCalculateWeightPrice();
			}
		}
		echo CJSON::encode(array(
			"Error" => 0,
			"Data" => array(),
		));
	}

	public function actionUnknown_shop_delivery_order_form(){
		$unknownShopDeliveryOrderForm = new UnknownShopDeliveryOrderForm();
		if($unknownShopDeliveryOrderForm->run()){
			return;
		}
		$unknownShopDeliveryOrderForm->unknown_shop_delivery_order = new UnknownShopDeliveryOrder();
		$unknownShopDeliveryOrderForm->unknown_shop_delivery_order->delivery_order_code = Input::get("delivery_order_code");
		$unknownShopDeliveryOrderForm->renderPage();
	}
}