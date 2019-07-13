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
?>