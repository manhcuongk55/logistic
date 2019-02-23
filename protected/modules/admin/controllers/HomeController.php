<?php
Yii::import("ext.Son.super-modules.admin-table.*");
class HomeController extends AdminTableBaseController {
	protected $userType = "adminUser";

	protected function getConfig(){ 
		$arr = array(
			"pageList" => array(
				"admins" => "Admin",
				"banners" => "Banner",
				"collaborator_groups" => "Nhóm cộng tác viên",
				"collaborators" => "Cộng tác viên",
				"customer_types" => "Loại khách hàng",
				"delivery_orders" => "Vận đơn",
				"order_products" => "Sản phẩm",
				"orders" => "Đơn hàng",
				"pages" => "Quản lý nội dung",
				"posts" => "Bài đăng",
				"subscribers" => "Danh sách theo dõi",
				"users" => "Người dùng",
			),
			"configPageList" => array(
				"front_page" => "Front Page",
				"keywords" => "Từ khóa",
				"setting" => "Cài đặt chung",
				"front_page_content" => "Trang chủ",
				"transfer_accounts" => "Tài khoản ngân hàng",
				"popup_banner" => "Popup banner",
			),
			"menu" => array(
				"banners" => array(
					"content" => "Banner",
					"icon" => "fa fa-picture-o",
				),
				"collaborator_groups" => array(
					"content" => "Nhóm CTV",
					"icon" => "fa fa-users",
				),
				"collaborators" => array(
					"content" => "CTV",
					"icon" => "fa fa-user-md",
				),
				"customer_types" => array(
					"content" => "Loại khách hàng",
					"icon" => "fa fa-user-md",
				),
				"delivery_orders" => array(
					"content" => "Vận đơn",
					"icon" => "fa fa-truck",
				),
				"orders" => array(
					"content" => "Đơn hàng",
					"icon" => "fa fa-envelope-o",
				),
				"pages" => array(
					"content" => "Nội dung",
					"icon" => "fa fa-file-text"
				),
				"posts" => array(
					"content" => "Bài đăng",
					"icon" => "fa fa-pencil",
				),
				/*"subscribers" => array(
					"content" => "Danh sách theo dõi",
					"icon" => "fa fa-thumbs-o-up",
				),*/
				"users" => array(
					"content" => "Khách hàng",
					"icon" => "fa fa-user",
				),
				"front_page" => array(
					"content" => "Front Page",
					"icon" => "fa fa-globe",
				),
				"keywords" => array(
					"content" => "Từ khóa",
					"icon" => "fa fa-file-word-o",
				),
				"setting" => array(
					"content" => "Cài đặt chung",
					"icon" => "fa fa-cog",
				),
				"front_page_content" => array(
					"content" => "Trang chủ",
					"icon" => "fa fa-home",
				),
				"transfer_accounts" => array(
					"content" => "Tài khoản ngân hàng",
					"icon" => "fa fa-money"
				),
				"popup_banner" => array(
					"content" => "Popup banner",
					"icon" => "fa fa-home"
				),
				"admins" => array(
					"content" => "Admin",
					"icon" => "fa fa-key"
				)
			),
			"folderName" => "admin",
			"defaultPage" => "orders",
			"authenticate" => function($controller){
				if(in_array($controller->action->id,array(
					"login", "logout"
				))){
					return true;
				}
				$loggedIn = !Yii::app()->{$this->userType}->isGuest;
				if(!$loggedIn){
					$controller->redirect("/admin/home/login");
				}
				return $loggedIn;
			},
			"view" => array(
				"layout" => "webroot.themes.giaodichtrungquoc.views.layouts.layout_sidebar"
			),
			"listDefaultConfig" => array(
				"view" => array(
					"viewPath" => array(
						"list" => "webroot.themes.giaodichtrungquoc.views.admin.list",
						"item" => "webroot.themes.giaodichtrungquoc.views.admin.item",
					)
				)
			),
			"configPageDefaultConfig" => array(
				"viewPath" => array(
					"main" => "webroot.themes.giaodichtrungquoc.views.admin.config_page"
				)
			)
		);

		$adminPermissions = array(
			Admin::TYPE_ACCOUNTANT => array(
				"orders", "users", "customer_types", 
			),
			Admin::TYPE_CUSTOMER_CARE => array(
				"orders","users", "customer_types", "collaborator_groups", "collaborators","pages", "posts", "banners", "front_page_content", "popup_banner",
			),
		);

		$adminType = Yii::app()->adminUser->getState("admin_type");
		if(!$adminType){
			$arr["menu"] = array();
		} else if($adminType!=Admin::TYPE_FULL_PERMISSION){
			$menu = array();
			foreach($adminPermissions[$adminType] as $item){
				$menu[$item] = $arr["menu"][$item];
			}

			$arr["menu"] = $menu;
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
		return $this->createAbsoluteUrl("/admin/home") . "/" . $path . "/";
	}

	public function init(){
		Yii::app()->theme = "giaodichtrungquoc";
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
				"userID" => "admin-$user->id",
				"userInfo" => array(
					"name" => $user->name,
				)
			),"chatUserInfo");
		}
		$this->viewType = View::TYPE_ADMIN;
	}

	public function renderFile($viewFile,$data=null,$return=false){
		Son::load("AdminTableHelper")->defaultButtonClass = "btn btn-sm btn-default";
		return parent::renderFile($viewFile,$data,$return);
	}

	// actions

	public function actionLogin(){
		$this->layout = "webroot.themes.giaodichtrungquoc.views.layouts.layout_login";
		$form = Son::load("AdminUserLoginForm");
		if($form->handle() && !$form->isError())
		{
			return;
		}
		$this->pageTitle = "Đăng nhập";
		$form->renderPage();
	}

	public function actionLogout(){
		Yii::app()->adminUser->logout();
		$this->redirect("/admin/home/login");
	}
}