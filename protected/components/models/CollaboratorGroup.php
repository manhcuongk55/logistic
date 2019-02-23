<?php

Yii::import('application.models._base.BaseCollaboratorGroup');

class CollaboratorGroup extends BaseCollaboratorGroup
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function getCollaboratorOfType($type){
		return Collaborator::model()->findByAttributes(array(
			"collaborator_group_id" => $this->id,
			"type" => $type
		));
	}
}