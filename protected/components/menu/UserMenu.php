<?php
class UserMenu extends SMenu {
    protected function getConfig(){
		if($this->isSubMenu)
			return null;
		$config = array(
			"items" => array(
				"__item" => array(
                    "url" => function($item){
                        return Util::controller()->createUrl("/user/" . $item["id"]);
                    }
                ),
                array(
                    "id" => "orders",
                    "name" => "Đơn hàng",
                    "items" => array(
                        "__item" => array(
                            "url" => function($item){
                                return Util::controller()->createUrl("/user/" . $item["id"]);
                            }
                        ),
                        array(
                            "id" => "create_order",
                            "name" => "Tạo đơn hàng"
                        ),
                        array(
                            "id" => "active_orders",
                            "name" => "Đang hoạt động",
                        ),
                        array(
                            "id" => "completed_orders",
                            "name" => "Hoàn thành"
                        ),
                        array(
                            "id" => "canceled_orders",
                            "name" => "Đã hủy"
                        ),
                        array(
                            "id" => "all_orders",
                            "name" => "Tất cả đơn hàng"
                        ),
                        array(
                            "id" => "report",
                            "name" => "Thống kê"
                        ),
                    )
                ),
                array(
                    "id" => "delivery_orders",
                    "name" => "Kí gửi",
                    "items" => array(
                        "__item" => array(
                            "url" => function($item){
                                return Util::controller()->createUrl("/user/" . $item["id"]);
                            }
                        ),
                        array(
                            "id" => "create_delivery_order",
                            "name" => "Tạo vận đơn",
                        ),
                        array(
                            "id" => "active_delivery_orders",
                            "name" => "Đang hoạt động",
                        ),
                        array(
                            "id" => "completed_delivery_orders",
                            "name" => "Hoàn thành"
                        ),
                        array(
                            "id" => "canceled_delivery_orders",
                            "name" => "Đã hủy"
                        ),
                        array(
                            "id" => "all_delivery_orders",
                            "name" => "Tất cả đơn hàng"
                        ),
                    )
                ),
                array(
                    "id" => "accounts",
                    "name" => "Tài khoản",
                    "items" => array(
                        "__item" => array(
                            "url" => function($item){
                                return Util::controller()->createUrl("/user/" . $item["id"]);
                            }
                        ),
                        /*array(
                            "id" => "account_info",
                            "name" => "Thông tin cá nhân"
                        ),*/
                        array(
                            "id" => "change_password",
                            "name" => "Thay đổi mật khẩu"
                        ),
                    )
                ),
                array(
                    "id" => "contact",
                    "name" => "Liên hệ",
                    "items" => array(
                        "__item" => array(
                            "url" => function($item){
                                return Util::controller()->createUrl("/user/" . $item["id"]);
                            }
                        ),
                        array(
                            "id" => "contact",
                            "name" => "Cập nhật tài khoản"
                        )
                    )
                )
			),
			"view" => "application.components.menu.views.user_menu"
		);

		return $config;
	}

    public function init(){
        parent::init();
        $this->setActiveItemId(Util::controller()->action->id);
    }
}