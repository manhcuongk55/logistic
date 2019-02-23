<?php
class MainMenu extends SMenu {
    protected function getConfig(){
		if($this->isSubMenu)
            return null;
        
        $frontPageLinks = Util::param2("front_page","float_links");
        $frontPageMenu = Util::param2("front_page","menu");

		$config = array(
			"items" => array(
				"__item" => array(
                    "url" => function($item){
                        return Util::controller()->createUrl("/home/" . $item["id"]);
                    }
                ),
                array(
                    "id" => "index",
                    "name" => "Trang chủ"
                ),
                array(
                    "id" => "pricing",
                    "name" => "Bảng giá",
                    "items" => ArrayHelper::get($frontPageMenu,"price",array())
                ),
                array(
                    "id" => "posts",
                    "name" => "Tin tức"
                ),
                array(
                    "id" => "services",
                    "name" => "Dịch vụ",
                    "url" => "javascript:;",
                    "items" => ArrayHelper::get($frontPageMenu,"services",array())
                ),
                array(
                    "id" => "regulations",
                    "name" => "Quy định",
                    "items" => ArrayHelper::get($frontPageMenu,"regulations",array())
                ),
                array(
                    "id" => "download",
                    "name" => "Download",
                    "items" => ArrayHelper::get($frontPageMenu,"download",array())
                ),
                array(
                    "id" => "contact",
                    "name" => "Liên hệ"
                ),
                array(
                    "id" => "chrome_extension",
                    "name" => "Công cụ đặt hàng",
                    "url" => $frontPageLinks["extension_link"]
                )
			),
			"view" => "application.components.menu.views.main_menu"
		);

		return $config;
	}

    public function init(){
        parent::init();
        $this->setActiveItemId(Util::controller()->action->id);
    }
}