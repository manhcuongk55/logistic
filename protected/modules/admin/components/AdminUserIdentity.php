<?php
class AdminUserIdentity extends CUserIdentity {
	var $_id;

	public function authenticate(){
		$adminUser = Admin::model()->findByAttributes(array(
			"email" => $this->username
		));
		if(!$adminUser || !$adminUser->active)
			return false;
		if(!$adminUser->verifyPassword($this->password))
			return false;

		$this->_id = $adminUser->id;

        $this->setState("type","admin");
		$this->setState("id", $adminUser->id);
        $this->setState("name", $adminUser->name);
        $this->setState("admin_type",$adminUser->type);
		return true;
	}

	public function getId(){
		return $this->_id;
	}
}