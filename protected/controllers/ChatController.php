<?php
class ChatController extends Controller {
    function getReceivers($userID,$userType){
        $data = array();
        switch($userType){
            case "admin":
                $users = User::model()->findAll();
                foreach($users as $user){
                   $data["user-$user->id"] = array(
                       "userID" => "user-$user->id",
                       "name" => $user->name,
                   );
                }
                break;
            case "collaborator":
                $collaborator = Collaborator::model()->findByPk($userID);
                if($collaborator){
                    $criteria = new CDbCriteria();
                    if($collaborator->is_manager){
                        $criteria->compare("collaborator_group_id",$collaborator->collaborator_group_id);
                    } else {
                        $criteria->compare("collaborator_id",$collaborator->id);
                    }
                    $users = User::model()->findAll($criteria);
                    foreach($users as $user){
                       $data["user-$user->id"] = array(
                           "userID" => "user-$user->id",
                           "name" => $user->name,
                       );
                    }
                }
                break;
            case "user":
                $user = User::model()->findByPk($userID);
                if($user){
                    $collaboratorManager = Collaborator::model()->findByAttributes(array(
                        "collaborator_group_id" => $user->collaborator_group_id,
                        "is_manager" => 1,
                    ));
                    if($collaboratorManager){
                        $data["collaborator-$collaboratorManager->id"] = array(
                            "userID" => "collaborator-$collaboratorManager->id",
                            "id" => $collaboratorManager->id,
                            "name" => $collaboratorManager->name,
                            "image" => $collaboratorManager->url("image"),
                            "email" => $collaboratorManager->email,
                            "phone" => $collaboratorManager->phone,
                        );
                    }
                    if($user->collaborator_id){
                        $collaborator = Collaborator::model()->findByPk($user->collaborator_id);
                        if($collaborator){
                            $data["collaborator-$collaborator->id"] = array(
                                "userID" => "collaborator-$collaborator->id",
                                "id" => $collaborator->id,
                                "name" => $collaborator->name,
                                "image" => $collaborator->url("image"),
                                "email" => $collaborator->email,
                                "phone" => $collaborator->phone,
                            );
                        }
                    }
                    $admins = Admin::model()->findAll();
                    foreach($admins as $admin){
                        $data["admin-$admin->id"] = array(
                            "userID" => "admin-$admin->id",
                            "name" => $admin->name,
                        );
                    } 
                }
                break;
        }
        return $data;
    }

	public function actionReceivers(){
        $userIDStr = Input::get("user_id");
        $arr = explode("-",$userIDStr);
        $userType = $arr[0];
        $userID = $arr[1];

        if(CacheHelper::returnHttpCacheIfAvailable(null,array(
            "chat-receivers",
            "chat-receivers-$userType",
            CacheHelper::getKeyForUser("chat-receivers-$userIDStr")
        )))
            return;
        if(CacheHelper::beginFragmentWithHttpCacheInfo(array(
            "differentByUser" => true
        ))){
            $data = $this->getReceivers($userID,$userType);
            echo(json_encode($data));
            CacheHelper::endFragment();
        }
    }
}