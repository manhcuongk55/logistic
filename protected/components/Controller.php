<?php
class Controller extends SController {
	protected $viewType;

	protected $userType = "user";
	protected function beforeAction($action){
		if(!parent::beforeAction($action))
			return false;
		include_once(dirname(__FILE__) . "/code/" . "before_action.php");
		return true;
	}

	public function getUser(){
		return Yii::app()->{$this->userType}->getUser();
	}

	public function init(){
		parent::init();
		$this->viewType = View::TYPE_GUEST;
	}

	protected function beforeRender($view){
		if(!parent::beforeRender($view))
			return false;
		// if(http_response_code()==200){
			$view = new View();
			$view->url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$view->ip = Input::getIpAddress();
			$view->user_agent = $_SERVER['HTTP_USER_AGENT'];
			if($user = $this->getUser()){
				$view->type = $this->viewType;
				$view->user_id = $user->id;
			} else {
				$this->viewType = View::TYPE_GUEST;
			}
			$view->save();
		// }
		return true;
	}
}