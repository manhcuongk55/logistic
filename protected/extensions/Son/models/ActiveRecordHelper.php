<?php
class ActiveRecordHelper {
    public static function getFirstError($model){
        foreach($model->getErrors() as $errorsOfAttr){
            return $errorsOfAttr[0];
        }
    }
}