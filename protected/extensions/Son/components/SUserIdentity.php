<?php
class SUserIdentity extends CUserIdentity {
	const ERROR_USER_NOT_ACTIVE = "ERROR_USER_NOT_ACTIVE";

	public $_id;
    public $record;

	public function getId()
    {
        return $this->_id;
    }


}