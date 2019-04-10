<?php
class WebUser extends SWebUser {
	protected $modelClass = "User";

	public function login($identity,$duration=0){
		$result = parent::login($identity,$duration);
		if(!$result)
			return false;
		$user = $identity->getUser();
		if(!$user->collaborator_group_id){
			$collaboratorGroupId = LoginHelper::getLoginCollaboratorGroupId();
			if($collaboratorGroupId){
				$user->collaborator_group_id = $collaboratorGroupId;
				$user->save(true,array(
					"collaborator_group_id"
				));
			}
		}
		return true;
	}
}