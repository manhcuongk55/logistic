<?php

class SiteController extends FrontPageBaseController
{
	public function init(){
		parent::init();
		$this->layout = "application.views.layouts.login";
		$this->viewType = View::TYPE_USER;
	}

	public function actionLogout(){
		Yii::app()->user->logout();
		CacheHelper::clearAllHttpCacheForUser();
		$this->redirect("/");
	}

	public function actionLogin(){
		LoginHelper::setLoginCallbackUrlIfExist();
		$loginForm = new LoginForm();
		if($loginForm->run()){
			CacheHelper::clearAllHttpCacheForUser();
			return;
		}
		Yii::app()->session["login_collaborator_id"] = Input::get("collaborator_id");
		$this->pageTitle = l("home/main","Đăng nhập");
		$this->render("login_page");
	}

	public function actionSignup(){
		LoginHelper::setLoginCallbackUrlIfExist();
		$signupForm = new SignupForm();
		if($signupForm->run()){
			CacheHelper::clearAllHttpCacheForUser();
			return;
		}
		$this->pageTitle = "Đăng ký";
		$this->render("signup_page");
	}

	public function actionLogin_with_google(){
		LoginHelper::setLoginCallbackUrlIfExist();
		Yii::import("ext.GoogleHelper2.*");
		$googleHelper = new GoogleHelper();
		$googleHelper->redirect();
	}

	public function actionGoogle_login_callback(){
		Yii::import("ext.GoogleHelper2.*");
		$googleHelper = new GoogleHelper();
		list($accessToken,$userInfo) = $googleHelper->loginCallback();
		$accessTokenString = json_encode($accessToken);
		$user = User::model()->findByAttributes(array(
			"email" => $userInfo["email"]
		));
		if($user){
			$user->google_token = $accessTokenString;
			$user->save(false,array(
				$accessTokenString
			));
		} else {
			$user = new User();
			$user->updateInfoFromGoogle($userInfo,$accessTokenString);
		}

		$identity = new UserIdentity($user["email"],"");
		$identity->defineUser($user);
		$duration = 3600 * 24 * 30 * 6; // 6 months
		Yii::app()->user->logout();
		Yii::app()->user->login($identity,$duration);
		$url = LoginHelper::getLoginCallbackUrl();
		CacheHelper::clearAllHttpCacheForUser();
		$this->redirect($url);
	}

	public function actionLogin_with_facebook(){
		$this->redirect("/site/login_with_facebook_bt");
		return;
		LoginHelper::setLoginCallbackUrlIfExist();
		Yii::import("ext.facebook.FacebookHelper");
		$fb = FacebookHelper::getInstance();
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email']; // optional
		$loginUrl = $helper->getLoginUrl($this->createAbsoluteUrl("/site/facebook_login_callback"), $permissions);
		$this->redirect($loginUrl);
	}

	public function actionFacebook_login_callback($accessToken=""){
		Yii::import("ext.facebook.FacebookHelper");
		$fb = FacebookHelper::getInstance();
		if(!$accessToken){
			$helper = $fb->getRedirectLoginHelper();
			try {
				$accessToken = $helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
		}

		if (isset($accessToken)) {
			$accessToken = (string)$accessToken;
			try {
				// Returns a `Facebook\FacebookResponse` object
				$response = $fb->get('/me?fields=id,name,email,picture.width(720).height(720)', $accessToken);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}

			$fbUser = $response->getGraphUser();

			//var_dump($fbUser);die();
			$email = @$fbUser["email"];
			if(!$email){
				$email = $fbUser["id"] . "@facebook.com"; 
			}

			$user = User::model()->findByAttributes(array(
				"email" => $email
			));
			$isNewUser = false;
			if($user){
				$user->facebook_access_token = $accessToken;
				$user->active = 1;
				if(isset(Yii::app()->session["login_collaborator_id"])){
					$collaboratorID = Yii::app()->session["login_collaborator_id"];
					$collaborator = Collaborator::model()->findByPk($collaboratorID);
					if($collaborator){
						$user->collaborator_group_id = $collaborator->collaborator_group_id;
						$user->collaborator_id = $collaborator->id;
					}
					unset(Yii::app()->session["login_collaborator_id"]);
				}
				$user->save(false,array(
					"active", "facebook_access_token", "collaborator_group_id", "collaborator_id"
				));
				
			} else {
				$isNewUser = true;
				$user = new User();
				$user->name = $fbUser["name"];
				$user->email = $email;
				$user->is_email_active = 1;
				$user->active = 1;
				$user->facebook_access_token = $accessToken;
				$user->facebook_id = $fbUser["id"];
				$user->fileGetFromUrl(array(
					"image" => $fbUser["picture"]["url"]
				));
				if(isset(Yii::app()->session["login_collaborator_id"])){
					$collaboratorID = Yii::app()->session["login_collaborator_id"];
					$collaborator = Collaborator::model()->findByPk($collaboratorID);
					if($collaborator){
						$user->collaborator_group_id = $collaborator->collaborator_group_id;
						$user->collaborator_id = $collaborator->id;
					}
					unset(Yii::app()->session["login_collaborator_id"]);
				}
				if(!$user->save()){
					echo $user->getFirstError();
					return;
				}
			}
			$identity = new UserIdentity($user->email,"");
			$identity->ignoreCheckUnactivedEmail = true;
			$identity->setUser($user);
			$duration= 3600 * 24 * 7; // 7 days
			Yii::app()->user->login($identity,$duration);
			$url = LoginHelper::getLoginCallbackUrl();
			CacheHelper::clearAllHttpCacheForUser();
			$this->redirect($url);
		}
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			$this->layout = "application.views.layouts.simple";
			$this->render('error', $error);
		}
	}

	public function actionSearch(){
		$this->doSearchModel();
	}

	public function actionSummernote_upload(){
		if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = md5(rand(100, 200) . Util::generateUUID() . $_FILES['file']['name']);
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $folder = Yii::getPathOfAlias("webroot.img.upload.summernote");
                if (!file_exists($folder)) {
		            mkdir($folder, 0777, true);
		        }
                $destination = $folder . "/" . $filename;
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                $file = '/img/upload/summernote/' . $filename;//change this URL
                Output::returnJsonSuccess(array(
                	"url" => $file
                ));
            }
            else
            {
				Output::returnJsonError('Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error']);
            }
        }
	}

	public function actionConfirm_email(){
		$emailConfirmToken = Input::get("token",array(
			array("required")
		),"html");
		$user = User::model()->findByAttributes(array(
			"is_email_active" => 0,
			"email_active_token" => $emailConfirmToken
		));
		if(!$user){
			echo "invalid token";
			return;
		}
		$user->is_email_active = 1;
		$user->active = 1;
		$user->email_active_token = null;
		$user->save(true,array(
			"is_email_active", "active", "email_active_token"
		));
		?>
			<div>Email confirmed. You will be redirected to the home page right now!</div>
			<script>
				setTimeout(function(){
					location.href = "/";
				},5000);
			</script>	
		<?php
	}

	public function actionForgot_password(){
		$emailConfirmToken = Input::get("token");
		if(!$emailConfirmToken){
			$forgotPasswordForm = new ForgotPasswordForm();
			if($forgotPasswordForm->run()){
				return;
			}
			$forgotPasswordForm->renderPage();
			return;
		} else {
			$user = User::model()->findByAttributes(array(
				"email_active_token" => $emailConfirmToken
			));
			if(!$user){
				echo "invalid token";
				return;
			}
			$renewPasswordForm = new RenewPasswordForm($emailConfirmToken);
			$renewPasswordForm->renderPage();
		}
	}

	public function actionRenew_password_form(){
		$renewPasswordForm = new RenewPasswordForm();
		$renewPasswordForm->run();
	}

	public function actionNot_forgot_password(){
		$emailConfirmToken = Input::get("token",array(
			array("required")
		),"html");
		$user = User::model()->findByAttributes(array(
			"email_active_token" => $emailConfirmToken
		));
		if(!$user){
			echo "invalid token";
			return;
		}
		$user->email_active_token = null;
		$user->save(true,array(
			"email_active_token"
		));
		?>
			<div>BẠN ĐÃ XÁC THỰC THÀNH CÔNG! TRANG SẼ ĐƯỢC CHUYỂN NGAY SAU ĐÂY!</div>
			<script>
				setTimeout(function(){
					location.href = "/";
				},5000);
			</script>	
		<?php
	}

	public function actionChange_language(){
		$lang = Input::get("lang",array(
			array("required")
		),"html");
		MultiLanguage::setLanguage($lang);
		$callbackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
		$this->redirect($callbackUrl);
	}

	public function actionChange_currency(){
		$currency = Input::get("currency",array(
			array("required")
		),"html");
		CurrencyHelper::setCurrency($currency);
		$callbackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/";
		$this->redirect($callbackUrl);
	}

	public function actionLogin_with_facebook_bt(){
		LoginHelper::setLoginCallbackUrlIfExist();
		$url = "https://bangtuong.com/site/login_with_facebook_od";
		$this->redirect($url);
	}

	public function actionFacebook_login_callback_bt(){
		$this->actionFacebook_login_callback(Input::get("access_token"));
	}
	//tranxuancuong 9/3/2019
	//create new page to redirect after signup
	public function actionRedirect_page_after_signup(){
		$this->render('/site/redirect_page_after_signup');
	}
}
