<?php
class CollaboratorUserIdentity extends CUserIdentity {
	var $_id;

	public function authenticate(){
		$collaboratorUser = Collaborator::model()->findByAttributes(array(
			"email" => $this->username
		));
		if(!$collaboratorUser || !$collaboratorUser->active)
			return false;
		if(!$collaboratorUser->verifyPassword($this->password))
			return false;

		$this->_id = $collaboratorUser->id;

		$this->setState("id", $collaboratorUser->id);
        $this->setState("name", $collaboratorUser->name);
		return true;
	}

	public function getId(){
		return $this->_id;
	}
}