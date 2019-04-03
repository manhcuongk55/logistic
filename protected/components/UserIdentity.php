<?php
class UserIdentity extends CUserIdentity {
	var $_id;
	public $ignoreCheckUnactivedEmail = false;
	protected $_user = null;

	public function authenticate(){
		$criteria = new CDbCriteria();
		$criteria->compare("email",$this->username,false);
		$user = User::model()->find($criteria);
		if(!$user->verifyPassword($this->password)){
			return false;
		}
		if(isset(Yii::app()->session["login_collaborator_id"])){
			$collaboratorID = Yii::app()->session["login_collaborator_id"];
			$collaborator = Collaborator::model()->findByPk($collaboratorID);
			if($collaborator){
				$user->collaborator_group_id = $collaborator->collaborator_group_id;
				$user->collaborator_id = $collaborator->id;
				$user->save(true,array(
					"collaborator_group_id", "collaborator_id"
				));
			}
			unset(Yii::app()->session["login_collaborator_id"]);
		}
		if(!$this->ignoreCheckUnactivedEmail){
			if(!$user->is_email_active){
				return "has_not_active_email";
			}
		}
		if(!$user || !$user->active){
			return false;
		}
		$this->setUser($user);
		return true;
	}

	public function setUser($user){
		$this->_id = $user->id;
		$this->setState('id', $this->_id);
        $this->setState('name', $this->username);
		$this->_user = $user;
	}

	public function getUser(){
		return $this->_user;
	}

	public function getId(){
		return $this->_id;
	}
}